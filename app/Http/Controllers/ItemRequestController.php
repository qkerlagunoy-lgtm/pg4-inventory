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
        $items = Item::all();
        return view('requests.index', compact('items'));
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        return view('requests.cart', compact('cart'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = Item::findOrFail($request->item_id);

        if ($item->available_quantity < $request->quantity) {
            return back()->with('error', 'Not enough items available');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$item->id])) {
            $cart[$item->id]['quantity'] += $request->quantity;
        } else {
            $cart[$item->id] = [
                'item_id' => $item->id,
                'item_name' => $item->item_name,
                'category' => $item->category,
                'quantity' => $request->quantity,
                'available' => $item->available_quantity,
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Item added to cart!');
    }

    public function removeFromCart($itemId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Item removed from cart');
    }

    public function submitRequest(Request $request)
    {
        $request->validate([
            'purpose' => 'required|string|max:255',
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
                'status' => 'pending',
            ]);

            foreach ($cart as $cartItem) {
                RequestItem::create([
                    'item_request_id' => $itemRequest->id,
                    'item_id' => $cartItem['item_id'],
                    'quantity' => $cartItem['quantity'],
                ]);
            }

            session()->forget('cart');

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Request submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit request: ' . $e->getMessage());
        }
    }

    public function myRequests(Request $request)
    {
        $query = ItemRequest::with(['requestItems.item'])
            ->where('user_id', Auth::id());

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search by purpose
        if ($request->has('search') && $request->search != '') {
            $query->where('purpose', 'like', '%' . $request->search . '%');
        }

        $requests = $query->orderBy('created_at', 'desc')->get();

        return view('requests.my-requests', compact('requests'));
    }
}