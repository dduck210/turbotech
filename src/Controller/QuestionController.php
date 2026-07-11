<?php

namespace Codemoi\Controller;

use Codemoi\Core\Controller;
use Codemoi\Model\Question;

/**
 * Hỏi đáp (contact/question form). Ported from `index.php` case
 * `'question'` (`index.php:375-385`).
 */
class QuestionController extends Controller
{
    public function index(): void
    {
        if (isset($_POST['btn_question']) && $_POST['btn_question']) {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $contennt = $_POST['contennt'] ?? '';

            Question::create($name, $email, $phone, $contennt);
            echo '<script>alert("Gửi câu hỏi thành công !")</script>';
        }

        $this->view('qa/question');
    }
}
