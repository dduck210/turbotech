<!--
    Cart page. Reads $_SESSION['mycart'] — array of positional tuples
    [id_pro, name_pro, img_pro, price, quantity, total]. index [4]=quantity,
    [5]=total — DO NOT change this indexing.
-->
<nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-6" aria-label="Breadcrumb">
    <ol class="flex items-center gap-2 text-sm text-ink-500">
        <li><a href="index.php" class="hover:text-brand-600 transition-colors">Trang chủ</a></li>
        <li aria-hidden="true">/</li>
        <li class="font-medium text-ink-900" aria-current="page">Giỏ hàng</li>
    </ol>
</nav>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <h1 class="font-heading text-2xl md:text-3xl font-bold text-ink-900 mb-6">Giỏ hàng của bạn</h1>

    <form action="index.php?act=bill" method="post">
        <?php if (!empty($_SESSION['mycart'])) {
            $total_amount = 0;
            $i = 0;
        ?>
        <div class="rounded-2xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-ink-50 text-xs uppercase tracking-wide text-ink-500">
                        <tr>
                            <th class="px-4 py-3" scope="col">Hình ảnh</th>
                            <th class="px-4 py-3" scope="col">Sản phẩm</th>
                            <th class="px-4 py-3" scope="col">Đơn giá</th>
                            <th class="px-4 py-3 text-center" scope="col">Số lượng</th>
                            <th class="px-4 py-3" scope="col">Thành tiền</th>
                            <th class="px-4 py-3 text-center" scope="col">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink-100">
                        <?php foreach ($_SESSION['mycart'] as $key => $cart) {
                            $total_amount = $total_amount + $cart[5];
                            $removepro = "index.php?act=removecart&idcart=" . $key;
                            $prodetail = "index.php?act=prodetail&idpro=" . $cart[0];
                        ?>
                        <tr>
                            <td class="px-4 py-3">
                                <a href="<?= $prodetail ?>" class="block h-16 w-16 overflow-hidden rounded-lg bg-ink-100">
                                    <img src="admin/uploads/<?= $cart[2] ?>" alt="<?= $cart[1] ?>" class="h-full w-full object-cover" />
                                </a>
                            </td>
                            <td class="px-4 py-3">
                                <a href="<?= $prodetail ?>" class="font-heading font-semibold text-ink-900 hover:text-brand-600 transition-colors"><?= $cart[1] ?></a>
                            </td>
                            <td class="px-4 py-3 text-ink-700"><?= number_format($cart[3]) ?> ₫</td>
                            <td class="px-4 py-3">
                                <div class="mx-auto w-20">
                                    <label for="<?= $key ?>" class="sr-only">Số lượng</label>
                                    <input type="number" name="quantity" id="<?= $key ?>" value="<?= $cart[4] ?>" onchange="saveCart(this);"
                                        class="block w-full rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-2 py-2 text-center text-sm text-ink-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500" />
                                </div>
                            </td>
                            <td class="px-4 py-3 font-semibold text-ink-900"><?= number_format($cart[5]) ?> ₫</td>
                            <td class="px-4 py-3 text-center">
                                <a href="<?= $removepro ?>" title="Xóa sản phẩm"
                                    class="inline-flex h-11 w-11 items-center justify-center rounded-full text-red-600 hover:bg-red-50 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                    <span class="sr-only">Xóa</span>
                                </a>
                            </td>
                        </tr>
                        <?php $i++;
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
            <a href="index.php?act=removecart"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-red-200 bg-ink-800/70 backdrop-blur-xl px-5 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500">
                <i class="fa-solid fa-trash" aria-hidden="true"></i> Xóa toàn bộ sản phẩm
            </a>
            <a href="index.php?act=product"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-5 py-2.5 text-sm font-semibold text-ink-900 hover:bg-ink-50 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500">
                Xem thêm sản phẩm
            </a>
        </div>

        <div class="mt-8 flex justify-end">
            <div class="w-full md:w-96 rounded-2xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl p-6 shadow-sm">
                <h2 class="font-heading text-lg font-semibold text-ink-900 mb-4">Tổng giỏ hàng</h2>
                <div class="flex items-center justify-between text-sm text-ink-700 mb-6">
                    <span>Tổng thành tiền</span>
                    <span class="text-lg font-bold text-brand-600"><?= number_format($total_amount) ?> ₫</span>
                </div>
                <button type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                    Xác nhận đặt hàng
                </button>
            </div>
        </div>
        <?php } else { ?>
        <div class="rounded-2xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl py-16 text-center shadow-sm">
            <i class="fa-solid fa-cart-shopping text-5xl text-ink-300" aria-hidden="true"></i>
            <h3 class="mt-4 font-heading text-lg font-semibold text-red-600">Giỏ hàng trống. Vui lòng thêm sản phẩm để đặt hàng!</h3>
            <div class="mt-6">
                <a href="index.php?act=product"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Thêm sản phẩm
                </a>
            </div>
        </div>
        <?php } ?>
    </form>
</div>
<script>
    function aler() {
        alert("Problen in sending reply!")
    }

    function saveCart(obj) {
        var quantity = $(obj).val();
        // var code = document.getElementById("code").value;
        var code = $(obj).attr("id");

        $.ajax({
            url: "?act=edit",
            type: "POST",
            data: 'quantity=' + quantity + '&code=' + code,
            success: function(data, status) {
                // The response is a full page (index.php wraps every route
                // in header/footer HTML), so the actual result is pulled
                // out of an HTML-comment marker rather than parsed as JSON.
                var match = /<!--CART_EDIT_RESULT:(.*?):END-->/.exec(data);
                if (match) {
                    try {
                        var result = JSON.parse(match[1]);
                        if (result.success === false && result.message) {
                            alert(result.message);
                        }
                    } catch (e) {}
                }
                location.reload();
            },
            error: function() {
                alert("Problen in sending reply!")
            }
        });
    }
</script>
