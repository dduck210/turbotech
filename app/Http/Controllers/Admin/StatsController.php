<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Ported from `Codemoi\Controller\Admin\StatsController` +
 * `Codemoi\Model\Stats` (legacy `src/Model/Stats.php`). Every revenue/
 * bestseller query uses `Order::scopeNotCancelled()` — cancelling an
 * order never resets `status_pay`, so without this a cancelled
 * bank-transfer order marked paid before cancellation would permanently
 * inflate every figure here.
 */
class StatsController extends Controller
{
    public function __invoke(Request $request)
    {
        $fromDate = $request->input('from_date', '');
        $toDate = $request->input('to_date', '');
        $sortOrder = $request->input('sort_product', 'DESC') === 'ASC' ? 'ASC' : 'DESC';

        $revenueQuery = Order::query()->where('status_pay', 1)->notCancelled();
        if ($fromDate !== '') {
            $revenueQuery->whereDate('order_date', '>=', $fromDate);
        }
        if ($toDate !== '') {
            $revenueQuery->whereDate('order_date', '<=', $toDate);
        }
        $revenueStats = $revenueQuery
            ->selectRaw('DATE(order_date) as order_day, SUM(total_amount) as total_revenue, COUNT(id_bill) as total_orders')
            ->groupBy('order_day')
            ->orderByDesc('order_day')
            ->get();

        $productsQuery = DB::table('cart')
            ->join('bill', 'bill.id_bill', '=', 'cart.id_bill')
            ->join('product', 'product.id_pro', '=', 'cart.id_pro')
            ->where('bill.status_pay', 1)
            ->where('bill.status', '!=', Order::STATUS_CANCELLED);
        if ($fromDate !== '') {
            $productsQuery->whereDate('bill.order_date', '>=', $fromDate);
        }
        if ($toDate !== '') {
            $productsQuery->whereDate('bill.order_date', '<=', $toDate);
        }
        $productStats = $productsQuery
            ->select(['product.id_pro', 'product.name_pro', 'product.img_pro'])
            ->selectRaw('SUM(cart.quantity) as total_sold, SUM(cart.total_amount) as total_revenue')
            ->groupBy('product.id_pro', 'product.name_pro', 'product.img_pro')
            ->orderBy('total_sold', $sortOrder)
            ->get();

        return view('admin.stats.index', [
            'revenueStats' => $revenueStats,
            'productStats' => $productStats,
            'inventoryStats' => Product::orderBy('stock')->get(),
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'sortProduct' => $sortOrder,
        ]);
    }
}