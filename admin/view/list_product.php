<?php include_once "header.php" ?>
<?php /** @var array $ds_loai */
/** @var array $listsp */ ?>
<?php if (!empty($flash_error)): ?>
<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:<?= json_encode($flash_error) ?>,showConfirmButton:false,timer:4000}));</script>
<?php endif; ?>
<?php if (!empty($flash_success)): ?>
<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:<?= json_encode($flash_success) ?>,showConfirmButton:false,timer:3000}));</script>
<?php endif; ?>

<div class="mb-8 pb-5 border-b border-ink-300 flex flex-wrap items-end justify-between gap-4">
    <div>
        <div class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-1">Quản lý</div>
        <h1 class="font-heading text-3xl text-ink-900">Sản Phẩm</h1>
    </div>
    <a href="index.php?act=add_product" class="btn-boutique inline-flex items-center gap-2 rounded-md px-5 py-2.5 font-medium">
        <i class="fas fa-plus"></i> Thêm sản phẩm
    </a>
</div>

<div class="card-boutique rounded-lg overflow-hidden">
    <div class="p-6">
        <form action="./index.php?act=list_product" method="POST"
            class="mb-6 flex flex-wrap items-end gap-3 border-b border-ink-200 pb-6">
<?= \Codemoi\Core\Csrf::field() ?>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Lọc theo danh mục</label>
                <select name="idcate"
                    class="px-4 py-2 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 focus:border-brand-500 transition-colors bg-ink-50 text-ink-800 min-w-[220px]">
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
            </div>
            <input type="submit" value="Lọc" name="btn_filter"
                class="px-5 py-2 border border-ink-300 text-ink-700 font-medium rounded-md hover:bg-ink-200 transition-colors cursor-pointer">
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="example">
                <thead>
                    <tr class="border-b-2 border-ink-300 text-ink-600 text-xs font-semibold tracking-widest uppercase">
                        <th class="px-4 py-3 w-16 text-center">ID</th>
                        <th class="px-4 py-3">Hình ảnh</th>
                        <th class="px-4 py-3 min-w-[200px]">Tên sản phẩm</th>
                        <th class="px-4 py-3">Giá bán</th>
                        <th class="px-4 py-3 text-center">Giảm giá</th>
                        <th class="px-4 py-3 text-center">Tồn kho</th>
                        <th class="px-4 py-3 text-center"><i class="far fa-eye text-ink-400" title="Lượt xem"></i>
                        </th>
                        <th class="px-4 py-3 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-200">
                    <?php foreach ($listpro as $pro) : ?>
                        <tr class="hover:bg-ink-100/60 transition-colors">
                            <td class="px-4 py-4 text-center text-ink-500 font-medium">#<?= e($pro['id_pro']) ?></td>
                            <td class="px-4 py-4">
                                <div
                                    class="w-14 h-14 rounded-md border border-ink-300 overflow-hidden bg-ink-50 flex items-center justify-center">
                                    <img src="./uploads/<?= e($pro['img_pro']) ?>"
                                        alt="<?= e($pro['name_pro']) ?>"
                                        class="max-w-full max-h-full object-cover">
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-heading text-base text-ink-900 line-clamp-2"><?= e($pro['name_pro']) ?></div>
                                <div class="text-xs text-ink-500 mt-1 line-clamp-1"
                                    title="<?= e($pro['short_des']) ?>"><?= e($pro['short_des']) ?></div>
                            </td>
                            <td class="px-4 py-4 font-semibold text-brand-600">
                                <?= number_format($pro['price']) ?> ₫
                            </td>
                            <td class="px-4 py-4 text-center">
                                <?php if ($pro['discount'] > 0): ?>
                                    <span
                                        class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-md bg-red-500/15 text-red-600">
                                        -<?= e($pro['discount']) ?>%
                                    </span>
                                <?php else: ?>
                                    <span class="text-ink-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <?php if ((int) $pro['stock'] <= 0) : ?>
                                    <span
                                        class="inline-flex items-center justify-center px-2.5 py-1 border border-red-500/30 text-xs font-medium rounded-md bg-red-500/10 text-red-600">
                                        Hết hàng
                                    </span>
                                <?php else : ?>
                                    <span
                                        class="inline-flex items-center justify-center px-2.5 py-1 border border-green-500/30 text-xs font-medium rounded-md bg-green-500/10 text-green-700">
                                        <?= e($pro['stock']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 text-center text-ink-500">
                                <?= number_format($pro['view']) ?>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="./index.php?act=edit_product&id_pro=<?= e($pro['id_pro']) ?>"
                                        class="p-2 text-yellow-600 bg-yellow-500/10 rounded-md hover:bg-yellow-500/15 transition-all active:scale-90"
                                        title="Sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="./index.php?act=delete_product&id_pro=<?= e($pro['id_pro']) ?>&_token=<?= urlencode(\Codemoi\Core\Csrf::token()) ?>"
                                        class="p-2 text-red-600 bg-red-500/10 rounded-md hover:bg-red-500/15 transition-all active:scale-90"
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
                'px-3 py-1 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 mx-2');
            $('.dataTables_filter input').addClass(
                'px-4 py-2 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 ml-2');
        }, 100);
    });
</script>

<?php include_once "footer.php" ?>
