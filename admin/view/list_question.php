<?php include_once "header.php" ?>
<?php /** @var array $listques */ ?>

<div class="mb-8 pb-5 border-b border-ink-300">
    <div class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-1">Phản hồi</div>
    <h1 class="font-heading text-3xl text-ink-900">Hỏi Đáp</h1>
</div>

<div class="card-boutique rounded-lg overflow-hidden">
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr class="border-b-2 border-ink-300 text-ink-600 text-xs font-semibold tracking-widest uppercase">
                        <th class="px-4 py-3">Mã hỏi đáp</th>
                        <th class="px-4 py-3">Họ và tên</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Số điện thoại</th>
                        <th class="px-4 py-3">Nội dung</th>
                        <th class="px-4 py-3 text-center">Trạng thái</th>
                        <th class="px-4 py-3 text-center">Thao tác</th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-ink-200">
                    <?php
                                foreach ($listques as $ques) : extract($ques); ?>
                    <tr class="hover:bg-ink-100/60 transition-colors">
                        <td class="px-4 py-4 text-ink-500 font-medium">#<?= e($id_ques) ?></td>
                        <td class="px-4 py-4 font-medium text-ink-800"><?= e($name) ?></td>
                        <td class="px-4 py-4"><?= e($email) ?></td>
                        <td class="px-4 py-4"><?= e($phone) ?></td>
                        <td class="px-4 py-4"><?= e($contennt) ?></td>
                        <td class="px-4 py-4 text-center">
                            <?php if (!empty($reply)): ?>
                                <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-md bg-emerald-500/15 text-emerald-700">Đã trả lời</span>
                            <?php else: ?>
                                <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-md bg-amber-500/15 text-amber-700">Chưa trả lời</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="./index.php?act=reply_question&id_ques=<?= e($ques['id_ques']) ?>"
                                    class="p-2 text-blue-600 bg-blue-500/10 rounded-md hover:bg-blue-500/15 transition-all active:scale-90"
                                    title="Trả lời"><i class="fa-solid fa-reply"></i></a>
                                <a href="./index.php?act=delete_ques&id_ques=<?= e($ques['id_ques']) ?>&_token=<?= urlencode(\Codemoi\Core\Csrf::token()) ?>"
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
                'px-3 py-1 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 mx-2'
                );
            $('.dataTables_filter input').addClass(
                'px-4 py-2 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 ml-2'
                );
        }, 100);
    });
    </script>
</div>

<?php include_once "footer.php" ?>
