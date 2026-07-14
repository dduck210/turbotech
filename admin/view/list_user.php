<?php include_once "header.php" ?>
<?php /** @var array $listuser */ ?>
<?php if (!empty($flash_success)): ?>
<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:<?= json_encode($flash_success) ?>,showConfirmButton:false,timer:3000}));</script>
<?php endif; ?>

<div class="mb-8 pb-5 border-b border-ink-300">
    <div class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-1">Quản lý</div>
    <h1 class="font-heading text-3xl text-ink-900">Khách Hàng</h1>
</div>

<div class="card-boutique rounded-lg overflow-hidden">
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr class="border-b-2 border-ink-300 text-ink-600 text-xs font-semibold tracking-widest uppercase">
                        <th class="px-4 py-3">Mã User</th>
                        <th class="px-4 py-3">Tên đăng nhập</th>
                        <th class="px-4 py-3">Họ tên</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3 text-center">Vai trò</th>
                        <th class="px-4 py-3 text-center">Thao tác</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-ink-200">
                    <?php foreach ($listuser as $user) : ?>
                    <tr class="hover:bg-ink-100/60 transition-colors">
                        <td class="px-4 py-4 text-ink-500 font-medium">#<?= e($user['id_user']) ?></td>
                        <td class="px-4 py-4 font-heading text-base text-ink-900"><?= e($user['user_name']) ?></td>
                        <td class="px-4 py-4"><?= e($user['full_name']) ?></td>
                        <td class="px-4 py-4"><?= e($user['email_user']) ?></td>
                        <td class="px-4 py-4 text-center"><?php if ($user['role'] == 1) {
                                                echo "<span class='inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-md bg-red-500/15 text-red-600'>Admin</span>";
                                            } else {
                                                echo "<span class='inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-md bg-emerald-500/15 text-emerald-700'>Thành Viên</span>";
                                            } ?></td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="./index.php?act=edit_user&id_user=<?= e($user['id_user']) ?>"
                                    class="p-2 text-yellow-600 bg-yellow-500/10 rounded-md hover:bg-yellow-500/15 transition-all active:scale-90"
                                    title="Sửa"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="./index.php?act=delete_usser&id_user=<?= e($user['id_user']) ?>&_token=<?= urlencode(\Codemoi\Core\Csrf::token()) ?>"
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
