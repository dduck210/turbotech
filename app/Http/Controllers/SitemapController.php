<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

/**
 * Ported from the legacy `public/sitemap.php`. Content is entirely
 * DB-driven, so a static XML file would drift out of date the moment a
 * product/category is added or removed.
 */
class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $staticPages = [
            ['url' => route('home'), 'priority' => '1.0'],
            ['url' => route('product.index'), 'priority' => '0.9'],
            ['url' => route('introduce'), 'priority' => '0.5'],
            ['url' => route('contact'), 'priority' => '0.5'],
            ['url' => route('question.index'), 'priority' => '0.4'],
            ['url' => route('login.show'), 'priority' => '0.3'],
            ['url' => route('register.show'), 'priority' => '0.3'],
        ];

        $categoryPages = Category::all()->map(fn(Category $category) => [
            'url' => route('product.index', ['idcate' => $category->id_cate]),
            'priority' => '0.7',
        ]);

        $productPages = Product::all()->map(fn(Product $product) => [
            'url' => route('product.show', ['idpro' => $product->id_pro]),
            'priority' => '0.8',
        ]);

        $urls = collect($staticPages)->concat($categoryPages)->concat($productPages);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($urls as $entry) {
    $xml .= '<url>
        <loc>' . e($entry['url']) . '</loc>
        <priority>' . $entry['priority'] . '</priority>
    </url>' . "\n";
    }
    $xml .= '</urlset>';

return response($xml, 200)->header('Content-Type', 'application/xml; charset=utf-8');
}
}