    <?php include_once "header.php" ?>
    <?php /** @var array $listbill */ ?>
    <?php if (!empty($flash_error)): ?>
    <script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:<?= json_encode($flash_error) ?>,showConfirmButton:false,timer:4000}));</script>
    <?php endif; ?>
    <?php if (!empty($flash_success)): ?>
    <script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:<?= json_encode($flash_success) ?>,showConfirmButton:false,timer:3000}));</script>
    <?php endif; ?>


    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-ink-800">Quản Lý Hóa Đơn</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden mb-6">
        <div class="p-6">
            <div class="mb-6 bg-ink-50 p-5 rounded-xl border border-ink-200">
                <div class="text-ink-700 font-semibold mb-4"><i class="fas fa-filter mr-2 text-brand-500"></i>Công cụ
                    tìm kiếm & Lọc đơn hàng</div>
                <form action="./index.php?act=list_bill" method="POST"
                    class="flex flex-col lg:flex-row gap-4 items-end">
<?= \Codemoi\Core\Csrf::field() ?>
                    <!-- Search Keyword -->
                    <div class="w-full lg:flex-1">
                        <label class="block text-xs font-medium text-ink-500 mb-1 uppercase tracking-wider">Từ khóa
                            tìm kiếm</label>
                        <input type="text" name="keyword"
                            value="<?= isset($keyword) ? htmlspecialchars($keyword) : '' ?>"
                            placeholder="Tên khách hàng, SĐT..."
                            class="w-full px-4 py-2 border border-ink-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors bg-white">
                    </div>

                    <!-- Status -->
                    <div class="w-full lg:w-48">
                        <label class="block text-xs font-medium text-ink-500 mb-1 uppercase tracking-wider">Trạng
                            thái</label>
                        <select name="status"
                            class="w-full px-4 py-2 border border-ink-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors bg-white">
                            <option value="-1" <?= (!isset($status) || $status == -1) ? 'selected' : '' ?>>Tất cả
                            </option>
                            <option value="0" <?= (isset($status) && $status == 0) ? 'selected' : '' ?>>Mới</option>
                            <option value="1" <?= (isset($status) && $status == 1) ? 'selected' : '' ?>>Đang xử lý
                            </option>
                            <option value="2" <?= (isset($status) && $status == 2) ? 'selected' : '' ?>>Đang giao
                            </option>
                            <option value="3" <?= (isset($status) && $status == 3) ? 'selected' : '' ?>>Đã giao</option>
                            <option value="4" <?= (isset($status) && $status == 4) ? 'selected' : '' ?>>Đã hủy</option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="w-full lg:w-auto flex flex-col sm:flex-row gap-3">
                        <div>
                            <label class="block text-xs font-medium text-ink-500 mb-1 uppercase tracking-wider">Từ
                                ngày</label>
                            <input type="date" name="from_date"
                                value="<?= isset($from_date) ? htmlspecialchars($from_date) : '' ?>"
                                class="w-full px-4 py-2 border border-ink-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors bg-white text-ink-700">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-ink-500 mb-1 uppercase tracking-wider">Đến
                                ngày</label>
                            <input type="date" name="to_date"
                                value="<?= isset($to_date) ? htmlspecialchars($to_date) : '' ?>"
                                class="w-full px-4 py-2 border border-ink-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors bg-white text-ink-700">
                        </div>
                    </div>

                    <!-- Filter Button -->
                    <div class="w-full lg:w-auto">
                        <button type="submit" name="btn_filter"
                            class="w-full px-6 py-2.5 bg-ink-800 text-white font-medium rounded-lg hover:bg-ink-900 focus:ring-4 focus:ring-ink-300 transition-all cursor-pointer whitespace-nowrap shadow-sm">
                            <i class="fas fa-search mr-2"></i>Tìm kiếm
                        </button>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="table1">
                    <thead>
                        <tr
                            class="bg-ink-50 border-y border-ink-100 text-ink-600 text-sm font-semibold tracking-wide uppercase">
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

                    <tbody class="divide-y divide-ink-100">
                        <?php $i = 1;
                        foreach ($listbill as $bill) :
                            extract($bill);
                        ?>
                            <tr class="hover:bg-ink-50 transition-colors">
                                <td class="px-4 py-4 text-ink-500 font-medium">#<?= e($i) ?></td>
                                <td class="px-4 py-4 font-medium text-ink-800"><?= e($bill['full_name']) ?></td>
                                <td class="px-4 py-4 font-semibold text-brand-600">
                                    <?= number_format($bill['total_amount']) ?> ₫</td>
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
                                <td class="px-4 py-4 text-ink-500"><?= e($bill['order_date']) ?></td>

                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <?php if ($bill['status'] == 0): ?>
                                            <a href="index.php?act=approve_bill&idbill=<?= e($bill['id_bill']) ?>&_token=<?= urlencode(\Codemoi\Core\Csrf::token()) ?>"
                                                class="p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-all active:scale-90"
                                                data-confirm="Bạn có chắc chắn muốn duyệt đơn hàng này?"
                                                title="Duyệt đơn hàng"><i class="fas fa-check"></i></a>
                                        <?php elseif ($bill['status'] == 1): ?>
                                            <a href="index.php?act=ship_bill&idbill=<?= e($bill['id_bill']) ?>&_token=<?= urlencode(\Codemoi\Core\Csrf::token()) ?>"
                                                class="p-2 text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-all active:scale-90"
                                                data-confirm="Bạn có chắc chắn muốn chuyển đơn hàng này sang giao hàng?"
                                                title="Giao hàng"><i class="fas fa-truck"></i></a>
                                        <?php endif; ?>

                                        <a href="index.php?act=edit_bill&idbill=<?= e($bill['id_bill']) ?>"
                                            class="p-2 text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 transition-all active:scale-90"
                                            title="Cập nhật chi tiết"><i class="fas fa-edit"></i></a>
                                        <a href="index.php?act=billdetail&idbill=<?= e($bill['id_bill']) ?>"
                                            class="p-2 text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-all active:scale-90"
                                            title="Xem chi tiết"><i class="fa-solid fa-circle-info"></i></a>

                                        <?php if ($bill['status'] == 0 || $bill['status'] == 1): ?>
                                            <a href="index.php?act=cancel_bill&idbill=<?= e($bill['id_bill']) ?>&_token=<?= urlencode(\Codemoi\Core\Csrf::token()) ?>"
                                                class="p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-all active:scale-90"
                                                data-confirm="Bạn có chắc chắn muốn hủy đơn hàng này?" title="Hủy đơn"><i
                                                    class="fas fa-times"></i></a>
                                        <?php endif; ?>
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
                $('.dataTables_length select').addClass(
                    'px-3 py-1 border border-ink-200 rounded-lg focus:ring-2 focus:ring-brand-500 mx-2');
                $('.dataTables_filter input').addClass(
                    'px-4 py-2 border border-ink-200 rounded-lg focus:ring-2 focus:ring-brand-500 ml-2');
            }, 100);
        });
    </script>

    <?php include_once "footer.php" ?>