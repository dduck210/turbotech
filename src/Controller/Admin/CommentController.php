<?php

namespace Codemoi\Controller\Admin;

use Codemoi\Model\Comment;

/**
 * Comment moderation. Ported from `public/admin/index.php` cases
 * `list_cmt`/`delete_cmt`.
 */
class CommentController extends AdminController
{
    public function list(): void
    {
        $this->requireAdmin();
        $this->render('list_comment', ['listcmt' => Comment::allAdmin()]);
    }

    /** Old code had no admin-session guard here — see CategoryController::update() for why. No FK references `comment`, so no try/catch needed. */
    public function delete(): void
    {
        $this->requireAdmin();

        if (isset($_GET['idcmt']) && $_GET['idcmt'] > 0) {
            Comment::delete((int) $_GET['idcmt']);
        }

        $this->redirect('index.php?act=list_cmt');
    }
}
