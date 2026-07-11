<?php

namespace Codemoi\Controller;

use Codemoi\Core\Controller;
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
