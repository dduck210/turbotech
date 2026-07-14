<?php include_once "header.php" ?>

<div class="mb-8 pb-5 border-b border-ink-300">
  <div class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-1">Quản lý</div>
  <h1 class="font-heading text-3xl text-ink-900">Cập nhật tài khoản người dùng</h1>
</div>

<div class="card-boutique rounded-lg overflow-hidden mb-6">
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
          <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Mã người
            dùng</label>
          <input type="text" name="id_user"
            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-100 text-ink-600"
            placeholder="Mã KH(auto increase)" value="<?= e($id_user) ?>" disabled>
        </div>
        <div class="mb-4">
          <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Tên đăng
            nhập</label>
          <input type="text" name="user_name" data-rules="required|min:3|max:50"
            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800"
            placeholder="Nhập tên dùng để đăng nhập" value="<?= e($user_name) ?>">
        </div>
        <div class="mb-4">
          <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Họ
            tên</label>
          <input type="text" name="full_name" data-rules="required|min:2|max:100"
            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800"
            placeholder="Nhập họ và tên người dùng" value="<?= e($full_name) ?>">
        </div>
        <div class="mb-4">
          <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Email</label>
          <input type="email" name="email_user" data-rules="required|email"
            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800"
            placeholder="Nhập email người dùng" value="<?= e($email_user) ?>">
        </div>
        <div class="mb-4">
          <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Mật
            khẩu</label>
          <input type="password" name="password" data-rules="min:6" autocomplete="new-password"
            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800"
            placeholder="Để trống nếu không đổi mật khẩu">
        </div>

        <div class="mb-4">
          <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Vai trò: <span
              class="text-red-600 normal-case">
              <?php if ($role == 1) {
                echo "Admin";
              } else {
                echo "Thành viên";
              } ?></span></label>
          <select required
            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800"
            name="role" id="">
            <?php $arr = array('0' => 'Thành Viên', '1' => 'Admin'); ?>
            <?php foreach ($arr as $key => $value) { ?>
              <option value="<?php echo $key; ?>" <?php echo $key ==  $role ? ' selected="selected"' : ''; ?>>
                <?php echo $value; ?></option>
            <?php } ?>
          </select>
        </div>

        <div class="flex items-center gap-3 mt-6 pt-6 border-t border-ink-200">
          <input type="hidden" name="id_user" value="<?= e($id_user) ?>">
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
