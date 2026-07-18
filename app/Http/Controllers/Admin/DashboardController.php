<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'userCount' => User::count(),
            'productCount' => Product::count(),
            'categoryCount' => Category::count(),
            'orderCount' => Order::count(),
            'commentCount' => Comment::count(),
            'recentOrders' => Order::orderByDesc('order_date')->limit(5)->get(),
            'lowStockProducts' => Product::where('stock', '<=', 5)->orderBy('stock')->limit(5)->get(),
        ]);
    }
}