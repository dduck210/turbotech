<?php
/**
 * Partial: renders a persisted order's cart lines as an HTML table.
 * Inlined from the old `cart_detail($listcart)` helper (`model/giohang.php`),
 * used by both `billconfirm.php` and `viewbill.php`. Expects `$cart_detail`
 * (array of associative rows from `Codemoi\Model\Order::items()`) to
 * already be in scope.
 */
$total_amount = 0;
echo '
 <table class="table">
 <thead>
<tr>
 <th class="jb-product-thumbnail">Hình ảnh</th>
 <th class="cart-product-name">Sản phẩm</th>
 <th class="jb-product-price">Đơn giá</th>
 <th class="jb-product-quantity">Số lượng</th>
 <th class="jb-product-subtotal">Thành tiền</th>
</tr>
</thead>';
foreach ($cart_detail as $cart) {
    $img_pro = "admin/uploads/" . $cart['img_pro'];
    $prodetail = "index.php?act=prodetail&idpro=" . $cart['id_pro'];
    $total_amount += $cart['total_amount'];
    echo '<tbody>
    <tr>
        <td class="jb-product-thumbnail"><img src="' . $img_pro . '" alt="Turbotech Product" width="80px"></img></td>
        <td class="jb-product-name"><a href="' . $prodetail . '">' . $cart['name_pro'] . '</a></td>
        <td class="jb-product-price"><span class="amount">' . number_format($cart['price_pro']) . '₫</span></td>
        <td class="quantity">' . $cart['quantity'] . '</td>
        <td class="product-subtotal"><span class="amount">' . number_format($cart['total_amount']) . '₫</span></td>
    </tr>
</tbody>';
}
echo '
 </table>
</div>
<div class="row">
    <div class="col-md-5 ml-auto">
        <div class="cart-page-total">
            <h2>Tổng giỏ hàng</h2>
            <ul>
                <li>Tổng thành tiền <span>' . number_format($total_amount) . '₫</span></li>
            </ul>
        </div>
    </div>
</div>';
