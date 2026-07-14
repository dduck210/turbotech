<?php include_once "header.php" ?>

<div class="mb-8 pb-5 border-b border-ink-300">
    <div class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-1">Quản lý</div>
    <h1 class="font-heading text-3xl text-ink-900">Cập nhật sản phẩm</h1>
</div>

<div class="card-boutique rounded-lg overflow-hidden mb-6">
    <?php
    $pro = $pro ?? [];
    if (is_array($pro)) {
        extract($pro);
    }
    $img_path = './uploads/' . $img_pro;
    if (is_file($img_path)) {
        $img_pro = $img_path;
    } else {
        $img_pro = 'No photo !';
    }
    ?>
    <div class="p-6">
        <div class="form-addcate">
            <form action="./index.php?act=update_product" method="post" enctype="multipart/form-data" data-validate
                novalidate>
<?= \Codemoi\Core\Csrf::field() ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Mã sản
                            phẩm</label>
                        <input type="text" name="id_pro"
                            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-100 text-ink-600"
                            value="<?= e($id_pro) ?>" disabled>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Tên sản
                            phẩm</label>
                        <input type="text" name="name_pro" data-rules="required|min:2|max:255"
                            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800"
                            placeholder="Tên sản phẩm" value="<?= e($name_pro) ?>">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Giá</label>
                        <input type="text" name="price" data-rules="required|number|minval:1"
                            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800"
                            placeholder="Giá sản phẩm" value="<?= e($price) ?>">
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Giảm
                            giá</label>
                        <input type="text" name="discount" data-rules="number|minval:0"
                            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800"
                            placeholder="Nhập số % mà sản phẩm được giảm giá" value="<?= e($discount) ?>">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Số
                            lượng tồn kho</label>
                        <input type="number" name="stock" min="0" data-rules="required|number|minval:0"
                            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800"
                            placeholder="Nhập số lượng hàng tồn kho" value="<?= e($stock) ?>">
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Thông
                            báo hết hàng</label>
                        <input type="text" name="stock_message" maxlength="255" data-rules="max:255"
                            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800"
                            placeholder="Để trống sẽ hiện: Hết hàng"
                            value="<?= htmlspecialchars($stock_message ?? '') ?>">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Loại máy tính</label>
                        <select
                            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800"
                            name="idcate" id="exampleFormControlSelect1" data-rules="minval:1"
                            data-msg-minval="Vui lòng chọn loại sản phẩm">
                            <option value="0">Chọn loại</option>
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
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Hình
                            ảnh (<?= e($img_pro) ?>)</label>
                        <input type="file" name="img_pro"
                            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800">
                    </div>
                </div>


                <div class="mb-4 mt-3">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Mô tả
                        ngắn</label>
                    <input type="text" name="short_des" data-rules="required|min:5|max:500"
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800"
                        placeholder="Mô tả tóm tắt sản phẩm" value="<?= e($short_des) ?>">
                </div>
                <div class="mb-4 mt-3">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Mô tả chi tiết</label>
                    <textarea
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all ckeditor"
                        rows="5" name="detail_des" placeholder="Mô tả đầy đủ chi tiết sản phẩm"
                        id="detail_des"><?php /* Not escaped: CKEditor round-trips its own HTML here. */ ?><?= $detail_des ?></textarea>
                </div>
                <div class="flex items-center gap-3 mt-6 pt-6 border-t border-ink-200">
                    <input type="hidden" name="id_pro" value="<?= e($id_pro) ?>">
                    <input type="submit" name="btn_update"
                        class="btn-boutique px-6 py-2.5 rounded-md font-medium cursor-pointer"
                        value="Cập nhật">
                    <input type="reset"
                        class="border border-ink-300 text-ink-700 hover:bg-ink-200 rounded-md px-6 py-2.5 transition-colors cursor-pointer"
                        value="Nhập lại">
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>

<?php include_once "footer.php" ?>
