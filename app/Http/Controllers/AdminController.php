<?php

namespace App\Http\Controllers;

use App\Models\ItemRequest;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard(): \Illuminate\View\View
    {
        $stats = [
            'pending_requests' => ItemRequest::where('status', 'pending')->count(),
            'urgent_requests' => ItemRequest::where('status', 'urgent')->count(),
            'approved_requests' => ItemRequest::where('status', 'approved')->count(),
            'rejected_requests' => ItemRequest::where('status', 'rejected')->count(),
            'low_stock_items' => 0,
            'expiring_soon' => 0,
        ];

        $recentRequests = ItemRequest::with('user')
            ->whereIn('status', ['pending', 'urgent'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentRequests' => $recentRequests,
        ]);
    }

    public function orders(): \Illuminate\View\View
    {
        $orders = ItemRequest::with('user', 'requestItems.item')
            ->latest()
            ->paginate(15);

        return view('admin.orders', ['orders' => $orders]);
    }

    public function inventory(): \Illuminate\View\View
    {
        $items = Item::paginate(15);

        return view('admin.inventory', ['items' => $items]);
    }

    public function users(): \Illuminate\View\View
    {
        $users = User::paginate(15);

        return view('admin.users', ['users' => $users]);
    }

    public function categories(): \Illuminate\View\View
    {
        $categories = Category::paginate(15);

        return view('admin.categories', ['categories' => $categories]);
    }
}
