<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderRequest;

class OrderRequestController extends Controller
{
    public function index()
    {
        $orders = OrderRequest::with('items.item')->latest()->get();
        return view('order_requests.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $order = OrderRequest::create($request->only([
            'requester',
            'purpose',
            'urgency',
            'date_requested',
        ]));

        foreach ($request->items as $item) {
            $order->items()->create($item);
        }

        return redirect()->back()->with('success', 'Order request created');
    }
}
