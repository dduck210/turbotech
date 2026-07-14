<?php include_once "header.php" ?>
<?php /** @var array $ds_loai */ ?>
<?php if (!empty($flash_error)): ?>
<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:<?= json_encode($flash_error) ?>,showConfirmButton:false,timer:4000}));</script>
<?php endif; ?>
<?php if (!empty($flash_success)): ?>
<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:<?= json_encode($flash_success) ?>,showConfirmButton:false,timer:3000}));</script>
<?php endif; ?>

<div class="mb-8 pb-5 border-b border-ink-300 flex flex-wrap items-end justify-between gap-4">
    <div>
        <div class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-1">Quản lý</div>
        <h1 class="font-heading text-3xl text-ink-900">Danh Mục</h1>
    </div>
    <a href="index.php?act=add_category" class="btn-boutique inline-flex items-center gap-2 rounded-md px-5 py-2.5 font-medium">
        <i class="fas fa-plus"></i> Thêm danh mục
    </a>
</div>

<div class="card-boutique rounded-lg overflow-hidden">
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr class="border-b-2 border-ink-300 text-ink-600 text-xs font-semibold tracking-widest uppercase">
                        <th class="px-4 py-3">Mã loại</th>
                        <th class="px-4 py-3">Tên loại</th>
                        <th class="px-4 py-3 text-center">Thao Tác</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-ink-200">
                    <?php
                    foreach ($ds_loai as $loai) : ?>
                        <tr class="hover:bg-ink-100/60 transition-colors">
                            <td class="px-4 py-4 text-ink-500 font-medium">#<?= e($loai['id_cate']) ?></td>
                            <td class="px-4 py-4 font-heading text-base text-ink-900"><?= e($loai['name_cate']) ?></td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="index.php?act=edit_category&id_cate=<?= e($loai['id_cate']) ?>"
                                        class="p-2 text-yellow-600 bg-yellow-500/10 rounded-md hover:bg-yellow-500/15 transition-all active:scale-90"
                                        title="Sửa"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="index.php?act=delete_cate&id_cate=<?= e($loai['id_cate']) ?>&_token=<?= urlencode(\Codemoi\Core\Csrf::token()) ?>"
                                        class="p-2 text-red-600 bg-red-500/10 rounded-md hover:bg-red-500/15 transition-all active:scale-90"
                                        data-confirm="Bạn có chắc chắn muốn xóa không?" title="Xóa"><i
                                            class="fa-solid fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
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
            $('.dataTables_length select').addClass(
                'px-3 py-1 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 mx-2');
            $('.dataTables_filter input').addClass(
                'px-4 py-2 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 ml-2');
        }, 100);
    });
</script>

<?php include_once "footer.php" ?>
