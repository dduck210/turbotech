<?php include_once "header.php" ?>
<?php /** @var array $ds_loai */ ?>

<div class="mb-8 pb-5 border-b border-ink-300">
    <div class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-1">Quản lý</div>
    <h1 class="font-heading text-3xl text-ink-900">Thêm Sản Phẩm</h1>
</div>

<div class="card-boutique rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-ink-300">
        <h2 class="font-heading text-lg text-ink-900">Thông tin sản phẩm mới</h2>
        <span class="block w-8 h-px bg-brand-500 mt-2"></span>
    </div>
    <div class="p-6">
        <form action="index.php?act=add_product" method="post" enctype="multipart/form-data" data-validate novalidate>
<?= \Codemoi\Core\Csrf::field() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Mã sản phẩm -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Mã sản phẩm</label>
                    <input type="text" name="id_pro"
                        class="w-full px-4 py-2.5 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 focus:border-brand-500 transition-colors bg-ink-50 text-ink-800"
                        placeholder="Mã loại (auto)">
                </div>

                <!-- Tên sản phẩm -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Tên sản phẩm</label>
                    <input type="text" name="name_pro" data-rules="required|min:2|max:255"
                        class="w-full px-4 py-2.5 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 focus:border-brand-500 transition-colors bg-ink-50 text-ink-800"
                        placeholder="Tên sản phẩm">
                </div>

                <!-- Giá -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Giá (₫)</label>
                    <input type="number" name="price" data-rules="required|number|minval:1"
                        class="w-full px-4 py-2.5 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 focus:border-brand-500 transition-colors bg-ink-50 text-ink-800"
                        placeholder="Giá sản phẩm">
                </div>

                <!-- Giảm giá -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Giảm giá (%)</label>
                    <input type="number" name="discount" min="1" max="100" data-rules="number|minval:0"
                        class="w-full px-4 py-2.5 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 focus:border-brand-500 transition-colors bg-ink-50 text-ink-800"
                        placeholder="Nhập số % giảm giá">
                </div>

                <!-- Số lượng tồn kho -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Số lượng tồn kho</label>
                    <input type="number" name="stock" min="0" data-rules="required|number|minval:0"
                        class="w-full px-4 py-2.5 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 focus:border-brand-500 transition-colors bg-ink-50 text-ink-800"
                        placeholder="Số lượng" value="0">
                </div>

                <!-- Thông báo hết hàng -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Thông báo hết hàng</label>
                    <input type="text" name="stock_message" maxlength="255" data-rules="max:255"
                        class="w-full px-4 py-2.5 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 focus:border-brand-500 transition-colors bg-ink-50 text-ink-800"
                        placeholder="Để trống sẽ hiện: Hết hàng">
                </div>

                <!-- Loại máy tính -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Loại sản phẩm</label>
                    <select name="idcate" data-rules="minval:1" data-msg-minval="Vui lòng chọn loại sản phẩm"
                        class="w-full px-4 py-2.5 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 focus:border-brand-500 transition-colors bg-ink-50 text-ink-800">
                        <option value="0">Chọn loại</option>
                        <?php
                        foreach ($ds_loai as $loai) {
                            extract($loai);
                            echo '<option value="' . e($id_cate) . '">' . e($name_cate) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- Hình ảnh -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Hình ảnh</label>
                    <input type="file" name="img_pro" data-rules="required"
                        data-msg-required="Vui lòng chọn ảnh sản phẩm"
                        class="w-full px-4 py-2.5 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 focus:border-brand-500 transition-colors bg-ink-50 text-ink-800 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                </div>

                <!-- Mô tả ngắn -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Mô tả ngắn</label>
                    <input type="text" name="short_des" data-rules="required|min:5|max:500"
                        class="w-full px-4 py-2.5 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 focus:border-brand-500 transition-colors bg-ink-50 text-ink-800"
                        placeholder="Mô tả tóm tắt sản phẩm">
                </div>

                <!-- Mô tả chi tiết -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Mô tả chi tiết</label>
                    <textarea name="detail_des" id="detail_des" rows="5"
                        class="w-full px-4 py-2.5 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 focus:border-brand-500 transition-colors ckeditor"
                        placeholder="Mô tả đầy đủ chi tiết sản phẩm"></textarea>
                </div>
            </div>

            <div class="flex items-center gap-4 mt-8 pt-6 border-t border-ink-200">
                <input type="submit" name="btn_add"
                    class="btn-boutique px-6 py-2.5 rounded-md font-medium cursor-pointer"
                    value="Thêm sản phẩm">
                <a href="?act=list_product"
                    class="px-6 py-2.5 border border-ink-300 text-ink-700 font-medium rounded-md hover:bg-ink-200 transition-colors">Hủy
                    & Quay lại</a>
            </div>
        </form>

        <?php if (isset($noticepro) && $noticepro != ""): ?>
        <div class="mt-6 p-4 bg-green-500/10 border border-green-500/30 text-green-700 rounded-md flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <?= e($noticepro) ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once "footer.php" ?>
