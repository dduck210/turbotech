<?php

namespace Codemoi\Controller\Admin;

use Codemoi\Model\Question;

/**
 * Question/contact-form moderation. Ported from `public/admin/index.php`
 * cases `list_ques`/`delete_ques` (list + delete only in the old code).
 * Reply is a new addition: submissions aren't tied to a logged-in account,
 * so there's nowhere in the client UI to show a reply — it's emailed to
 * the address the customer submitted, via the same Mailer already used
 * for order confirmations and password resets.
 */
class QuestionController extends AdminController
{
    public function list(): void
    {
        $this->requireAdmin();
        $this->render('list_question', ['listques' => Question::allAdmin()]);
    }

    /** Old code had no admin-session guard here — see CategoryController::update() for why. No FK references `question`, so no try/catch needed. */
    public function delete(): void
    {
        $this->requireAdmin();

        if (isset($_GET['id_ques']) && $_GET['id_ques'] > 0) {
            Question::delete((int) $_GET['id_ques']);
        }

        $this->redirect('index.php?act=list_ques');
    }

    public function replyForm(): void
    {
        $this->requireAdmin();

        $question = (isset($_GET['id_ques']) && $_GET['id_ques'] > 0)
            ? Question::find((int) $_GET['id_ques'])
            : false;

        if (!is_array($question)) {
            $_SESSION['flash_error'] = 'Không tìm thấy câu hỏi này.';
            $this->redirect('index.php?act=list_ques');
        }

        $this->render('reply_question', ['question' => $question]);
    }

    public function sendReply(): void
    {
        $this->requireAdmin();

        if (isset($_POST['btn_reply']) && $_POST['btn_reply']) {
            $id_ques = (int) $_POST['id_ques'];
            $reply = trim($_POST['reply'] ?? '');
            $question = Question::find($id_ques);

            if (!is_array($question)) {
                $_SESSION['flash_error'] = 'Không tìm thấy câu hỏi này.';
                $this->redirect('index.php?act=list_ques');
            }

            if ($reply === '') {
                $_SESSION['flash_error'] = 'Vui lòng nhập nội dung trả lời.';
                $this->redirect('index.php?act=reply_question&id_ques=' . $id_ques);
            }

            Question::saveReply($id_ques, $reply);

            $title = 'Turbotech đã trả lời câu hỏi của bạn';
            $content = "<p>Xin chào " . htmlspecialchars($question['name']) . ",</p>"
                . "<p>Câu hỏi của bạn: <em>" . nl2br(htmlspecialchars($question['contennt'])) . "</em></p>"
                . "<p>Trả lời từ Turbotech:<br>" . nl2br(htmlspecialchars($reply)) . "</p>";

            $mail = new \Codemoi\Mail\Mailer();
            $sent = $mail->sendMail($title, $content, $question['email']);

            $_SESSION['flash_success'] = $sent
                ? 'Đã gửi trả lời qua email!'
                : 'Đã lưu trả lời, nhưng gửi email thất bại — vui lòng kiểm tra cấu hình email.';
        }

        $this->redirect('index.php?act=list_ques');
    }
}
