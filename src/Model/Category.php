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

    /**
     * Look up a single category by id. Mirrors old `loadone_loai($id_cate)`.
     *
     * @return array|false
     */
    public static function find(int $idCate)
    {
        return Database::queryOne("SELECT * FROM category WHERE id_cate = ?", $idCate);
    }

    /** Mirrors old `them_loai($name_cate)`. */
    public static function create(string $nameCate): void
    {
        Database::execute("INSERT INTO category (name_cate) VALUES (?)", $nameCate);
    }

    /** Mirrors old `capnhat_loai($id_cate, $name_cate)`. */
    public static function update(int $idCate, string $nameCate): void
    {
        Database::execute("UPDATE category SET name_cate = ? WHERE id_cate = ?", $nameCate, $idCate);
    }

    /**
     * Mirrors old `xoa_loai($id_cate)`. Throws PDOException (SQLSTATE 23000)
     * when the category is still referenced by a product — callers guard
     * this the same way the old code did (see
     * `Controller\Admin\CategoryController::delete()`).
     */
    public static function delete(int $idCate): void
    {
        Database::execute("DELETE FROM category WHERE id_cate = ?", $idCate);
    }
}
