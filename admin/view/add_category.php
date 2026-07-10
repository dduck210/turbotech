<?php include_once "header.php" ?>


      <div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-slate-800">Thêm Loại</h1>
</div>

<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden mb-6">
    <div class="p-6">
          <div class="form-addcate">
            <form action="./index.php?act=add_category" method="post">
              <div class="mb-4">
                <label for="formGroupExampleInput" class="block text-sm font-medium text-slate-700 mb-1">Mã loại</label>
                <input type="text" class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all mb-4" placeholder="Mã loại (auto increase)" disabled>
              </div>
              <div class="mb-4">
                <label for="formGroupExampleInput" class="block text-sm font-medium text-slate-700 mb-1">Tên loại</label>
                <input type="text" name="name_cate" class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all mb-4" placeholder="Tên loại máy tính" required>
              </div>
              <div class="wrap-btn">
                <input type="submit" name="btn_add" class="bg-brand-600 hover:bg-brand-700 text-white font-medium rounded-lg px-4 py-2 transition-all active:scale-[0.97] inline-block mt-3" value="Thêm">
                <a href="index.php?act=list_category" class="bg-red-500 hover:bg-red-600 text-white rounded-lg px-3 py-1.5 mt-3 inline-block">Quay Lại danh sách</a>
              </div>
            </form>
            <h3 class="text-emerald-500 text-sm mt-3 font-semibold">
              <?php
              if (isset($notice) && $notice != "") {
                echo $notice;
              }
              ?>
            </h3>
          </div>
        </div>

      <?php include_once "footer.php" ?>
