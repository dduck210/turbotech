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
     * Mirrors old `admin/model/question.php::delete_ques($id_ques)`. No FK
     * references `question` (Phase 06 audit), so this can't fail on delete.
     */
    public static function delete(int $idQues): void
    {
        Database::execute("DELETE FROM `question` WHERE id_ques = ?", $idQues);
    }
}
