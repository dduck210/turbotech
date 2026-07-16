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
    public static function create(string $content, int $id_user, string $user_name, string $full_name, int $idpro, string $comment_date): void
    {
        $sql = "INSERT INTO comment (content, id_user, user_name, full_name, id_pro, comment_date) VALUES (?, ?, ?, ?, ?, ?)";
        Database::execute($sql, $content, $id_user, $user_name, $full_name, $idpro, $comment_date);
    }

    /**
     * Latest 8 comments for a product.
     * Mirrors old `loadall_comment($idpro)`.
     */
    public static function forProduct(int $idpro): array
    {
        $sql = "SELECT * FROM comment WHERE id_pro = ? ORDER BY id_cmt desc limit 0,8";
        return Database::query($sql, $idpro);
    }

    /**
     * Whether this user has already reviewed this product — a verified
     * purchase (`Order::hasDeliveredPurchase()`) only gates ELIGIBILITY to
     * review, it doesn't stop the same eligible customer from submitting
     * unlimited reviews for the same product.
     */
    public static function hasReviewed(int $id_user, int $id_pro): bool
    {
        $sql = "SELECT 1 FROM comment WHERE id_user = ? AND id_pro = ? LIMIT 1";
        return Database::queryOne($sql, $id_user, $id_pro) !== false;
    }

    /**
     * Every comment across all products (for moderation), joined with the
     * product so the admin list can show which product each comment is on.
     * Mirrors old `admin/model/comment.php::loadall_cmt()`.
     */
    public static function allAdmin(): array
    {
        $sql = "SELECT * FROM comment c inner join product p on c.id_pro = p.id_pro ORDER BY id_cmt DESC";
        return Database::query($sql);
    }

    /**
     * Mirrors old `admin/model/comment.php::remove_cmt($id_cmt)`. No FK
     * references `comment` (Phase 06 audit), so this can't fail on delete.
     */
    public static function delete(int $idCmt): void
    {
        Database::execute("DELETE FROM comment WHERE id_cmt = ?", $idCmt);
    }
}
