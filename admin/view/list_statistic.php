<?php include_once "header.php" ?>
<?php /** @var array $liststatis */ ?>


            <div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-slate-800">Thống Kê Sản Phẩm Theo Loại</h1>
</div>

<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden mb-6">
    <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr class="bg-slate-50 border-y border-slate-100 text-slate-600 text-sm font-semibold tracking-wide uppercase">
                                    <th class="px-4 py-4">Mã loại</th>
                                    <th class="px-4 py-4">Tên loại</th>
                                    <th class="px-4 py-4">Số lượng sản phẩm</th>
                                    <th class="px-4 py-4">Giá thấp nhất</th>
                                    <th class="px-4 py-4">Giá cao nhất</th>
                                    <th class="px-4 py-4">Giá trung bình</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                <?php foreach ($liststatis as $statis) : extract($statis); ?>
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-4 text-slate-500 font-medium">#<?= $idcate ?></td>
                                        <td class="px-4 py-4 font-medium text-slate-800"><?= $namecate ?></td>
                                        <td class="px-4 py-4"><?= $pro_quantity ?></td>
                                        <td class="px-4 py-4"><?= number_format($min_price) ?> ₫</td>
                                        <td class="px-4 py-4"><?= number_format($max_price) ?> ₫</td>
                                        <td class="px-4 py-4 font-semibold text-brand-600"><?= number_format($avg_price) ?> ₫</td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>


    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/vi.json"
                },
                "dom": '<"flex flex-col md:flex-row justify-between items-center mb-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-4"ip>',
            });

            setTimeout(function() {
                $('.dataTables_length select').addClass('px-3 py-1 border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500 mx-2');
                $('.dataTables_filter input').addClass('px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500 ml-2');
            }, 100);
        });
    </script>

    <?php include_once "footer.php" ?>
