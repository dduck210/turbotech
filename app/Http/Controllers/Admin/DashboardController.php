<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

/**
 * Minimal placeholder — the full stats/charts version (matching legacy
 * `admin/view/dashboard.php`'s revenue/category charts) is built in
 * Phase 4 Group F alongside StatsController.
 */
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
        ]);
    }
}
