<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        return view('frontend.cart.index', compact('cart'));
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
            'cart_count' => $cart->count,
            'total' => $cart->total
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
            'cart_count' => $cart->count,
            'total' => $cart->total
        ]);
    }

    public function clear()
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        session(['cart_count' => 0]);

        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully.');
    }

    private function getCart()
    {
        if (auth()->check()) {
            $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        } else {
            $sessionId = Session::getId();
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }
        return $cart;
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
