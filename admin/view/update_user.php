<?php include_once "header.php" ?>

<div class="bg-white rounded-xl shadow-sm border border-ink-200 overflow-hidden mb-6">
  <div class="px-6 py-4 border-b border-ink-200 bg-ink-50/50 font-semibold text-ink-800">
    <h6 class="m-0 text-emerald-600">Cập nhật tài khoản người dùng</h6>
  </div>
  <div class="p-6">
    <div class="form-addcate">
      <form action="index.php?act=update_user" method="post" data-validate novalidate>
<?= \Codemoi\Core\Csrf::field() ?>
        <?php
        /** @var array $user */
        if (is_array($user))
          extract($user);
        ?>
        <div class="mb-4">
          <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Mã người
            dùng</label>
          <input type="text" name="id_user"
            class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all"
            placeholder="Mã KH(auto increase)" value="<?= e($id_user) ?>" disabled>
        </div>
        <div class="mb-4">
          <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Tên đăng
            nhập</label>
          <input type="text" name="user_name" data-rules="required|min:3|max:50"
            class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all"
            placeholder="Nhập tên dùng để đăng nhập" value="<?= e($user_name) ?>">
        </div>
        <div class="mb-4">
          <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Họ
            tên</label>
          <input type="text" name="full_name" data-rules="required|min:2|max:100"
            class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all"
            placeholder="Nhập họ và tên người dùng" value="<?= e($full_name) ?>">
        </div>
        <div class="mb-4">
          <label for="formGroupExampleInput"
            class="block text-sm font-medium text-ink-700 mb-1">Email</label>
          <input type="email" name="email_user" data-rules="required|email"
            class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all"
            placeholder="Nhập email người dùng" value="<?= e($email_user) ?>">
        </div>
        <div class="mb-4">
          <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Mật
            khẩu</label>
          <input type="password" name="password" data-rules="min:6" autocomplete="new-password"
            class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all"
            placeholder="Để trống nếu không đổi mật khẩu">
        </div>

        <div class="mb-4">
          <label for="" class="block text-sm font-medium text-ink-700 mb-1">Vai trò: <span
              style="color:red">
              <?php if ($role == 1) {
                echo "Admin";
              } else {
                echo "Thành viên";
              } ?></span></label>
          <select required
            class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all bg-white"
            name="role" id="">
            <?php $arr = array('0' => 'Thành Viên', '1' => 'Admin'); ?>
            <?php foreach ($arr as $key => $value) { ?>
              <option value="<?php echo $key; ?>" <?php echo $key ==  $role ? ' selected="selected"' : ''; ?>>
                <?php echo $value; ?></option>
            <?php } ?>
          </select>
        </div>

        <div class="wrap-btn mt-4">
          <input type="hidden" name="id_user" value="<?= e($id_user) ?>">
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