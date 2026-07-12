<?php

namespace Codemoi\Controller\Admin;

use Codemoi\Model\Stats;

/**
 * Statistics/reports. Ported from `public/admin/index.php` case `list_thongke`.
 */
class StatsController extends AdminController
{
    public function index(): void
    {
        $this->requireAdmin();

        $from_date = $_POST['from_date'] ?? '';
        $to_date = $_POST['to_date'] ?? '';
        $sort_product = $_POST['sort_product'] ?? 'DESC';

        $this->render('list_statistic', [
            'revenue_stats' => Stats::revenueByDate($from_date, $to_date),
            'product_sold_stats' => Stats::productsSoldByDate($from_date, $to_date, $sort_product),
            'inventory_stats' => Stats::inventory(),
            'from_date' => $from_date,
            'to_date' => $to_date,
            'sort_product' => $sort_product,
        ]);
    }
}
