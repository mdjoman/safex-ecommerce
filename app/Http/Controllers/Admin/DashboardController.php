<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Lead;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalLeads = Lead::count();
        $totalCategories = Category::count();

        // Revenue Stats
        $todayRevenue = Order::whereDate('created_at', Carbon::today())->sum('total');
        $weekRevenue = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total');
        $monthRevenue = Order::whereMonth('created_at', Carbon::now()->month)->sum('total');
        $totalRevenue = Order::sum('total');

        // Recent Orders
        $recentOrders = Order::with(['user', 'items'])->latest()->take(5)->get();

        // Order Status Counts
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $processingOrders = Order::where('order_status', 'processing')->count();
        $deliveredOrders = Order::where('order_status', 'delivered')->count();
        $cancelledOrders = Order::where('order_status', 'cancelled')->count();

        // Lead Status Counts
        $newLeads = Lead::where('status', 'new')->count();
        $contactedLeads = Lead::where('status', 'contacted')->count();
        $convertedLeads = Lead::where('status', 'converted')->count();

        // Monthly Revenue Chart Data
        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRevenue[] = Order::whereMonth('created_at', $i)
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->sum('total');
        }

        // Top Products
        $topProducts = Product::withCount('orderItems')
                             ->orderBy('order_items_count', 'desc')
                             ->take(5)
                             ->get();

        return view('admin.dashboard.index', compact(
            'totalOrders', 'totalProducts', 'totalLeads', 'totalCategories',
            'todayRevenue', 'weekRevenue', 'monthRevenue', 'totalRevenue',
            'recentOrders', 'pendingOrders', 'processingOrders',
            'deliveredOrders', 'cancelledOrders',
            'newLeads', 'contactedLeads', 'convertedLeads',
            'monthlyRevenue', 'topProducts'
        ));
    }
}
