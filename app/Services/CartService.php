<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

/**
 * Session-based cart — ported from `Codemoi\Model\Cart` (legacy
 * `src/Model/Cart.php`). Each line is an associative array (not the
 * legacy's positional tuple — nothing outside this class reads cart lines
 * directly anymore, so there's no reason to keep the position-dependent
 * format): id_pro, name_pro, img_pro, price, quantity, total.
 */
class CartService
{
    private const SESSION_KEY = 'mycart';

    public function items(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    /** Merges into an existing line for the same product instead of duplicating. */
    public function add(int $idPro, string $namePro, string $imgPro, int $price, int $quantity = 1): void
    {
        $items = $this->items();

        foreach ($items as $key => $line) {
            if ((int) $line['id_pro'] === $idPro) {
                $items[$key]['quantity'] += $quantity;
                $items[$key]['total'] = $items[$key]['price'] * $items[$key]['quantity'];
                Session::put(self::SESSION_KEY, $items);

                return;
            }
        }

        $items[] = [
            'id_pro' => $idPro,
            'name_pro' => $namePro,
            'img_pro' => $imgPro,
            'price' => $price,
            'quantity' => $quantity,
            'total' => $price * $quantity,
        ];
        Session::put(self::SESSION_KEY, $items);
    }

    /** A quantity of 0 (or negative) removes the line — mirrors the legacy Cart::update(). */
    public function update(int $index, int $quantity): void
    {
        $items = $this->items();
        if (! isset($items[$index])) {
            return;
        }

        if ($quantity <= 0) {
            unset($items[$index]);
            Session::put(self::SESSION_KEY, array_values($items));

            return;
        }

        $items[$index]['quantity'] = $quantity;
        $items[$index]['total'] = $items[$index]['price'] * $quantity;
        Session::put(self::SESSION_KEY, $items);
    }

    public function remove(?int $index = null): void
    {
        if ($index === null) {
            Session::put(self::SESSION_KEY, []);

            return;
        }

        $items = $this->items();
        unset($items[$index]);
        Session::put(self::SESSION_KEY, array_values($items));
    }

    public function clear(): void
    {
        Session::put(self::SESSION_KEY, []);
    }

    public function count(): int
    {
        return array_sum(array_column($this->items(), 'quantity'));
    }

    public function total(): int
    {
        return array_sum(array_column($this->items(), 'total'));
    }
}