<?php include_once "header.php" ?>
<?php /** @var array $one_loai */ ?>


<?php
if (is_array($one_loai)) {
  extract($one_loai);
}
?>
<div class="mb-8 flex items-center justify-between">
  <h1 class="text-3xl font-bold text-ink-800">Cập nhật loại máy tính</h1>
</div>

<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden mb-6">
  <div class="p-6">
    <div class="form-addcate">
      <form action="index.php?act=update_category" method="post" data-validate novalidate>
<?= \Codemoi\Core\Csrf::field() ?>
        <div class="mb-4">
          <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Mã
            loại</label>
          <input type="text"
            class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all mb-4"
            value="<?= e($id_cate) ?>" disabled>
        </div>
        <div class="mb-4">
          <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Tên
            loại</label>
          <input type="text" name="name_cate" data-rules="required|min:2|max:100"
            class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all mb-4"
            value="<?= e($name_cate) ?>">
        </div>
        <div class="wrap-btn mt-4">
          <input type="hidden" name="id_cate" value="<?= e($id_cate) ?>">
          <input type="submit" name="btn_update"
            class="bg-brand-600 hover:bg-brand-700 text-white font-medium rounded-lg px-4 py-2 transition-all active:scale-[0.97] inline-block"
            value="Cập nhật">
          <input type="reset"
            class="bg-red-500 hover:bg-red-600 text-white rounded-lg px-3 py-1.5 inline-block ml-2"
            value="Nhập lại">
        </div>
      </form>
    </div>
  </div>
</div>

<?php include_once "footer.php" ?>