<?php include_once "header.php" ?>
<?php /** @var array $question */ ?>

<div class="mb-8 pb-5 border-b border-ink-300">
    <div class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-1">Phản hồi</div>
    <h1 class="font-heading text-3xl text-ink-900">Trả lời câu hỏi</h1>
</div>

<div class="card-boutique rounded-lg overflow-hidden mb-6">
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="mb-4">
                <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Họ và tên</label>
                <input type="text" class="w-full rounded-md border border-ink-300 px-4 py-2.5 bg-ink-100 text-ink-600" value="<?= e($question['name']) ?>" disabled>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Email</label>
                <input type="text" class="w-full rounded-md border border-ink-300 px-4 py-2.5 bg-ink-100 text-ink-600" value="<?= e($question['email']) ?>" disabled>
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Số điện thoại</label>
            <input type="text" class="w-full rounded-md border border-ink-300 px-4 py-2.5 bg-ink-100 text-ink-600" value="<?= e($question['phone']) ?>" disabled>
        </div>
        <div class="mb-4">
            <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Nội dung câu hỏi</label>
            <textarea class="w-full rounded-md border border-ink-300 px-4 py-2.5 bg-ink-100 text-ink-600" rows="3" disabled><?= e($question['contennt']) ?></textarea>
        </div>

        <?php if (!empty($question['reply'])): ?>
            <div class="mb-4 rounded-md border border-emerald-500/30 bg-emerald-500/10 p-3 text-sm text-emerald-800">
                <p class="font-semibold">Đã trả lời lúc <?= e($question['replied_at']) ?>:</p>
                <p class="mt-1 whitespace-pre-line"><?= e($question['reply']) ?></p>
            </div>
        <?php endif; ?>

        <form action="./index.php?act=send_reply" method="post">
            <?= \Codemoi\Core\Csrf::field() ?>
            <input type="hidden" name="id_ques" value="<?= e($question['id_ques']) ?>">
            <label for="reply" class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">
                <?= !empty($question['reply']) ? 'Gửi trả lời khác' : 'Nội dung trả lời' ?>
            </label>
            <textarea id="reply" name="reply" required rows="5"
                class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800"
                placeholder="Nhập nội dung trả lời, sẽ được gửi qua email cho khách..."></textarea>

            <div class="flex items-center gap-3 mt-6 pt-6 border-t border-ink-200">
                <input type="submit" name="btn_reply"
                    class="btn-boutique rounded-md px-6 py-2.5 font-medium cursor-pointer"
                    value="Gửi trả lời qua email">
                <a href="./index.php?act=list_ques" class="border border-ink-300 text-ink-700 hover:bg-ink-200 rounded-md px-6 py-2.5 transition-colors">Quay lại</a>
            </div>
        </form>
    </div>
</div>

<?php include_once "footer.php" ?>
