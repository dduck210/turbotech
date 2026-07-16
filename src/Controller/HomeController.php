<?php

namespace Codemoi\Controller;

use Codemoi\Core\Controller;
use Codemoi\Core\Seo;
use Codemoi\Model\Product;

/**
 * Home page (default route). Ported from `index.php`'s outer `else`/`default`
 * branch (`index.php:445-451`), which rendered `view/content.php` with the
 * three product lists loaded at the top of the old front controller
 * (`index.php:18-28`).
 */
class HomeController extends Controller
{
    public function index(): void
    {
        Seo::setTitle('Turbotech - Laptop Gaming & PC Chính Hãng, Giá Tốt Nhất Thị Trường');
        Seo::setDescription('Turbotech - laptop gaming và PC hiệu năng cao chính hãng, cấu hình mạnh mẽ, giá cạnh tranh. Miễn phí vận chuyển toàn quốc, bảo hành chính hãng 12 tháng.');

        $this->view('content', [
            'prohome' => Product::allHome(),
            'list_topsp' => Product::featured(),
            'list_bestsp' => Product::bestSellers(),
        ]);
    }
}
