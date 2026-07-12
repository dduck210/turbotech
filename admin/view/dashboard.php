<?php include_once "header.php" ?>
<?php if (!empty($flash_success)): ?>
<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:<?= json_encode($flash_success) ?>,showConfirmButton:false,timer:3000}));</script>
<?php endif; ?>
<?php $listuser = \Codemoi\Model\User::allAdmin();
$listcmt = loadall_cmt();
$listbill = \Codemoi\Model\Order::allAdmin();
$listpro = \Codemoi\Model\Product::allAdmin();
$ds_loai = \Codemoi\Model\Category::all(); ?>

<div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-slate-800">Bảng điều khiển</h1>
</div>

<!-- Content Row -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

    <!-- Earnings (Daily) -->
    <div
        class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border-l-4 border-brand-500 flex items-center justify-between">
        <div>
            <div class="text-xs font-bold text-brand-500 uppercase tracking-wider mb-1">Tổng doanh thu(Ngày)</div>
            <div class="text-2xl font-bold text-slate-800"><?= number_format(ngay()) ?> đ</div>
        </div>
        <div class="text-slate-300">
            <i class="fas fa-dollar-sign fa-2x"></i>
        </div>
    </div>

    <!-- Earnings (Weekly) -->
    <div
        class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border-l-4 border-yellow-500 flex items-center justify-between">
        <div>
            <div class="text-xs font-bold text-yellow-500 uppercase tracking-wider mb-1">Tổng doanh thu(Tuần)</div>
            <div class="text-2xl font-bold text-slate-800"><?= number_format(tuan()) ?> đ</div>
        </div>
        <div class="text-slate-300">
            <i class="fas fa-dollar-sign fa-2x"></i>
        </div>
    </div>

    <!-- Earnings (Monthly) -->
    <div
        class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border-l-4 border-green-500 flex items-center justify-between">
        <div>
            <div class="text-xs font-bold text-green-500 uppercase tracking-wider mb-1">Tổng doanh thu(Tháng)</div>
            <div class="text-2xl font-bold text-slate-800"><?= number_format(thang()) ?> đ</div>
        </div>
        <div class="text-slate-300">
            <i class="fas fa-dollar-sign fa-2x"></i>
        </div>
    </div>

    <!-- Total Orders -->
    <div
        class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border-l-4 border-brand-500 flex items-center justify-between">
        <div>
            <div class="text-xs font-bold text-brand-500 uppercase tracking-wider mb-1">Tổng đơn</div>
            <div class="text-2xl font-bold text-slate-800"><?= e(count($listbill)) ?></div>
        </div>
        <div class="text-slate-300">
            <i class="fas fa-clipboard-list fa-2x"></i>
        </div>
    </div>

    <!-- Total Customers -->
    <div
        class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border-l-4 border-yellow-500 flex items-center justify-between">
        <div>
            <div class="text-xs font-bold text-yellow-500 uppercase tracking-wider mb-1">Tổng khách hàng</div>
            <div class="text-2xl font-bold text-slate-800"><?= e(count($listuser)) ?></div>
        </div>
        <div class="text-slate-300">
            <i class="fas fa-solid fa-user fa-2x"></i>
        </div>
    </div>

    <!-- Total Products -->
    <div
        class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border-l-4 border-green-500 flex items-center justify-between">
        <div>
            <div class="text-xs font-bold text-green-500 uppercase tracking-wider mb-1">Tổng sản phẩm</div>
            <div class="text-2xl font-bold text-slate-800"><?= e(count($listpro)) ?></div>
        </div>
        <div class="text-slate-300">
            <i class="fas fa-brands fa-product-hunt fa-2x"></i>
        </div>
    </div>

    <!-- Total Categories -->
    <div
        class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border-l-4 border-indigo-500 flex items-center justify-between">
        <div>
            <div class="text-xs font-bold text-indigo-500 uppercase tracking-wider mb-1">Tổng loại sản phẩm</div>
            <div class="text-2xl font-bold text-slate-800"><?= e(count($ds_loai)) ?></div>
        </div>
        <div class="text-slate-300">
            <i class="fas fa-solid fa-weight-hanging fa-2x"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Area Graph -->
    <div class="lg:col-span-2">
        <div
            class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 h-full flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h6 class="m-0 font-bold text-brand-600">Biểu đồ thống kê Doanh thu</h6>
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
                                    $a = tungthang($i);
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
                            fill: false,
                            tension: 0.1
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
        <div
            class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 h-full flex flex-col overflow-hidden border-t-4 border-red-500">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h6 class="m-0 font-bold text-red-500">Thống kê danh mục</h6>
            </div>
            <div class="p-6 grow">
                <canvas id="pieChart"></canvas>
            </div>

            <script type="text/javascript">
                <?php $all = thonngke();
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
                        backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8db2', '#d2d6de',
                            '#6610f2', '#3d9970', '#001f3f'
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