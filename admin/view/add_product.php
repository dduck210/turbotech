<?php include_once "header.php" ?>
<?php /** @var array $ds_loai */ ?>

<div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-slate-800">Thêm Sản Phẩm</h1>
</div>

<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
        <h6 class="m-0 font-bold text-brand-600">Thông tin sản phẩm mới</h6>
    </div>
    <div class="p-6">
        <form action="index.php?act=add_product" method="post" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Mã sản phẩm -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Mã sản phẩm</label>
                    <input type="text" name="id_pro" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors" placeholder="Mã loại (auto)">
                </div>

                <!-- Tên sản phẩm -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tên sản phẩm</label>
                    <input type="text" name="name_pro" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors" placeholder="Tên sản phẩm">
                </div>

                <!-- Giá -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Giá (₫)</label>
                    <input type="number" name="price" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors" placeholder="Giá sản phẩm">
                </div>

                <!-- Giảm giá -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Giảm giá (%)</label>
                    <input type="number" name="discount" min="1" max="100" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors" placeholder="Nhập số % giảm giá">
                </div>

                <!-- Số lượng tồn kho -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Số lượng tồn kho</label>
                    <input type="number" name="stock" min="0" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors" placeholder="Số lượng" value="0" required>
                </div>

                <!-- Loại máy tính -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Loại sản phẩm</label>
                    <select name="idcate" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors bg-white">
                        <option value="0">Chọn loại</option>
                        <?php
                        foreach ($ds_loai as $loai) {
                            extract($loai);
                            echo '<option value=' . $id_cate . '>' . $name_cate . '</option>';
                        }
                        ?>
                    </select>
                </div>
                
                <!-- Hình ảnh -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Hình ảnh</label>
                    <input type="file" name="img_pro" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                </div>

                <!-- Mô tả ngắn -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Mô tả ngắn</label>
                    <input type="text" name="short_des" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors" placeholder="Mô tả tóm tắt sản phẩm">
                </div>

                <!-- Mô tả chi tiết -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Mô tả chi tiết</label>
                    <textarea name="detail_des" id="detail_des" rows="5" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors ckeditor" placeholder="Mô tả đầy đủ chi tiết sản phẩm"></textarea>
                </div>
            </div>

            <div class="flex items-center gap-4 mt-8 pt-6 border-t border-slate-100">
                <input type="submit" name="btn_add" class="px-6 py-2.5 bg-brand-600 text-white font-medium rounded-lg hover:bg-brand-700 focus:ring-4 focus:ring-blue-300 transition-all cursor-pointer" value="Thêm sản phẩm">
                <a href="?act=list_product" class="px-6 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-lg hover:bg-slate-200 focus:ring-4 focus:ring-slate-100 transition-all">Hủy & Quay lại</a>
            </div>
        </form>

        <?php if (isset($noticepro) && $noticepro != ""): ?>
            <div class="mt-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <?php echo $noticepro; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once "footer.php" ?>