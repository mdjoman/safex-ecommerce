<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            $orders = Order::where('user_id', auth()->id())
                          ->orderBy('created_at', 'desc')
                          ->get();
        } else {
            $orders = collect();
        }

        return view('frontend.orders.index', compact('orders'));
    }

    public function show($orderId)
    {
        $order = Order::where('order_id', $orderId)
                     ->with('items.product')
                     ->firstOrFail();

        // Check if user can view this order
        if (auth()->check() && $order->user_id != auth()->id()) {
            abort(403);
        }

        return view('frontend.orders.show', compact('order'));
    }

    public function track(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string|exists:orders,order_id'
        ]);

        $order = Order::where('order_id', $request->order_id)
                     ->with('items.product')
                     ->first();

        return view('frontend.orders.track', compact('order'));
    }

    public function trackForm()
    {
        return view('frontend.orders.track-form');
    }
}
