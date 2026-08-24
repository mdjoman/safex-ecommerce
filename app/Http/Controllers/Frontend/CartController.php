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
    public function index()
    {
        $cart = $this->getCart();

        // Get settings from database
        $settings = Setting::pluck('value', 'key')->toArray();

        $vatRate = $settings['vat_rate'] ?? 0;
        $deliveryChargeInside = $settings['delivery_charge_inside_city'] ?? 0;
        $deliveryChargeOutside = $settings['delivery_charge_outside_city'] ?? 0;

        // Initialize calculation variables
        $subtotal = 0;
        $totalDiscount = 0;
        $totalCouponDiscount = 0;
        $appliedCoupons = [];
        $cartItems = [];

        if ($cart && $cart->items) {
            foreach ($cart->items as $item) {
                $product = $item->product;
                $quantity = $item->quantity;

                // Get price (use discount price if available)
                $unitPrice = $product->discount_price ?? $product->selling_price;
                $originalPrice = $product->selling_price;

                // Calculate row totals
                $rowSubtotal = $unitPrice * $quantity;
                $rowDiscount = 0;
                $rowCouponDiscount = 0;

                // Calculate product discount per row
                if ($product->discount_price && $product->discount_price < $product->selling_price) {
                    $rowDiscount = ($product->selling_price - $product->discount_price) * $quantity;
                    $totalDiscount += $rowDiscount;
                }

                // Calculate coupon discount per row
                if ($product->coupon_code && $product->coupon_less_amount > 0) {
                    $rowCouponDiscount = $product->coupon_less_amount * $quantity;
                    $totalCouponDiscount += $rowCouponDiscount;
                    $appliedCoupons[] = [
                        'code' => $product->coupon_code,
                        'amount' => $rowCouponDiscount,
                        'product_name' => $product->name
                    ];
                }

                // Row total after all discounts
                $rowTotal = $rowSubtotal - $rowDiscount - $rowCouponDiscount;

                // Add to subtotal
                $subtotal += $rowSubtotal;

                // Store item data for view
                $cartItems[] = (object) [
                    'id' => $item->id,
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'original_price' => $originalPrice,
                    'row_subtotal' => $rowSubtotal,
                    'row_discount' => $rowDiscount,
                    'row_coupon_discount' => $rowCouponDiscount,
                    'row_total' => $rowTotal,
                    'has_discount' => $product->discount_price,
                    'has_coupon' => $product->coupon_code && $product->coupon_less_amount > 0,
                    'coupon_code' => $product->coupon_code,
                    'coupon_less_amount' => $product->coupon_less_amount ?? 0,
                ];
            }
        }

        // Calculate totals
        $totalAfterDiscount = $subtotal - $totalDiscount - $totalCouponDiscount;
        $vatAmount = $totalAfterDiscount * ($vatRate / 100);
        $grandTotal = $totalAfterDiscount + $vatAmount + $deliveryChargeInside;

        // Get featured products for empty cart
        $featuredProducts = Product::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return view('frontend.cart.index', compact(
            'cart',
            'vatRate',
            'deliveryChargeInside',
            'deliveryChargeOutside',
            'totalDiscount',
            'totalCouponDiscount',
            'appliedCoupons',
            'subtotal',
            'totalAfterDiscount',
            'vatAmount',
            'grandTotal',
            'featuredProducts',
            'cartItems'
        ));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = $this->getCart();
        $product = Product::find($request->product_id);

        // Check stock
        if ($product->stock_qty < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available.'
            ]);
        }

        // Add or update cart item
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

        // Update lead
        $this->updateLead($product);

        // Update session cart count
        session(['cart_count' => $cart->count]);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully.',
            'cart_count' => $cart->count
        ]);
    }

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

        $cart = $this->getCart();

        return response()->json([
            'success' => true,
            'cart_count' => $cart->count
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id'
        ]);

        CartItem::find($request->item_id)->delete();
        $cart = $this->getCart();

        return response()->json([
            'success' => true,
            'cart_count' => $cart->count
        ]);
    }

    public function clear()
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        session(['cart_count' => 0]);

        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        $couponCode = $request->coupon_code;

        $cart = $this->getCart();

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
            if ($item->product->coupon_code == $couponCode && $item->product->coupon_less_amount > 0) {
                $found = true;
                $discount = $item->product->coupon_less_amount * $item->quantity;
                $totalCouponDiscount += $discount;
                $appliedProducts[] = $item->product->name;
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
                'message' => 'Coupon applied successfully! You saved BDT ' . number_format($totalCouponDiscount, 2),
                'total_discount' => $totalCouponDiscount,
                'applied_products' => $appliedProducts
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code for items in your cart'
            ]);
        }
    }

    public function removeCoupon(Request $request)
    {
        session()->forget('applied_coupon');

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed successfully'
        ]);
    }

    /**
     * Place Order
     */
    public function placeOrder(Request $request)
    {
        try {
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|max:20',
                'shipping_address' => 'required|string',
                'city' => 'required|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'order_notes' => 'nullable|string',
                'payment_method' => 'required|string',
                'grand_total' => 'required|numeric',
            ]);

            $cart = $this->getCart();

            if (!$cart || $cart->items->count() == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty.'
                ]);
            }

            // Generate order number
            $orderNumber = 'ORD-' . date('Y') . '-' . str_pad(Order::count() + 1, 6, '0', STR_PAD_LEFT);

            // Calculate totals
            $subtotal = 0;
            $totalDiscount = 0;
            $totalCouponDiscount = 0;
            $discountCode = null;

            foreach ($cart->items as $item) {
                $price = $item->product->discount_price ?? $item->product->selling_price;
                $subtotal += $price * $item->quantity;

                if ($item->product->discount_price && $item->product->discount_price < $item->product->selling_price) {
                    $totalDiscount += ($item->product->selling_price - $item->product->discount_price) * $item->quantity;
                }

                if ($item->product->coupon_code && $item->product->coupon_less_amount > 0) {
                    $totalCouponDiscount += $item->product->coupon_less_amount * $item->quantity;
                    $discountCode = $item->product->coupon_code;
                }
            }

            $totalAfterDiscount = $subtotal - $totalDiscount - $totalCouponDiscount;
            $settings = Setting::pluck('value', 'key')->toArray();
            $vatRate = $settings['vat_rate'] ?? 0;
            $vatAmount = $totalAfterDiscount * ($vatRate / 100);
            $deliveryCharge = $settings['delivery_charge_inside_city'] ?? 0;
            $grandTotal = $totalAfterDiscount + $vatAmount + $deliveryCharge;

            // Create order
            $order = Order::create([
                'order_id' => $orderNumber,
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->shipping_address,
                'subtotal' => $subtotal,
                'discount' => $totalDiscount + $totalCouponDiscount,
                'discount_code' => $discountCode,
                'discount_amount' => $totalCouponDiscount,
                'tax' => $vatAmount,
                'shipping_cost' => $deliveryCharge,
                'total' => $grandTotal,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'notes' => $request->order_notes,
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

                // Decrease stock
                $item->product->decreaseStock($item->quantity);
            }

            // Clear cart
            $cart->items()->delete();
            session(['cart_count' => 0]);

            // Send email to customer
            try {
                Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
            } catch (\Exception $e) {
                \Log::error('Customer email send failed: ' . $e->getMessage());
            }

            // Send email to admin
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

    public function getCartSummary()
    {
        $cart = $this->getCart();

        $settings = Setting::pluck('value', 'key')->toArray();
        $vatRate = $settings['vat_rate'] ?? 0;
        $deliveryCharge = $settings['delivery_charge_inside_city'] ?? 0;

        $subtotal = 0;
        $totalDiscount = 0;
        $totalCouponDiscount = 0;

        if ($cart && $cart->items) {
            foreach ($cart->items as $item) {
                $price = $item->product->discount_price ?? $item->product->selling_price;
                $subtotal += $price * $item->quantity;

                if ($item->product->discount_price && $item->product->discount_price < $item->product->selling_price) {
                    $totalDiscount += ($item->product->selling_price - $item->product->discount_price) * $item->quantity;
                }

                if ($item->product->coupon_code && $item->product->coupon_less_amount > 0) {
                    $totalCouponDiscount += $item->product->coupon_less_amount * $item->quantity;
                }
            }
        }

        $totalAfterDiscount = $subtotal - $totalDiscount - $totalCouponDiscount;
        $vatAmount = $totalAfterDiscount * ($vatRate / 100);
        $grandTotal = $totalAfterDiscount + $vatAmount + $deliveryCharge;

        return response()->json([
            'success' => true,
            'data' => [
                'subtotal' => $subtotal,
                'product_discount' => $totalDiscount,
                'coupon_discount' => $totalCouponDiscount,
                'total_after_discount' => $totalAfterDiscount,
                'vat_rate' => $vatRate,
                'vat_amount' => $vatAmount,
                'delivery_charge' => $deliveryCharge,
                'grand_total' => $grandTotal,
                'items_count' => $cart->items->count() ?? 0
            ]
        ]);
    }
}
