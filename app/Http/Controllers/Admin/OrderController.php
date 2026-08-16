<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderStatusUpdate;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled'
        ]);

        $oldStatus = $order->order_status;
        $order->order_status = $request->status;
        $order->save();

        // Update lead if converted
        if ($request->status === 'delivered') {
            $lead = Lead::where('email', $order->customer_email)
                       ->orWhere('phone', $order->customer_phone)
                       ->first();
            if ($lead) {
                $lead->status = 'converted';
                $lead->save();
            }
        }

        // Send notification
        try {
            Notification::route('mail', $order->customer_email)
                ->notify(new OrderStatusUpdate($order, $oldStatus));
        } catch (\Exception $e) {
            // Log error but continue
        }

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->items()->delete();
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
}
