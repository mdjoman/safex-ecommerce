<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Brand;

class ProductController extends Controller
{
     public function index(Request $request)
    {
        $query = Product::where('status', 'active')->with(['category', 'subCategory', 'brand']);

        // ===== FILTER BY MULTIPLE CATEGORIES =====
        if ($request->has('categories')) {
            $categoryIds = explode(',', $request->categories);
            $query->whereIn('category_id', $categoryIds);
        }

        // ===== FILTER BY MULTIPLE SUBCATEGORIES =====
        if ($request->has('subcategories')) {
            $subCategoryIds = explode(',', $request->subcategories);
            $query->whereIn('sub_category_id', $subCategoryIds);
        }

        // ===== FILTER BY MULTIPLE BRANDS =====
        if ($request->has('brands')) {
            $brandIds = explode(',', $request->brands);
            $query->whereIn('brand_id', $brandIds);
        }

        // ===== FILTER BY PRICE RANGE =====
        if ($request->has('price_min')) {
            $query->where('selling_price', '>=', $request->price_min);
        }
        if ($request->has('price_max')) {
            $query->where('selling_price', '<=', $request->price_max);
        }

        // ===== SEARCH =====
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%")
                  ->orWhereHas('brand', function ($brandQuery) use ($search) {
                        $brandQuery->where('name', 'LIKE', "%{$search}%");
                    })
                  ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        // ===== SORTING =====
        switch ($request->sort) {
            case 'price_low':
                $query->orderBy('selling_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('selling_price', 'desc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
        }

        // ===== PAGINATE =====
        $products = $query->paginate(12);

        // ===== GET ALL DATA FOR FILTERS =====
        $categories = Category::where('status', 'active')
            ->withCount(['products' => function($q) {
                $q->where('status', 'active');
            }])
            ->orderBy('name')
            ->get();

        $subCategories = SubCategory::where('status', 'active')
            ->withCount(['products' => function($q) {
                $q->where('status', 'active');
            }])
            ->orderBy('name')
            ->get();

        $brands = Brand::where('status', 'active')
            ->withCount(['products' => function($q) {
                $q->where('status', 'active');
            }])
            ->orderBy('name')
            ->get();

        // ===== GET PRICE RANGE FOR SLIDER =====
        $minPrice = Product::where('status', 'active')->min('selling_price') ?? 0;
        $maxPrice = Product::where('status', 'active')->max('selling_price') ?? 1000;

        // ===== GET SELECTED FILTERS =====
        $selectedCategories = $request->has('categories') ? explode(',', $request->categories) : [];
        $selectedSubCategories = $request->has('subcategories') ? explode(',', $request->subcategories) : [];
        $selectedBrands = $request->has('brands') ? explode(',', $request->brands) : [];

        return view('frontend.products.index', compact(
            'products',
            'categories',
            'subCategories',
            'brands',
            'minPrice',
            'maxPrice',
            'selectedCategories',
            'selectedSubCategories',
            'selectedBrands'
        ));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
                         ->where('status', 'active')
                         ->with(['category', 'subCategory', 'brand'])
                         ->firstOrFail();

        // Increment view count
        $product->increment('views');

        // Get related products
        $relatedProducts = Product::where('category_id', $product->category_id)
                                 ->where('id', '!=', $product->id)
                                 ->where('status', 'active')
                                 ->with(['category', 'brand'])
                                 ->take(4)
                                 ->get();

        // Create or update lead for product view
        $this->createLead($product);

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }

    private function createLead($product)
    {
        // Check if user is logged in
        if (auth()->check()) {
            $lead = Lead::updateOrCreate(
                ['user_id' => auth()->id()],
                [
                    'interested_product' => $product->name,
                    'source' => 'Product View',
                    'product_id' => $product->id
                ]
            );
        } else {
            // For guest users, use session
            $sessionId = session()->getId();
            $lead = Lead::updateOrCreate(
                ['session_id' => $sessionId],
                [
                    'interested_product' => $product->name,
                    'source' => 'Product View',
                    'product_id' => $product->id
                ]
            );
            session(['lead_id' => $lead->id]);
        }
    }

}
