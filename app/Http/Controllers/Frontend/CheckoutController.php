<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Lead;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderConfirmation;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('frontend.checkout.index', compact('cart'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:255',
            'shipping_address' => 'required|string',
            'billing_address' => 'nullable|string',
            'payment_method' => 'required|in:cod,bkash,nagad'
        ]);

        $cart = $this->getCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        DB::beginTransaction();

        try {
            // Calculate totals
            $subtotal = 0;
            foreach ($cart->items as $item) {
                $subtotal += $item->product->price * $item->quantity;
            }

            $discount = 0;
            $tax = $subtotal * 0.15; // 15% VAT
            $total = $subtotal + $tax - $discount;

            // Create order
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address ?? $request->shipping_address,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'order_status' => 'pending'
            ]);

            // Generate order ID
            $order->order_id = 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
            $order->save();

            // Create order items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'sku' => $item->product->sku,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'total' => $item->product->price * $item->quantity
                ]);

                // Update product stock
                $product = $item->product;
                $product->stock_qty -= $item->quantity;
                $product->save();
            }

            // Update lead
            $this->updateLead($order);

            // Send confirmation notification
            try {
                Notification::route('mail', $order->customer_email)
                    ->notify(new OrderConfirmation($order));
            } catch (\Exception $e) {
                // Log error but continue
            }

            // Clear cart
            $cart->items()->delete();
            session(['cart_count' => 0]);

            DB::commit();

            return redirect()->route('order.success', $order->order_id)
                           ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    public function success($orderId)
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();
        return view('frontend.checkout.success', compact('order'));
    }

    private function getCart()
    {
        if (auth()->check()) {
            return Cart::with('items.product')->firstOrCreate(['user_id' => auth()->id()]);
        } else {
            return Cart::with('items.product')->firstOrCreate(['session_id' => session()->getId()]);
        }
    }

    private function updateLead($order)
    {
        // Find lead by email or phone
        $lead = Lead::where('email', $order->customer_email)
                   ->orWhere('phone', $order->customer_phone)
                   ->first();

        if ($lead) {
            $lead->update([
                'status' => 'converted',
                'customer_name' => $order->customer_name,
                'notes' => ($lead->notes ?? '') . "\nOrder placed: " . $order->order_id
            ]);
        } else {
            // Create new lead from order
            Lead::create([
                'lead_id' => 'LEAD-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                'customer_name' => $order->customer_name,
                'phone' => $order->customer_phone,
                'email' => $order->customer_email,
                'source' => 'Order Placed',
                'status' => 'converted',
                'notes' => 'Order: ' . $order->order_id
            ]);
        }
    }
}
