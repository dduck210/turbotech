<?php

namespace Codemoi\Controller;

use Codemoi\Core\Controller;
use Codemoi\Model\User;

/**
 * Forgot-password flow. Ported from `index.php` cases `'mk'`, `'usermk'`,
 * `'forgotPass'`, `'verification'`, `'changePass'` (`index.php:113-174`).
 *
 * Originally two separate, confusing paths: one emailed a 6-digit code
 * (`forgotPass`), the other looked the account up by username+email and
 * printed the PLAINTEXT PASSWORD directly on the page (`usermk`) — a real
 * security smell, since anyone who guessed/knew a username+email pair (not
 * even the password) could read the actual password back. Consolidated
 * into one flow: recover by email OR phone number, code always emailed
 * (the only channel actually wired up — no SMS gateway account exists).
 */
class PasswordController extends Controller
{
    /**
     * Look up the account by email or phone, then email it a reset code.
     * `act=mk` — same route the rest of the app already links to
     * ("Quên mật khẩu?" on login, "lấy lại mật khẩu" on register).
     */
    public function forgotPassword(): void
    {
        $error = null;

        if (isset($_POST['btn_forgot']) && $_POST['btn_forgot']) {
            $identifier = trim($_POST['identifier'] ?? '');

            if ($identifier === '') {
                $error = 'Vui lòng nhập email hoặc số điện thoại';
            } else {
                $account = User::findByEmailOrPhone($identifier);

                if (!is_array($account)) {
                    $error = 'Không tìm thấy tài khoản với email hoặc số điện thoại này';
                } else {
                    $email = $account['email_user'];
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
        }

        $this->view('nguoidung/cachthuclaymk', ['error' => $error]);
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
