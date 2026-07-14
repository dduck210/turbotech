<?php include_once "header.php" ?>
<?php /** @var array $one_loai */ ?>


<?php
if (is_array($one_loai)) {
  extract($one_loai);
}
?>
<div class="mb-8 pb-5 border-b border-ink-300">
  <div class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-1">Quản lý</div>
  <h1 class="font-heading text-3xl text-ink-900">Cập nhật loại máy tính</h1>
</div>

<div class="card-boutique rounded-lg overflow-hidden mb-6">
  <div class="p-6">
    <div class="form-addcate">
      <form action="index.php?act=update_category" method="post" data-validate novalidate>
<?= \Codemoi\Core\Csrf::field() ?>
        <div class="mb-4">
          <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Mã
            loại</label>
          <input type="text"
            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-100 text-ink-600 mb-4"
            value="<?= e($id_cate) ?>" disabled>
        </div>
        <div class="mb-4">
          <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Tên
            loại</label>
          <input type="text" name="name_cate" data-rules="required|min:2|max:100"
            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800 mb-4"
            value="<?= e($name_cate) ?>">
        </div>
        <div class="flex items-center gap-3 mt-4">
          <input type="hidden" name="id_cate" value="<?= e($id_cate) ?>">
          <input type="submit" name="btn_update"
            class="btn-boutique rounded-md px-6 py-2.5 font-medium cursor-pointer"
            value="Cập nhật">
          <input type="reset"
            class="border border-ink-300 text-ink-700 hover:bg-ink-200 rounded-md px-6 py-2.5 transition-colors cursor-pointer"
            value="Nhập lại">
        </div>
      </form>
    </div>
  </div>
</div>

<?php include_once "footer.php" ?>
