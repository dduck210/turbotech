<?php

namespace Codemoi\Controller;

use Codemoi\Core\Controller;
use Codemoi\Core\Seo;
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
        Seo::setTitle('Giỏ hàng - Turbotech');
        Seo::setDescription('Xem lại giỏ hàng của bạn tại Turbotech trước khi đặt hàng.');
        $this->view('cart/viewcart');
    }

    /**
     * AJAX quantity-stepper update. The front controller (index.php) always
     * wraps every route's output in the full header/footer HTML — there's
     * no "bare" route — so a plain `header('Content-Type: application/json')`
     * + JSON body isn't actually valid JSON by the time it reaches the
     * browser; it's JSON sandwiched inside a full page. The old code got
     * away with this because it sent no body at all and the caller never
     * inspected the response. Now that the caller needs to know whether the
     * quantity got clamped to available stock, the result is wrapped in an
     * HTML-comment marker that view/cart/viewcart.php's saveCart() pulls
     * out of the full response with a regex instead of relying on
     * dataType: "json" to parse the whole body.
     */
    public function edit(): void
    {
        $code = $_POST['code'] ?? null;
        $quantity = $_POST['quantity'] ?? null;
        $result = ['success' => false];

        if ($code !== null && $quantity !== null) {
            $quantity = (int) $quantity;
            // Cart lines are indexed positionally by $code (see Model\Cart's
            // docblock) — index [0] of the tuple at that position is id_pro.
            $items = Cart::items();
            $idPro = $items[$code][0] ?? null;

            // A negative quantity used to fall through to the `else` branch
            // below untouched (only `$quantity > 0` triggered the stock
            // clamp) — Cart::update() would store it as-is, and at checkout
            // Product::decrementStock()'s `stock = stock - ?` arithmetic
            // would then ADD stock back for a negative quantity, letting a
            // logged-in customer inflate a product's stock for free.
            if ($idPro !== null && $quantity >= 0 && $quantity > 0 && !Product::hasStock((int) $idPro, $quantity)) {
                $maxStock = (int) (Product::one((int) $idPro)['stock'] ?? 0);
                Cart::update((int) $code, $maxStock);
                $result = ['success' => false, 'clampedTo' => $maxStock, 'message' => 'Chỉ còn ' . $maxStock . ' sản phẩm trong kho.'];
            } elseif ($idPro !== null && $quantity >= 0) {
                Cart::update((int) $code, $quantity);
                $result = ['success' => true];
            }
        }

        echo '<!--CART_EDIT_RESULT:' . json_encode($result) . ':END-->';
    }

    public function add(): void
    {
        if (isset($_POST['addtocart']) && $_POST['addtocart']) {
            $id_pro = $_POST['id_pro'] ?? null;
            $quantity = (isset($_POST['quatity']) && $_POST['quatity'] >= 1) ? $_POST['quatity'] : 1;

            // name_pro/img_pro/price used to come straight from the POSTed
            // hidden form fields — trivially edited client-side to add an
            // item at an arbitrary price. Always re-fetch the real values
            // server-side instead; the client can't be trusted with money.
            $product = $id_pro !== null ? Product::one((int) $id_pro) : null;
            $name_pro = $product['name_pro'] ?? '';
            $img_pro = $product['img_pro'] ?? '';
            // The post-discount price, not the raw one — every template
            // (homepage, listing, detail) shows the discounted "sale price",
            // so that's what must actually get charged at checkout too.
            $price = is_array($product) ? Product::discountedPrice($product) : 0;

            if (is_array($product)) {
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
                    // Flash, not echo — this always falls through to the
                    // redirect below, and a script echoed right before
                    // header('Location: ...') never runs in a real browser.
                    $_SESSION['flash_error'] = 'Sản phẩm không đủ số lượng tồn kho!';
                }
            }

            $this->redirect('index.php?act=viewcart');
        }

        $this->view('cart/viewcart');
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
