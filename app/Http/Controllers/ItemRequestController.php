<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\RequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemRequestController extends Controller
{
    
    public function index()
    {
        $items = Item::available()->with('category')->get();
        return view('requests.index', compact('items'));
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        // Get item details for cart display
        $cartItems = [];
        foreach ($cart as $itemId => $cartItem) {
            $item = Item::with('category')->find($itemId);
            if ($item) {
                $cartItems[$itemId] = array_merge($cartItem, [
                    'item' => $item,
                    'item_name' => $item->name, 
                    'category_name' => $item->category->name ?? 'Uncategorized',
                ]);
            }
        }
        return view('requests.cart', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        $item = Item::findOrFail($request->item_id);

        // Check if item is available
        if (!$item->isAvailable()) {
            return back()->with('error', 'Item is not available for request');
        }

        // Check if quantity is available
        if ($item->quantity < $request->quantity) {
            return back()->with('error', 'Not enough items available. Only ' . $item->quantity . ' left.');
        }

        $cart = session()->get('cart', []);

        // If item already in cart, update quantity
        if (isset($cart[$item->id])) {
            $newQuantity = $cart[$item->id]['quantity'] + $request->quantity;
            
            // Check if total requested exceeds available quantity
            if ($item->quantity < $newQuantity) {
                return back()->with('error', 'Total requested quantity exceeds available stock');
            }
            
            $cart[$item->id]['quantity'] = $newQuantity;
            // Update notes if provided
            if ($request->has('notes')) {
                $cart[$item->id]['notes'] = $request->notes;
            }
        } else {
            $cart[$item->id] = [
                'item_id' => $item->id,
                'quantity' => $request->quantity,
                'notes' => $request->notes ?? '',
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Item added to cart!');
    }

    public function updateCart(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        $cart = session()->get('cart', []);
        
        if (!isset($cart[$itemId])) {
            return back()->with('error', 'Item not found in cart');
        }

        $item = Item::findOrFail($itemId);
        
        // Check if item is still available
        if (!$item->isAvailable()) {
            return back()->with('error', 'Item is no longer available');
        }
        
        // Check if new quantity is available
        if ($item->quantity < $request->quantity) {
            return back()->with('error', 'Not enough items available. Only ' . $item->quantity . ' left.');
        }

        $cart[$itemId]['quantity'] = $request->quantity;
        $cart[$itemId]['notes'] = $request->notes ?? $cart[$itemId]['notes'] ?? '';
        
        session()->put('cart', $cart);

        return back()->with('success', 'Cart updated successfully');
    }

    public function removeFromCart($itemId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            session()->put('cart', $cart);
            return back()->with('success', 'Item removed from cart');
        }

        return back()->with('error', 'Item not found in cart');
    }
    public function clearCart()
    {
        session()->forget('cart');
        return back()->with('success', 'Cart cleared successfully');
    }

    public function submitRequest(Request $request)
    {
        $request->validate([
            'purpose' => 'required|string|max:255',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'required_date' => 'nullable|date|after_or_equal:today',
            'remarks' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500', // Added notes validation
        ]);
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Cart is empty');
        }
        DB::beginTransaction();
        try {
            $itemRequest = ItemRequest::create([
                'user_id' => Auth::id(),
                'purpose' => $request->purpose,
                'priority' => $request->priority ?? 'medium',
                'status' => 'pending',
                'request_date' => now(),
                'required_date' => $request->required_date,
                'remarks' => $request->remarks, 
                'notes' => $request->notes,
            ]);

            // Add items to request
            foreach ($cart as $itemId => $cartItem) {
                $item = Item::find($itemId);
                if (!$item) {
                    throw new \Exception("Item not found: {$itemId}");
                }
                // Check if item is still available
                if (!$item->isAvailable()) {
                    throw new \Exception("Item '{$item->name}' is no longer available");
                }
                if ($item->quantity < $cartItem['quantity']) {
                    throw new \Exception("Item '{$item->name}' has insufficient quantity. Only {$item->quantity} available");
                }
                RequestItem::create([
                    'item_request_id' => $itemRequest->id,
                    'item_id' => $itemId,
                    'quantity' => $cartItem['quantity'],
                    'remarks' => $cartItem['notes'] ?? null, // Cart notes go to request item remarks
                    'status' => 'pending',
                ]);
            }

            // Clear cart
            session()->forget('cart');

            DB::commit();

            return redirect()->route('requests.my-requests')->with('success', 'Request submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit request: ' . $e->getMessage());
        }
    }

    public function myRequests(Request $request)
    {
        $query = ItemRequest::with(['requestItems.item.category'])
            ->where('user_id', Auth::id());
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        // Search by purpose
        if ($request->filled('search')) {
            $query->where('purpose', 'like', '%' . $request->search . '%');
        }
        $requests = $query->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('requests.my-requests', compact('requests'));
    }

    public function show($id)
    {
        $request = ItemRequest::with(['requestItems.item.category', 'user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        return view('requests.show', compact('request'));
    }

    public function cancelRequest($id)
    {
        $request = ItemRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $request->update([
                'status' => 'cancelled',
                'cancelled_by' => Auth::id(),
                'cancelled_at' => now(),
            ]);

            DB::commit();
            return redirect()->route('requests.my-requests')->with('success', 'Request cancelled successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel request');
        }
    }
}