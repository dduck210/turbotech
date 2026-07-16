<?php

namespace Codemoi\Controller;

use Codemoi\Core\Controller;
use Codemoi\Core\Seo;
use Codemoi\Model\Category;
use Codemoi\Model\Product;

/**
 * Product listing + detail. Ported from `index.php` cases `'product'`
 * (`index.php:39-50`) and `'prodetail'` (`index.php:51-72`).
 */
class ProductController extends Controller
{
    /**
     * Product search/listing. Keeps the exact keyword/category/price-range
     * parsing + min>max swap from `index.php:40-46` (GET-based, with
     * `trim()`/`is_numeric()` guards).
     */
    public function index(): void
    {
        $kyw = isset($_GET['kyw']) ? trim($_GET['kyw']) : "";
        $idcate = (isset($_GET['idcate']) && $_GET['idcate'] > 0) ? (int) $_GET['idcate'] : 0;
        $min = (isset($_GET['min_price']) && is_numeric($_GET['min_price']) && $_GET['min_price'] > 0) ? (int) $_GET['min_price'] : 0;
        $max = (isset($_GET['max_price']) && is_numeric($_GET['max_price']) && $_GET['max_price'] > 0) ? (int) $_GET['max_price'] : 0;

        if ($min > 0 && $max > 0 && $min > $max) {
            [$min, $max] = [$max, $min];
        }

        // Category::name() returns a single space (not '') for both "no
        // idcate given" and "idcate doesn't match any row" — trim() so a
        // stale/invalid idcate falls through to the generic listing title
        // instead of rendering "Laptop   chính hãng..." with the literal
        // space still in it.
        $categoryName = trim(Category::name($idcate));
        if ($kyw !== '') {
            Seo::setTitle('Kết quả tìm kiếm "' . $kyw . '" - Turbotech');
            Seo::setDescription('Kết quả tìm kiếm sản phẩm "' . $kyw . '" tại Turbotech - laptop gaming và PC hiệu năng cao chính hãng.');
        } elseif ($idcate > 0 && $categoryName !== '') {
            Seo::setTitle('Laptop ' . $categoryName . ' chính hãng, giá tốt - Turbotech');
            Seo::setDescription('Danh sách laptop ' . $categoryName . ' chính hãng tại Turbotech - cấu hình mạnh mẽ, giá cạnh tranh, bảo hành 12 tháng.');
        } else {
            Seo::setTitle('Tất cả sản phẩm - Turbotech');
            Seo::setDescription('Toàn bộ laptop gaming và PC hiệu năng cao tại Turbotech - lọc theo danh mục, khoảng giá, tìm kiếm theo từ khóa.');
        }

        $this->view('product/product-list', [
            'listpro' => Product::search($kyw, $idcate, $min, $max),
            'namecate' => Category::name($idcate),
            'listcate' => Category::all(),
            'list_topsp' => Product::featured(),
        ]);
    }

    /**
     * Product detail page. Renders first, then increments the view counter
     * once per session (session key `'post_' . $id_pro`), matching
     * `index.php:59-68` — the render must happen before the counter update
     * since the template shows the pre-increment `view` value.
     *
     * Falls back to the product listing when `idpro` is missing/invalid,
     * matching `index.php:69-71`.
     */
    public function detail(): void
    {
        if (!isset($_GET['idpro']) || $_GET['idpro'] <= 0) {
            $this->view('product/product-list', [
                'listpro' => [],
                'namecate' => Category::name(0),
                'listcate' => Category::all(),
                'list_topsp' => Product::featured(),
            ]);
            return;
        }

        $id_pro = $_GET['idpro'];
        $one_pro = Product::one($id_pro);
        $idcate = $one_pro['idcate'] ?? 0;
        $similar_pro = Product::similar($id_pro, $idcate);

        if (is_array($one_pro)) {
            Seo::setTitle($one_pro['name_pro'] . ' - Giá ' . number_format((float) $one_pro['price']) . 'đ - Turbotech');
            Seo::setDescription($one_pro['short_des'] ?? $one_pro['name_pro']);
            if (!empty($one_pro['img_pro'])) {
                Seo::setImage('admin/uploads/' . $one_pro['img_pro']);
            }
        }

        $this->view('product/product-detail', [
            'one_pro' => $one_pro,
            'similar_pro' => $similar_pro,
        ]);

        $seekey = 'post_' . $id_pro;
        if (empty($_SESSION[$seekey])) {
            $_SESSION[$seekey] = "1";
            Product::incrementView($id_pro);
        }
    }
}
