<?php

namespace Codemoi\Model;

use Codemoi\Core\Database;

/**
 * Product comments. Ported from `model/binhluan.php`.
 */
class Comment
{
    /**
     * Create a comment on a product.
     * Mirrors old `insert_comment($content, $id_user, $user_name, $full_name, $idpro, $comment_date)`.
     */
    public static function create($content, $id_user, $user_name, $full_name, $idpro, $comment_date): void
    {
        $sql = "INSERT INTO comment (content, id_user, user_name, full_name, id_pro, comment_date) VALUES (?, ?, ?, ?, ?, ?)";
        Database::execute($sql, $content, $id_user, $user_name, $full_name, $idpro, $comment_date);
    }

    /**
     * Latest 8 comments for a product.
     * Mirrors old `loadall_comment($idpro)`.
     */
    public static function forProduct($idpro): array
    {
        $sql = "SELECT * FROM comment WHERE id_pro = ? ORDER BY id_cmt desc limit 0,8";
        return Database::query($sql, $idpro);
    }
}
