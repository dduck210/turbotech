<?php include_once "header.php" ?>
<?php /** @var array $ds_loai */
/** @var array $listsp */ ?>
<?php if (!empty($flash_error)): ?>
<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:<?= json_encode($flash_error) ?>,showConfirmButton:false,timer:4000}));</script>
<?php endif; ?>
<?php if (!empty($flash_success)): ?>
<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:<?= json_encode($flash_success) ?>,showConfirmButton:false,timer:3000}));</script>
<?php endif; ?>

<div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-slate-800">Quản Lý Sản Phẩm</h1>
    <a href="index.php?act=add_product"
        class="px-5 py-2.5 bg-brand-600 text-white font-medium rounded-lg hover:bg-brand-700 focus:ring-4 focus:ring-blue-300 transition-all flex items-center gap-2">
        <i class="fas fa-plus"></i> Thêm sản phẩm
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
    <div class="p-6">
        <div class="mb-6 bg-slate-50 p-4 rounded-lg border border-slate-100 flex items-center justify-between">
            <div class="text-slate-600 font-medium">Lọc sản phẩm</div>
            <form action="./index.php?act=list_product" method="POST" class="flex gap-3">
<?= \Codemoi\Core\Csrf::field() ?>
                <select name="idcate"
                    class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors bg-white min-w-[200px]">
                    <option value="0">Tất cả danh mục</option>
                    <?php
                    foreach ($ds_loai as $loai) {
                        extract($loai);
                        if ($idcate == $id_cate) {
                            echo '<option value="' . e($id_cate) . '" selected>' . e($name_cate) . '</option>';
                        } else {
                            echo '<option value="' . e($id_cate) . '">' . e($name_cate) . '</option>';
                        }
                    }
                    ?>
                </select>
                <input type="submit" value="Lọc" name="btn_filter"
                    class="px-5 py-2 bg-slate-800 text-white font-medium rounded-lg hover:bg-slate-900 focus:ring-4 focus:ring-slate-300 transition-all active:scale-[0.97] cursor-pointer">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="example">
                <thead>
                    <tr
                        class="bg-slate-50 border-y border-slate-100 text-slate-600 text-sm font-semibold tracking-wide uppercase">
                        <th class="px-4 py-4 w-16 text-center">ID</th>
                        <th class="px-4 py-4">Hình ảnh</th>
                        <th class="px-4 py-4 min-w-[200px]">Tên sản phẩm</th>
                        <th class="px-4 py-4">Giá bán</th>
                        <th class="px-4 py-4 text-center">Giảm giá</th>
                        <th class="px-4 py-4 text-center">Tồn kho</th>
                        <th class="px-4 py-4 text-center"><i class="far fa-eye text-slate-400" title="Lượt xem"></i>
                        </th>
                        <th class="px-4 py-4 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($listpro as $pro) : ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-4 text-center text-slate-500 font-medium">#<?= e($pro['id_pro']) ?></td>
                            <td class="px-4 py-4">
                                <div
                                    class="w-14 h-14 rounded-lg border border-slate-200 overflow-hidden bg-white flex items-center justify-center">
                                    <img src="./uploads/<?= e($pro['img_pro']) ?>"
                                        alt="<?= htmlspecialchars($pro['name_pro']) ?>"
                                        class="max-w-full max-h-full object-cover">
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-medium text-slate-800 line-clamp-2"><?= e($pro['name_pro']) ?></div>
                                <div class="text-xs text-slate-500 mt-1 line-clamp-1"
                                    title="<?= htmlspecialchars($pro['short_des']) ?>"><?= e($pro['short_des']) ?></div>
                            </td>
                            <td class="px-4 py-4 font-semibold text-brand-600">
                                <?= number_format($pro['price']) ?> ₫
                            </td>
                            <td class="px-4 py-4 text-center">
                                <?php if ($pro['discount'] > 0): ?>
                                    <span
                                        class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full bg-red-100 text-red-600">
                                        -<?= $pro['discount'] ?>%
                                    </span>
                                <?php else: ?>
                                    <span class="text-slate-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <?php if ((int) $pro['stock'] <= 0) : ?>
                                    <span
                                        class="inline-flex items-center justify-center px-2.5 py-1 border border-red-200 text-xs font-medium rounded-full bg-red-50 text-red-600">
                                        Hết hàng
                                    </span>
                                <?php else : ?>
                                    <span
                                        class="inline-flex items-center justify-center px-2.5 py-1 border border-green-200 text-xs font-medium rounded-full bg-green-50 text-green-700">
                                        <?= $pro['stock'] ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 text-center text-slate-500">
                                <?= number_format($pro['view']) ?>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="./index.php?act=edit_product&id_pro=<?= e($pro['id_pro']) ?>"
                                        class="p-2 text-yellow-600 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-all active:scale-90"
                                        title="Sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="./index.php?act=delete_product&id_pro=<?= e($pro['id_pro']) ?>&_token=<?= urlencode(\Codemoi\Core\Csrf::token()) ?>"
                                        class="p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-all active:scale-90"
                                        data-confirm="Bạn có chắc chắn muốn xóa không?" title="Xóa">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/vi.json"
            },
            "dom": '<"flex flex-col md:flex-row justify-between items-center mb-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-4"ip>',
        });

        // Add Tailwind classes to DataTable elements
        setTimeout(function() {
            $('.dataTables_length select').addClass(
                'px-3 py-1 border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500 mx-2');
            $('.dataTables_filter input').addClass(
                'px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500 ml-2');
        }, 100);
    });
</script>

<?php include_once "footer.php" ?>