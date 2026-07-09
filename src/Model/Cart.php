<?php

namespace Codemoi\Model;

/**
 * Data-only wrapper around `$_SESSION['mycart']`. Ported from the HTML-free
 * parts of `model/giohang.php` (`countcart()`, `total_amount()`) plus the
 * cart-mutation logic that was inlined in `index.php` cases 'edit' /
 * 'addtocart' / 'removecart' (lines ~223-276).
 *
 * Each cart line is a POSITIONAL tuple, preserved exactly as the legacy code
 * expects (existing `view/giohang/*.php` templates read these by index):
 *   [0] id_pro, [1] name_pro, [2] img_pro, [3] price, [4] quantity, [5] total
 */
class Cart
{
    /**
     * Ensure the session cart array exists.
     * Mirrors old `if (!isset($_SESSION['mycart'])) $_SESSION['mycart'] = [];`
     * (`index.php:15-16`).
     */
    private static function ensure(): void
    {
        if (!isset($_SESSION['mycart']) || !is_array($_SESSION['mycart'])) {
            $_SESSION['mycart'] = [];
        }
    }

    /**
     * All cart line tuples.
     */
    public static function items(): array
    {
        self::ensure();
        return $_SESSION['mycart'];
    }

    /**
     * Add a product to the cart. If the product is already present, its
     * quantity/total are increased instead of adding a duplicate line.
     * Mirrors old 'addtocart' case (`index.php:237-264`).
     */
    public static function add($id_pro, $name_pro, $img_pro, $price, $quantity = 1): void
    {
        self::ensure();

        foreach ($_SESSION['mycart'] as $k => $line) {
            if ($id_pro == $line[0]) {
                $_SESSION['mycart'][$k][4] = $line[4] + $quantity;
                $_SESSION['mycart'][$k][5] = $_SESSION['mycart'][$k][3] * $_SESSION['mycart'][$k][4];
                return;
            }
        }

        $total = $price * $quantity;
        $_SESSION['mycart'][] = [$id_pro, $name_pro, $img_pro, $price, $quantity, $total];
    }

    /**
     * Update the quantity of the line at $index. A quantity of 0 removes
     * the line entirely.
     * Mirrors old 'edit' case (`index.php:223-235`).
     */
    public static function update($index, $quantity): void
    {
        self::ensure();

        if (!isset($_SESSION['mycart'][$index])) {
            return;
        }

        if ($quantity == '0') {
            array_splice($_SESSION['mycart'], $index, 1);
            return;
        }

        $_SESSION['mycart'][$index][4] = $quantity;
        $_SESSION['mycart'][$index][5] = $_SESSION['mycart'][$index][3] * $_SESSION['mycart'][$index][4];
    }

    /**
     * Remove one line by index, or clear the whole cart when no index is
     * given.
     * Mirrors old 'removecart' case (`index.php:268-276`).
     */
    public static function remove($index = null): void
    {
        self::ensure();

        if ($index === null) {
            $_SESSION['mycart'] = [];
            return;
        }

        if (isset($_SESSION['mycart'][$index])) {
            array_splice($_SESSION['mycart'], $index, 1);
        }
    }

    /**
     * Empty the cart.
     * Mirrors old inline `$_SESSION['mycart'] = [];` after checkout
     * (`index.php:355,409`).
     */
    public static function clear(): void
    {
        $_SESSION['mycart'] = [];
    }

    /**
     * Total quantity across all lines.
     * Mirrors old `countcart()` (`model/giohang.php:134`).
     */
    public static function count(): int
    {
        self::ensure();

        $i = 0;
        foreach ($_SESSION['mycart'] as $line) {
            $i += $line[4];
        }

        return $i;
    }

    /**
     * Sum of price*quantity across all lines.
     * Mirrors old `total_amount()` (`model/giohang.php:142`).
     */
    public static function total()
    {
        self::ensure();

        $total_amount = 0;
        foreach ($_SESSION['mycart'] as $line) {
            $total_amount += $line[3] * $line[4];
        }

        return $total_amount;
    }
}
