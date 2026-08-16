<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;
use App\Models\LandingPage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get active banners
        $banners = Banner::where('status', 'active')
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->orderBy('position')
                        ->get();

        // Get featured products
        $featuredProducts = Product::where('status', 'active')
                                  ->orderBy('created_at', 'desc')
                                  ->take(8)
                                  ->get();

        // Get categories with products
        $categories = Category::where('status', 'active')
                             ->with(['products' => function($query) {
                                 $query->where('status', 'active')->take(4);
                             }])
                             ->take(4)
                             ->get();

        // Get landing pages
        $landingPages = LandingPage::where('status', 'active')->get();

        return view('frontend.home.index', compact('banners', 'featuredProducts', 'categories', 'landingPages'));
    }

    public function about()
    {
        return view('frontend.about.index');
    }

    public function privacy()
    {
        return view('frontend.policy.privacy');
    }

    public function terms()
    {
        return view('frontend.policy.terms');
    }
}
