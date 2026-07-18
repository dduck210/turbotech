<?php

namespace App\Http\Controllers;

use App\Models\Product;

/**
 * Ported from `Codemoi\Controller\HomeController` (legacy
 * `src/Controller/HomeController.php`).
 */
class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home', [
            'prohome' => Product::homeLatest()->get(),
            'listTopsp' => Product::featured()->get(),
            'listBestsp' => Product::bestSellers(),
            'metaTitle' => 'Turbotech - Laptop Gaming & PC Chính Hãng, Giá Tốt Nhất Thị Trường',
            'metaDescription' => 'Turbotech - laptop gaming và PC hiệu năng cao chính hãng, cấu hình mạnh mẽ, giá cạnh tranh. Miễn phí vận chuyển toàn quốc, bảo hành chính hãng 12 tháng.',
        ]);
    }
}