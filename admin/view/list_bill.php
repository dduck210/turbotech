    <?php include_once "header.php" ?>
<?php /** @var array $listbill */ ?>


                <div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-slate-800">Quản Lý Hóa Đơn</h1>
</div>

<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden mb-6">
    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse" id="table1">
                                <thead>
                                    <tr class="bg-slate-50 border-y border-slate-100 text-slate-600 text-sm font-semibold tracking-wide uppercase">
                                        <th class="px-4 py-4">STT</th>
                                        <th scope="col" class="px-4 py-4">Người đặt</th>
                                        <th scope="col" class="px-4 py-4">Thành tiền</th>
                                        <th scope="col" class="px-4 py-4">Phương thức thanh toán</th>
                                        <th scope="col" class="px-4 py-4 text-center">Trạng thái thanh toán</th>
                                        <th scope="col" class="px-4 py-4 text-center">Trạng thái đơn hàng</th>
                                        <th scope="col" class="px-4 py-4">Ngày đặt</th>
                                        <th scope="col" class="px-4 py-4 text-center">Thao tác</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-100">
                                    <?php $i = 1;
                                    foreach ($listbill as $bill) :
                                        extract($bill);
                                        $user_detail = '' . $bill['user_name'] . '<br>' . $bill['full_name'] . '<br> ' . $bill['email'] . '<br> ' . $bill['address'] . '<br>0' . $bill['phone'] . '<br>
                                        </td>'
                                    ?>
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-4 py-4 text-slate-500 font-medium">#<?= $i ?></td>
                                            <td class="px-4 py-4 font-medium text-slate-800"><?= $bill['full_name'] ?></td>
                                            <td class="px-4 py-4 font-semibold text-brand-600"><?= number_format($bill['total_amount']) ?> ₫</td>
                                            <td class="px-4 py-4"><?php if ($bill['payment'] == 1) {
                                                    echo "Thanh toán khi nhận hàng";
                                                } else if ($bill['payment'] == 2) {
                                                    echo "Chuyển khoản ngân hàng";
                                                } else if ($bill['payment'] == 3) {
                                                    echo "Thanh toán Online";
                                                } else {
                                                    echo "Không tìm thấy phương thức thanh toán";
                                                }  ?></td>
                                            <td class="px-4 py-4 text-center"><?php if ($bill['status_pay'] == 0) {
                                                    echo "<span class='inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-700'>Chưa thanh toán</span>";
                                                } else if ($bill['status_pay'] == 1) {
                                                    echo "<span class='inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700'>Đã thanh toán</span>";
                                                } else {
                                                    echo "Không tìm thấy phương thức thanh toán";
                                                }  ?></td>
                                            <td class="px-4 py-4 text-center"><?php if ($bill['status'] == 0) {
                                                    echo "<span class='inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full bg-cyan-100 text-cyan-700'>Đơn hàng mới</span>";
                                                } else if ($bill['status'] == 1) {
                                                    echo "<span class='inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-700'>Đang xử lý</span>";
                                                } else if ($bill['status'] == 2) {
                                                    echo "<span class='inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full bg-brand-100 text-brand-700'>Đang giao hàng</span>";
                                                } else if ($bill['status'] == 3) {
                                                    echo "<span class='inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700'>Đã giao hàng</span>";
                                                } elseif ($bill['status'] == 4) {
                                                    echo "<span class='inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full bg-red-100 text-red-600'>Đã hủy</span>";
                                                } else {
                                                    echo "Lỗi trạng thái";
                                                } ?></td>
                                            <td class="px-4 py-4 text-slate-500"><?= $bill['order_date'] ?></td>

                                            <td class="px-4 py-4 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="index.php?act=edit_bill&idbill=<?= $bill['id_bill'] ?>" class="p-2 text-yellow-600 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors" title="Sửa"><i class="fas fa-edit"></i></a>
                                                    <a href="index.php?act=billdetail&idbill=<?= $bill['id_bill'] ?>" class="p-2 text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors" title="Chi tiết"><i class="fa-solid fa-circle-info"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php $i++;
                                    endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        <script>
            $(document).ready(function() {
                $('#table1').DataTable({
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
