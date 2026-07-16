<?php

namespace Codemoi\Model;

use Codemoi\Core\Database;

/**
 * Contact / question form submissions. Ported from `model/question.php`.
 */
class Question
{
    /**
     * Create a question/contact submission.
     * Mirrors old `question($name, $email, $phone, $contennt)`.
     */
    public static function create(string $name, string $email, string $phone, string $contennt): void
    {
        $sql = "INSERT INTO `question`( `name`, `email`, `phone`, `contennt`) VALUES (?, ?, ?, ?)";
        Database::execute($sql, $name, $email, $phone, $contennt);
    }

    /**
     * Every question/contact submission (for moderation). Mirrors old
     * `admin/model/question.php::question()`.
     */
    public static function allAdmin(): array
    {
        return Database::query("SELECT * FROM `question` WHERE 1");
    }

    /**
     * Single question by id, for the admin reply form.
     *
     * @return array|false
     */
    public static function find(int $idQues)
    {
        return Database::queryOne("SELECT * FROM `question` WHERE id_ques = ?", $idQues);
    }

    /**
     * Save the admin's reply. Submissions aren't tied to a logged-in
     * account (name/email/phone only), so the reply is emailed to the
     * submitted address rather than shown on any "my questions" page —
     * this just records what was sent and when.
     */
    public static function saveReply(int $idQues, string $reply): void
    {
        Database::execute("UPDATE `question` SET reply = ?, replied_at = NOW() WHERE id_ques = ?", $reply, $idQues);
    }

    /**
     * Mirrors old `admin/model/question.php::delete_ques($id_ques)`. No FK
     * references `question` (Phase 06 audit), so this can't fail on delete.
     */
    public static function delete(int $idQues): void
    {
        Database::execute("DELETE FROM `question` WHERE id_ques = ?", $idQues);
    }
}
