<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_items' => Item::count(),
            'pending_requests' => ItemRequest::where('status', 'pending')->count(),
            'urgent_requests' => ItemRequest::where('status', 'urgent')->count(),
            'approved_requests' => ItemRequest::where('status', 'approved')->count(),
            'rejected_requests' => ItemRequest::where('status', 'rejected')->count(),
            'low_stock_items' => Item::where('available_quantity', '<', 10)->count(),
            'expiring_soon' => 0, // Placeholder for future feature
        ];

        $recentRequests = ItemRequest::with(['user', 'requestItems.item'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $lowStockItems = Item::where('available_quantity', '<', 10)
            ->orderBy('available_quantity', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentRequests', 'lowStockItems'));
    }
}