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

      public function trackForm()
    {
        return view('frontend.orders.track-form');
    }

    public function track(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string|exists:orders,order_id'
        ]);

        $order = Order::where('order_id', $request->order_id)
                     ->with('items.product')
                     ->first();

        // Check if it's an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'order' => $this->formatOrderData($order)
            ]);
        }

        return view('frontend.orders.track', compact('order'));
    }

    private function formatOrderData($order)
    {
        return [
            'order_id' => $order->order_id,
            'order_status' => $order->order_status,
            'customer_name' => $order->customer_name,
            'customer_email' => $order->customer_email,
            'customer_phone' => $order->customer_phone,
            'shipping_address' => $order->shipping_address,
            'billing_address' => $order->billing_address,
            'subtotal' => (float) $order->subtotal,
            'discount' => (float) ($order->discount ?? 0),
            'discount_code' => $order->discount_code,
            'discount_amount' => (float) ($order->discount_amount ?? 0),
            'tax' => (float) ($order->tax ?? 0),
            'shipping_cost' => (float) ($order->shipping_cost ?? 0),
            'total' => (float) $order->total,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'payment_id' => $order->payment_id,
            'transaction_id' => $order->transaction_id,
            'shipping_tracking' => $order->shipping_tracking,
            'delivered_at' => $order->delivered_at,
            'cancelled_at' => $order->cancelled_at,
            'cancellation_reason' => $order->cancellation_reason,
            'notes' => $order->notes,
            'admin_notes' => $order->admin_notes,
            'created_at' => $order->created_at->toISOString(),
            'items' => $order->items->map(function($item) {
                return [
                    'product_name' => $item->product->name ?? 'Product',
                    'product_image' => $item->product->featured_image ? asset('storage/' . $item->product->featured_image) : null,
                    'sku' => $item->product->sku ?? null,
                    'quantity' => (int) $item->quantity,
                    'price' => (float) $item->price,
                ];
            })
        ];
    }
}
