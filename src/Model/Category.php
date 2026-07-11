<?php

namespace Codemoi\Model;

use Codemoi\Core\Database;

/**
 * Category queries. Ported from `model/loai.php`. `name()` drops the old
 * `extract($cate); return $name_cate;` smell and returns the column directly.
 */
class Category
{
    /**
     * All categories, newest first.
     * Mirrors old `loadall_cate()`.
     */
    public static function all(): array
    {
        $sql = "SELECT * FROM category ORDER BY id_cate DESC";
        return Database::query($sql);
    }

    /**
     * Category name by id, or a single space when no id is given.
     * Mirrors old `load_namecate($idcate)` without the `extract()` call.
     */
    public static function name(int $idcate): string
    {
        if ($idcate > 0) {
            $sql = "SELECT * FROM category WHERE id_cate = ?";
            $cate = Database::queryOne($sql, $idcate);
            return $cate ? $cate['name_cate'] : " ";
        }

        return " ";
    }
}
