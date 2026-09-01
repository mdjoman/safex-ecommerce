<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Lead;
use App\Models\Setting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use App\Mail\AdminOrderNotificationMail;

class CartController extends Controller
{
    /**
     * Common function to get cart with items
     */
    private function getCart()
    {
        if (auth()->check()) {
            $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        } else {
            $sessionId = Session::getId();
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }
        return $cart->load('items.product');
    }

    /**
     * Common function to calculate cart summary
     */
    private function calculateCartSummary($cart)
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $vatRate = $settings['vat_rate'] ?? 0;
        $deliveryCharge = session('delivery_charge')
            ?? ($settings['delivery_charge_inside_city'] ?? 0);
        $delivery_type = session('delivery_type')
            ?? 'inside';

        $appliedCoupon = session('applied_coupon', null);

        $subtotal = 0;
        $totalProductDiscount = 0;
        $totalCouponDiscount = 0;
        $items = [];

        if ($cart && $cart->items) {
            foreach ($cart->items as $item) {
                $product = $item->product;
                $quantity = $item->quantity;

                $unitPrice = $product->discount_price ?? $product->selling_price;
                $originalPrice = $product->selling_price;

                $rowSubtotal = $unitPrice * $quantity;

                $rowProductDiscount = 0;
                if ($product->discount_price && $product->discount_price < $product->selling_price) {
                    $rowProductDiscount = ($product->selling_price - $product->discount_price) * $quantity;
                    $totalProductDiscount += $rowProductDiscount;
                }

                $rowCouponDiscount = 0;
                $hasCoupon = false;
                if ($appliedCoupon && isset($appliedCoupon['products'][$item->product_id])) {
                    $rowCouponDiscount = $appliedCoupon['products'][$item->product_id]['discount'];
                    $hasCoupon = true;
                    $totalCouponDiscount += $rowCouponDiscount;
                }
                $rowTotal = $rowSubtotal  - $rowCouponDiscount;
                $subtotal += $rowSubtotal + $rowProductDiscount;

                $items[] = (object) [
                    'id' => $item->id,
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'row_subtotal' => $rowTotal,
                    'row_discount' => $rowProductDiscount,
                    'row_coupon_discount' => $rowCouponDiscount,
                    'has_discount' => $rowProductDiscount > 0,
                    'has_coupon' => $hasCoupon,
                    'coupon_code' => $hasCoupon ? $appliedCoupon['code'] : null,
                ];
            }
        }

