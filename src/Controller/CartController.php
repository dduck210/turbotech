<?php

namespace Codemoi\Controller;

use Codemoi\Core\Controller;
use Codemoi\Model\Cart;
use Codemoi\Model\Product;

/**
 * Cart routes. Ported from `index.php` cases `'viewcart'`, `'edit'`,
 * `'addtocart'`, `'removecart'` (`index.php:220-276`), now delegating the
 * cart-array mutation to `Model\Cart` instead of inlining session-array
 * splicing here.
 *
 * NOTE: the route-table method for `'viewcart'` is named `viewCart()`
 * (not `view()`) to avoid shadowing the base `Controller::view()` render
 * helper that every method in this class relies on.
 */
class CartController extends Controller
{
    public function viewCart(): void
    {
        $this->view('giohang/viewcart');
    }

    /** AJAX-only mutation, no response body (`index.php:223-235`). */
    public function edit(): void
    {
        $code = $_POST['code'] ?? null;
        $quantity = $_POST['quantity'] ?? null;

        if ($code !== null && $quantity !== null) {
            Cart::update($code, $quantity);
        }
    }

    public function add(): void
    {
        if (isset($_POST['addtocart']) && $_POST['addtocart']) {
            $id_pro = $_POST['id_pro'] ?? null;
            $name_pro = $_POST['name_pro'] ?? '';
            $img_pro = $_POST['img_pro'] ?? '';
            $price = $_POST['price'] ?? 0;
            $quantity = (isset($_POST['quatity']) && $_POST['quatity'] >= 1) ? $_POST['quatity'] : 1;

            if ($id_pro !== null) {
                // Guard against adding more than is actually in stock (also
                // blocks POSTing straight to this route for an out-of-stock
                // product, bypassing the disabled button in the UI) —
                // account for however much of this product is already in
                // the cart, since Cart::add() merges into that line.
                $alreadyInCart = 0;
                foreach (Cart::items() as $line) {
                    if ($line[0] == $id_pro) {
                        $alreadyInCart = $line[4];
                        break;
                    }
                }

                if (Product::hasStock($id_pro, $alreadyInCart + $quantity)) {
                    Cart::add($id_pro, $name_pro, $img_pro, $price, $quantity);
                } else {
                    echo '<script>alert("Sản phẩm không đủ số lượng tồn kho!")</script>';
                }
            }

            $this->redirect('index.php?act=viewcart');
        }

        $this->view('giohang/viewcart');
    }

    public function remove(): void
    {
        if (isset($_GET['idcart'])) {
            Cart::remove($_GET['idcart']);
        } else {
            Cart::remove();
        }

        $this->redirect('index.php?act=viewcart');
    }
}
