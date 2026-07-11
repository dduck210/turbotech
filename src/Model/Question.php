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
}
