<?php

namespace Codemoi\Controller\Admin;

use Codemoi\Model\Question;

/**
 * Question/contact-form moderation. Ported from `public/admin/index.php`
 * cases `list_ques`/`delete_ques`. No answer/reply path exists in the old
 * code (confirmed — moderation is list + delete only).
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
}
