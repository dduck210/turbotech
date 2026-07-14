<?php include_once "header.php" ?>

<div class="mb-8 pb-5 border-b border-ink-300">
    <div class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-1">Quản lý</div>
    <h1 class="font-heading text-3xl text-ink-900">Thêm Loại</h1>
</div>

<div class="card-boutique rounded-lg overflow-hidden mb-6">
    <div class="p-6">
        <div class="form-addcate">
            <form action="./index.php?act=add_category" method="post" data-validate novalidate>
<?= \Codemoi\Core\Csrf::field() ?>
                <div class="mb-4">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Mã
                        loại</label>
                    <input type="text"
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-100 text-ink-600 mb-4"
                        placeholder="Mã loại (auto increase)" disabled>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Tên
                        loại</label>
                    <input type="text" name="name_cate" data-rules="required|min:2|max:100"
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800 mb-4"
                        placeholder="Tên loại máy tính">
                </div>
                <div class="flex items-center gap-3 mt-4">
                    <input type="submit" name="btn_add"
                        class="btn-boutique rounded-md px-6 py-2.5 font-medium cursor-pointer"
                        value="Thêm">
                    <a href="index.php?act=list_category"
                        class="border border-ink-300 text-ink-700 hover:bg-ink-200 rounded-md px-6 py-2.5 transition-colors">Quay Lại
                        danh sách</a>
                </div>
            </form>
            <h3 class="text-emerald-600 text-sm mt-4 font-semibold">
                <?php
        if (isset($notice) && $notice != "") {
          echo e($notice);
        }
        ?>
            </h3>
        </div>
    </div>
</div>

<?php include_once "footer.php" ?>
