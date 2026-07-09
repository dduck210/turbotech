<?php

namespace Codemoi\Controller;

use Codemoi\Core\Controller;
use Codemoi\Model\User;

/**
 * Forgot-password flows. Ported from `index.php` cases `'mk'`, `'usermk'`,
 * `'forgotPass'`, `'verification'`, `'changePass'` (`index.php:113-174`).
 */
class PasswordController extends Controller
{
    /** Cách thức lấy lại mật khẩu (static links, no data). */
    public function methods(): void
    {
        $this->view('nguoidung/cachthuclaymk');
    }

    /** Lấy lại mật khẩu qua user_name + email. `index.php:118-130`. */
    public function byNameEmail(): void
    {
        $thongbao = '';

        if (isset($_POST['mk2']) && $_POST['mk2']) {
            $name = $_POST['user_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $checkuser = User::checkPass($name, $email);

            if (is_array($checkuser)) {
                $thongbao = '<p class="text-success"> Mật khẩu của tài khoản "' . $name . '" là: <span class="fw-bold">' . $checkuser['password'] . '</span></p>';
            } else {
                $thongbao = '<p class="text-danger fw-bold">Tài khoản hoặc Email không tồn tại! Vui lòng kiểm tra lại</p>';
            }
        }

        $this->view('nguoidung/laymk2', ['thongbao' => $thongbao]);
    }

    /** Gửi mã xác nhận qua email. `index.php:132-152`. */
    public function forgot(): void
    {
        $error = [];

        if (isset($_POST['btn_forgotPass'])) {
            $email = $_POST['email'] ?? '';

            if ($email == "") {
                $error['email'] = 'Không để trống Email!';
            }

            if (empty($error)) {
                // Kept for parity with the old call graph; the model no
                // longer echoes on "not found" (that side effect was
                // dropped in Phase 02 — see `Model\User::byEmail` docblock).
                User::byEmail($email);

                $code = substr((string) rand(0, 999999), 0, 6);
                $title = "Tìm lại mật khẩu của bạn";
                $content = "<p>Xin chào, chúng tôi đã nhận được yêu cầu đặt lại mật khẩu Turbotech của bạn.<br>
                            Nhập mã sau đây để đặt lại mật khẩu: <span style='color: black; font-weight: 600'>" . $code . "</span></p>";

                $mail = new \Codemoi\Mail\Mailer();
                $mail->sendMail($title, $content, $email);

                $_SESSION['mail'] = $email;
                $_SESSION['code'] = $code;
                $this->redirect('index.php?act=verification');
            }
        }

        $this->view('nguoidung/forgotpass', ['error' => $error]);
    }

    /** Nhập mã xác nhận. The template handles its own POST check inline
     * (`view/nguoidung/verification.php`) — no data to pass here.
     * `index.php:154-156`.
     */
    public function verification(): void
    {
        $this->view('nguoidung/verification');
    }

    /** Tạo mật khẩu mới. `index.php:159-174`. */
    public function change(): void
    {
        $error = [];

        if (isset($_POST['btn_changePass'])) {
            $password = $_POST['newpass'] ?? '';
            $email = $_SESSION['mail'] ?? null;

            if (($_POST['repass'] ?? null) !== ($_POST['newpass'] ?? null)) {
                $error['fail'] = 'Nhập lại mật khẩu không khớp !';
            } else {
                User::resetPassword($password, $email);
                $this->redirect('index.php?act=login');
            }
        }

        $this->view('nguoidung/changePass', ['error' => $error]);
    }
}
