<?php include_once "header.php" ?>
<?php if (!empty($flash_success)): ?>
<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:<?= json_encode($flash_success) ?>,showConfirmButton:false,timer:3000}));</script>
<?php endif; ?>
<?php $listuser = \Codemoi\Model\User::allAdmin();
$listcmt = \Codemoi\Model\Comment::allAdmin();
$listbill = \Codemoi\Model\Order::allAdmin();
$listpro = \Codemoi\Model\Product::allAdmin();
$ds_loai = \Codemoi\Model\Category::all(); ?>

<div class="mb-8 pb-5 border-b border-ink-300">
    <div class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-1">Tổng quan</div>
    <h1 class="font-heading text-3xl text-ink-900">Bảng điều khiển</h1>
</div>

<!-- Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    <?php
    $stats = [
        ['label' => 'Doanh thu hôm nay', 'value' => number_format(\Codemoi\Model\Stats::today()) . ' đ', 'icon' => 'fa-dollar-sign'],
        ['label' => 'Doanh thu tuần này', 'value' => number_format(\Codemoi\Model\Stats::thisWeek()) . ' đ', 'icon' => 'fa-chart-line'],
        ['label' => 'Doanh thu tháng này', 'value' => number_format(\Codemoi\Model\Stats::thisMonth()) . ' đ', 'icon' => 'fa-sack-dollar'],
        ['label' => 'Tổng đơn hàng', 'value' => e(count($listbill)), 'icon' => 'fa-clipboard-list'],
        ['label' => 'Tổng khách hàng', 'value' => e(count($listuser)), 'icon' => 'fa-user'],
        ['label' => 'Tổng sản phẩm', 'value' => e(count($listpro)), 'icon' => 'fa-box'],
        ['label' => 'Tổng danh mục', 'value' => e(count($ds_loai)), 'icon' => 'fa-tags'],
    ];
    foreach ($stats as $stat): ?>
    <div class="card-boutique card-hover rounded-lg p-6">
        <div class="flex items-center justify-between mb-5">
            <span class="text-xs font-semibold uppercase tracking-wider text-ink-500"><?= $stat['label'] ?></span>
            <span class="w-9 h-9 shrink-0 rounded-full border border-ink-300 flex items-center justify-center text-brand-600">
                <i class="fas <?= $stat['icon'] ?> text-sm"></i>
            </span>
        </div>
        <div class="font-heading text-2xl text-ink-900"><?= $stat['value'] ?></div>
    </div>
    <?php endforeach; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Area Graph -->
    <div class="lg:col-span-2">
        <div class="card-boutique rounded-lg h-full flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-ink-300">
                <h2 class="font-heading text-lg text-ink-900">Biểu đồ thống kê doanh thu</h2>
                <span class="block w-8 h-px bg-brand-500 mt-2"></span>
            </div>
            <div class="p-6 grow">
                <canvas id="myChart"></canvas>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
                <?php $thang = 12;
                /** @var mixed $dau1 */
                $dau1  ?>
                const ctx = document.getElementById('myChart');

                new window.Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [
                            'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7',
                            'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
                        ],
                        datasets: [{
                            label: 'Tháng',
                            data: [
                                <?php for ($i = 1; $i <= $thang; $i++) {
                                    $a = \Codemoi\Model\Stats::forMonth($i);
                                    if ($i == $thang) {
                                        $dau1 = "";
                                    } else {
                                        $dau1 = ",";
                                    };
                                ?>
                                    <?= json_encode($a) ?> <?= $dau1 ?>
                                <?php
                                } ?>
                            ],
                            borderColor: '#b08d57',
                            backgroundColor: 'rgba(176, 141, 87, 0.12)',
                            fill: true,
                            tension: 0.25
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>

    <!-- Pie Graph -->
    <div>
        <div class="card-boutique rounded-lg h-full flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-ink-300">
                <h2 class="font-heading text-lg text-ink-900">Thống kê danh mục</h2>
                <span class="block w-8 h-px bg-brand-500 mt-2"></span>
            </div>
            <div class="p-6 grow">
                <canvas id="pieChart"></canvas>
            </div>

            <script type="text/javascript">
                <?php $all = \Codemoi\Model\Stats::byCategory();
                $dem = count($all);
                $i = 1; ?>

                var donutData = {
                    labels: [
                        <?php foreach ($all as $value) { ?> <?= json_encode($value['name_cate']) ?>,
                        <?php
                        } ?>
                    ],
                    datasets: [{
                        data: [
                            <?php foreach ($all as $valuee) {
                                if ($i == $dem) {
                                    $dau = "";
                                } else {
                                    $dau = ",";
                                }; ?>
                                <?= json_encode($valuee['sluong']) ?><?= $dau ?>
                            <?php $i++;
                            } ?>
                        ],
                        backgroundColor: ['#b08d57', '#a85c3f', '#c9a464', '#7a5c32', '#c97b5b', '#dcd2bf',
                            '#8c4530', '#9c8b72', '#4a3a20'
                        ],
                    }]
                }

                var pieChartCanvas = document.getElementById('pieChart').getContext('2d')
                var pieData = donutData;
                var pieOptions = {
                    maintainAspectRatio: false,
                    responsive: true,
                }

                new window.Chart(pieChartCanvas, {
                    type: 'pie',
                    data: pieData,
                    options: pieOptions
                })
            </script>
        </div>
    </div>
</div>

<?php include_once "footer.php" ?>
