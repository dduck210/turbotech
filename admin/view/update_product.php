<?php include_once "header.php" ?>


<div class="bg-ink-800/70 backdrop-blur-xl rounded-xl shadow-sm border border-ink-200 overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-ink-200 bg-ink-50/50 font-semibold text-ink-800">
        <h6 class="m-0">Cập nhật sản phẩm</h6>
    </div>
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Mã sản
                            phẩm</label>
                        <input type="text" name="id_pro"
                            class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all"
                            value="<?= e($id_pro) ?>" disabled>
                    </div>
                    <div class="mb-4">
                        <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Tên sản
                            phẩm</label>
                        <input type="text" name="name_pro" data-rules="required|min:2|max:255"
                            class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all"
                            placeholder="Tên sản phẩm" value="<?= e($name_pro) ?>">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="formGroupExampleInput"
                            class="block text-sm font-medium text-ink-700 mb-1">Giá</label>
                        <input type="text" name="price" data-rules="required|number|minval:1"
                            class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all"
                            placeholder="Giá sản phẩm" value="<?= e($price) ?>">
                    </div>
                    <div class="mb-4">
                        <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Giảm
                            giá</label>
                        <input type="text" name="discount" data-rules="number|minval:0"
                            class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all"
                            placeholder="Nhập số % mà sản phẩm được giảm giá" value="<?= e($discount) ?>">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Số
                            lượng tồn kho</label>
                        <input type="number" name="stock" min="0" data-rules="required|number|minval:0"
                            class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all"
                            placeholder="Nhập số lượng hàng tồn kho" value="<?= e($stock) ?>">
                    </div>
                    <div class="mb-4">
                        <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Thông
                            báo hết hàng</label>
                        <input type="text" name="stock_message" maxlength="255" data-rules="max:255"
                            class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all"
                            placeholder="Để trống sẽ hiện: Hết hàng"
                            value="<?= htmlspecialchars($stock_message ?? '') ?>">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="exampleFormControlSelect1"
                            class="block text-sm font-medium text-ink-700 mb-1">Loại máy tính</label>
                        <select
                            class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all bg-ink-800/70 backdrop-blur-xl"
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
                        <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Hình
                            ảnh (<?= e($img_pro) ?>)</label>
                        <input type="file" name="img_pro"
                            class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all bg-ink-800/70 backdrop-blur-xl">
                    </div>
                </div>


                <div class="mb-4 mt-3">
                    <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Mô tả
                        ngắn</label>
                    <input type="text" name="short_des" data-rules="required|min:5|max:500"
                        class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all"
                        placeholder="Mô tả tóm tắt sản phẩm" value="<?= e($short_des) ?>">
                </div>
                <div class="mb-4 mt-3">
                    <label for="comment" class="block text-sm font-medium text-ink-700 mb-1">Mô tả chi tiết</label>
                    <textarea
                        class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all ckeditor"
                        rows="5" name="detail_des" placeholder="Mô tả đầy đủ chi tiết sản phẩm"
                        id="detail_des"><?php /* Not escaped: CKEditor round-trips its own HTML here. */ ?><?= $detail_des ?></textarea>
                </div>
                <div class="wrap-btn mt-4">
                    <input type="hidden" name="id_pro" value="<?= e($id_pro) ?>">
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
<!-- /.container-fluid -->
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>

<?php include_once "footer.php" ?>