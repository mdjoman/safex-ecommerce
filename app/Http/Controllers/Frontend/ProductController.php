<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', 'active');

        // Filter by category
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by subcategory
        if ($request->has('subcategory')) {
            $query->where('sub_category_id', $request->subcategory);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%")
                  ->orWhere('brand', 'LIKE', "%{$search}%");
            });
        }

        // Sort
        switch ($request->sort) {
            case 'price_low':
                $query->orderBy('selling_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('selling_price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('name');
        }

        $products = $query->paginate(12);
        $categories = Category::where('status', 'active')->get();
        $subCategories = SubCategory::where('status', 'active')->get();

        return view('frontend.products.index', compact('products', 'categories', 'subCategories'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
                         ->where('status', 'active')
                         ->firstOrFail();

        // Get related products
        $relatedProducts = Product::where('category_id', $product->category_id)
                                 ->where('id', '!=', $product->id)
                                 ->where('status', 'active')
                                 ->take(4)
                                 ->get();

        // Create or update lead for product view
        $this->createLead($product);

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }

    private function createLead($product)
    {
        $leadId = Lead::generateLeadId();

        $leadData = [
            'lead_id' => $leadId,
            'interested_product' => $product->name,
            'source' => 'Product View',
            'status' => 'new'
        ];

        // If user is authenticated
        if (auth()->check()) {
            $leadData['customer_name'] = auth()->user()->name;
            $leadData['email'] = auth()->user()->email;
        }

        // Check if session has lead
        if (session()->has('lead_id')) {
            $lead = Lead::where('lead_id', session('lead_id'))->first();
            if ($lead) {
                $lead->update(['interested_product' => $product->name]);
                return;
            }
        }

        // Store lead data in session for later update
        $lead = Lead::create($leadData);
        session(['lead_id' => $lead->lead_id]);
    }
}
