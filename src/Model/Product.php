<?php

namespace Codemoi\Model;

use Codemoi\Core\Database;

/**
 * Product catalog queries. Ported from `model/sanpham.php` — same SQL,
 * same bound-parameter order, LIKE-escaping preserved verbatim.
 */
class Product
{
    /**
     * Latest 20 products for the home page.
     * Mirrors old `loadall_pro_home()`.
     */
    public static function allHome(): array
    {
        $sql = "SELECT * FROM product WHERE 1 ORDER BY id_pro desc limit 0,20";
        return Database::query($sql);
    }

    /**
     * All product ids, for building the sitemap.
     */
    public static function allIds(): array
    {
        $sql = "SELECT id_pro FROM product ORDER BY id_pro DESC";
        return Database::query($sql);
    }

    /**
     * Single product by id.
     * Mirrors old `loadone_pro($id_pro)`.
     *
     * @return array|false
     */
    public static function one(int $id_pro)
    {
        $sql = "SELECT * FROM product WHERE id_pro = ?";
        return Database::queryOne($sql, $id_pro);
    }

    /**
     * Search products by keyword / category / price range.
     * Mirrors old `loadall_pro($kyw, $idcate, $min, $max)`. Keeps the exact
     * LIKE-escaping (`\\`, `%`, `_`) from the legacy implementation.
     */
    public static function search($kyw = "", $idcate = 0, $min = 0, $max = 0): array
    {
        $sql = "SELECT * FROM product WHERE 1";
        $args = [];
        $kyw = trim($kyw);
        if ($kyw !== '') {
            $esc = str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $kyw);
            $sql .= " AND name_pro LIKE ?";
            $args[] = '%' . $esc . '%';
        }
        if ($idcate > 0) {
            $sql .= " AND idcate = ?";
            $args[] = $idcate;
        }
        if ($min > 0) {
            $sql .= " AND price >= ?";
            $args[] = $min;
        }
        if ($max > 0) {
            $sql .= " AND price <= ?";
            $args[] = $max;
        }
        $sql .= " ORDER BY id_pro desc";

        return Database::query($sql, ...$args);
    }

    /**
     * Products ranked by units sold across paid bills.
     * Mirrors old `loadall_pro_best()`.
     */
    public static function bestSellers(): array
    {
        $sql = "SELECT
                a.id_pro,
                a.name_pro,
                a.price,
                a.discount,
                a.img_pro,
                a.stock,
                a.stock_message,
                COUNT(*) as total_sale
            FROM product a
            INNER JOIN cart b ON a.id_pro = b.id_pro
            INNER JOIN bill c ON b.id_bill = c.id_bill
            WHERE c.status_pay = 1
            GROUP BY
                a.id_pro,
                a.name_pro,
                a.price,
                a.img_pro,
                a.stock_message
            ORDER BY total_sale DESC";

        return Database::query($sql);
    }

    /**
     * Top 8 most-viewed products.
     * Mirrors old `loadall_pro_noibat()`.
     */
    public static function featured(): array
    {
        $sql = "SELECT * FROM product WHERE 1 ORDER BY view desc limit 0,8";
        return Database::query($sql);
    }

    /**
     * Other products in the same category, excluding the current one.
     * Mirrors old `similar_pro($id_pro, $idcate)`.
     */
    public static function similar(int $id_pro, int $idcate): array
    {
        $sql = "SELECT * FROM product WHERE idcate = ? AND id_pro <> ?";
        return Database::query($sql, $idcate, $id_pro);
    }

    /**
     * Increment the view counter for a product.
     * Mirrors old `updateview($a)`.
     */
    public static function incrementView(int $id_pro): void
    {
        $sql = "UPDATE product SET view = view+1 WHERE id_pro = ?";
        Database::execute($sql, $id_pro);
    }

    /**
     * Decrement stock by `$quantity`, but only if enough stock remains —
     * the guard is baked into the WHERE clause (same pattern as
     * `Order::cancel()`) so two concurrent purchases of the last unit can't
     * both succeed and drive stock negative.
     *
     * @return bool True if stock was actually decremented.
     */
    public static function decrementStock(int $id_pro, int $quantity): bool
    {
        $sql = "UPDATE product SET stock = stock - ? WHERE id_pro = ? AND stock >= ?";
        return Database::execute($sql, $quantity, $id_pro, $quantity) > 0;
    }

    /**
     * Whether at least `$quantity` units are currently in stock.
     */
    public static function hasStock(int $id_pro, int $quantity = 1): bool
    {
        $sql = "SELECT stock FROM product WHERE id_pro = ?";
        $row = Database::queryOne($sql, $id_pro);
        return $row !== false && (int) $row['stock'] >= (int) $quantity;
    }

    /**
     * All products, optionally filtered by category — the admin product
     * list's simpler filter (no keyword/price range, unlike `search()`).
     * Mirrors old `admin/model/product.php::loadall_pro($idcate = 0)`.
     */
    public static function allAdmin(int $idcate = 0): array
    {
        $sql = "SELECT * FROM product WHERE 1";
        $args = [];
        if ($idcate > 0) {
            $sql .= " AND idcate = ?";
            $args[] = $idcate;
        }
        $sql .= " ORDER BY id_pro DESC";

        return Database::query($sql, ...$args);
    }

    /** Mirrors old `add_pro(...)`. */
    public static function create(
        string $namePro,
        string $price,
        string $discount,
        string $imgPro,
        string $shortDes,
        string $detailDes,
        int $idcate,
        int $stock = 0,
        ?string $stockMessage = null
    ): void {
        $sql = "INSERT INTO product (name_pro, price, discount, img_pro, short_des, detail_des, idcate, stock, stock_message) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        Database::execute($sql, $namePro, $price, $discount, $imgPro, $shortDes, $detailDes, $idcate, $stock, $stockMessage);
    }

    /**
     * Mirrors old `update_pro(...)`. When `$imgPro` is empty, the image
     * column is left untouched (same "leave it if no new file" behavior
     * as the old two-branch SQL).
     */
    public static function update(
        int $idPro,
        string $namePro,
        string $price,
        string $discount,
        string $shortDes,
        string $detailDes,
        string $imgPro,
        int $idcate,
        int $stock = 0,
        ?string $stockMessage = null
    ): void {
        if ($imgPro !== '') {
            $sql = "UPDATE product SET
        name_pro = ?,
        price = ?,
        discount = ?,
        short_des = ?,
        detail_des = ?,
        img_pro = ?,
        idcate = ?,
        stock = ?,
        stock_message = ?
        WHERE id_pro = ?";
            Database::execute($sql, $namePro, $price, $discount, $shortDes, $detailDes, $imgPro, $idcate, $stock, $stockMessage, $idPro);
            return;
        }

        $sql = "UPDATE product SET
        name_pro = ?,
        price = ?,
        discount = ?,
        short_des = ?,
        detail_des = ?,
        idcate = ?,
        stock = ?,
        stock_message = ?
        WHERE id_pro = ?";
        Database::execute($sql, $namePro, $price, $discount, $shortDes, $detailDes, $idcate, $stock, $stockMessage, $idPro);
    }

    /**
     * Mirrors old `remove_pro($id_pro)`. Throws PDOException (SQLSTATE
     * 23000) when the product is still referenced by a cart/order line —
     * callers guard this (see `Controller\Admin\ProductController::delete()`).
     */
    public static function delete(int $idPro): void
    {
        Database::execute("DELETE FROM product WHERE id_pro = ?", $idPro);
    }
}