        $totalAfterDiscount = $subtotal - $totalProductDiscount - $totalCouponDiscount;
        $vatAmount = $totalAfterDiscount * ($vatRate / 100);
        $grandTotal = $totalAfterDiscount + $vatAmount + $deliveryCharge;

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'total_product_discount' => $totalProductDiscount,
            'total_coupon_discount' => $totalCouponDiscount,
            'total_after_discount' => $totalAfterDiscount,
            'vat_rate' => $vatRate,
            'vat_amount' => $vatAmount,
            'delivery_charge' => $deliveryCharge,
            'grand_total' => $grandTotal,
            'items_count' => $cart->items->count() ?? 0,
            'applied_coupon' => $appliedCoupon,
            'delivery_type'  => $delivery_type,
        ];
    }

    /**
     * Display cart page
     */
    public function index()
    {
        $cart = $this->getCart();
        $summary = $this->calculateCartSummary($cart);

        $featuredProducts = Product::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return view('frontend.cart.index', [
            'cart' => $cart,
            'cartItems' => $summary['items'],
            'subtotal' => $summary['subtotal'],
            'totalDiscount' => $summary['total_product_discount'],
            'totalCouponDiscount' => $summary['total_coupon_discount'],
            'totalAfterDiscount' => $summary['total_after_discount'],
            'vatRate' =>  $summary['vat_rate'],
            'vatAmount' => $summary['vat_amount'],
            'delivery_charge' =>  $summary['delivery_charge'],
            'grandTotal' => $summary['grand_total'],
            'appliedCoupon' => $summary['applied_coupon'],
            'delivery_type' => $summary['delivery_type'],
            'featuredProducts' => $featuredProducts,
        ]);
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = $this->getCart();
        $product = Product::find($request->product_id);

        if ($product->stock_qty < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available.'
            ]);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
                           ->where('product_id', $request->product_id)
                           ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($product->stock_qty < $newQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available.'
                ]);
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        $this->updateLead($product);
        $cart->load('items');
        session(['cart_count' => $cart->count]);

        $summary = $this->calculateCartSummary($cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully.',
            'cart_count' => $cart->count,
        ]);
    }

    /**
     * Update item quantity
     */
    public function update(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::find($request->item_id);
        $product = $cartItem->product;

        if ($product->stock_qty < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available.'
            ]);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();
        return response()->json([
            'success' => true,
            'message' => 'Item update successfully!',
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id'
        ]);

        CartItem::find($request->item_id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item remove successfully.',
        ]);
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        session(['cart_count' => 0]);
        session()->forget('applied_coupon');

        return response()->json([
            'success' => true,
            'message' => 'Card clear successfully.',
        ]);
    }

    /**
     * Apply coupon
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        $couponCode = $request->coupon_code;
        $cart = $this->getCart();
        $cart->load('items');

        if (!$cart || $cart->items->count() == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty'
            ]);
        }

        $found = false;
        $totalCouponDiscount = 0;
        $appliedProducts = [];

        foreach ($cart->items as $item) {
            $product = $item->product;

            if ($product && $product->coupon_code === $couponCode && $product->coupon_less_amount > 0) {
                $found = true;
                $discount = $product->coupon_less_amount * $item->quantity;
                $totalCouponDiscount += $discount;

                $appliedProducts[$item->product_id] = [
                    'discount' => $discount,
                    'couponCode' => $couponCode,
                ];
            }
        }

        if ($found) {
            session(['applied_coupon' => [
                'code' => $couponCode,
                'discount' => $totalCouponDiscount,
                'products' => $appliedProducts
            ]]);

            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully! You saved ' . setting('currency', 'BDT') . ' ' . number_format($totalCouponDiscount, 2),

            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code for items in your cart'
            ]);
        }
    }

    /**
     * Remove coupon
     */
    public function removeCoupon(Request $request)
    {
        session()->forget('applied_coupon');

         return response()->json([
            'success' => true,
            'message' => 'Coupon remove successfully!',
        ]);
    }

    /**
     * Update delivery charge
     */
    public function updateDelivery(Request $request)
    {
        $request->validate([
            'delivery_type' => 'required|in:inside,outside'
        ]);

        $settings = Setting::pluck('value', 'key')->toArray();

        $deliveryCharge = $request->delivery_type === 'inside'
            ? ($settings['delivery_charge_inside_city'] ?? 0)
            : ($settings['delivery_charge_outside_city'] ?? 0);

        // Store delivery information in session
        session([
            'delivery_type'   => $request->delivery_type,
            'delivery_charge' => $deliveryCharge,
        ]);

        return response()->json([
            'success' => true,
            'delivery_type' => $request->delivery_type,
            'delivery_charge' => $deliveryCharge,
        ]);
    }

    /**
     * Get cart summary (AJAX)
     */
    public function getCartSummary()
    {
        $cart = $this->getCart();
        $cart->load('items');
        $summary = $this->calculateCartSummary($cart);

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }


    /**
     * Place Order
     */
    public function placeOrder(Request $request)
    {

      return($request);

        try {
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'email|max:255',
                'customer_phone' => 'required|string|max:20',
                'shipping_address' => 'required|string',
                'city' => 'string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'order_notes' => 'nullable|string',
                'payment_type' => 'required|string|in:cash,online',
                'payment_method' => 'nullable|string',
                'transaction_id' => 'nullable|string',
                'transaction_number' => 'nullable|string',
            ]);

            $cart = $this->getCart();
            $cart->load('items');

            if (!$cart || $cart->items->count() == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty.'
                ]);
            }
            $summary = $this->calculateCartSummary($cart);

            // Generate order number
            $orderNumber = 'ORD-' . date('Y') . '-' . str_pad(Order::count() + 1, 6, '0', STR_PAD_LEFT);


            $order = Order::create([
                'order_id' => $orderNumber,
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'subtotal' => $summary['subtotal'],
                'discount' =>  $summary['total_product_discount'] + $summary['total_coupon_discount'],
                'coupon_code' => $summary['applied_coupon']['code'] ?? null,
                'coupon_discount' => $summary['total_coupon_discount'],
                'tax' => $summary['vat_amount'],
                'shipping_cost' => $summary['delivery_charge'],
                'total' => $summary['grand_total'],
                'payment_method' => $request->payment_method,
                'payment_type' => $request->payment_type,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'notes' => $request->order_notes,
                'transaction_id' => $request->transaction_id,
                'transaction_number' => $request->transaction_number,

            ]);

            // Create order items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->selling_price,
                    'discount' => $item->product->discount_price ? ($item->product->selling_price - $item->product->discount_price) : 0,
                ]);

                $item->product->decreaseStock($item->quantity);
            }

            // Clear cart
            $cart->items()->delete();
            session(['cart_count' => 0]);
            session()->forget('applied_coupon');

            // Send emails
            try {
                Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
            } catch (\Exception $e) {
                \Log::error('Customer email send failed: ' . $e->getMessage());
            }

            try {
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new AdminOrderNotificationMail($order));
                }
            } catch (\Exception $e) {
                \Log::error('Admin email send failed: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'redirect_url' => route('order.confirmation', ['order' => $order->id])
            ]);

        } catch (\Exception $e) {
            \Log::error('Order placement error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order. Please try again.'
            ]);
        }
    }

    private function updateLead($product)
    {
        if (session()->has('lead_id')) {
            $lead = Lead::where('lead_id', session('lead_id'))->first();
            if ($lead) {
                $lead->update([
                    'interested_product' => $product->name,
                    'source' => 'Add to Cart'
                ]);
            }
        }
    }
}
