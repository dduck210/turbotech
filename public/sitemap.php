<?php

require __DIR__ . '/../vendor/autoload.php';

use Codemoi\Model\Category;
use Codemoi\Model\Product;

// Dynamic sitemap — content is entirely DB-driven, so a static XML file
// would drift out of date the moment a product/category is added or removed.
// dirname(SCRIPT_NAME) would resolve to the real filesystem path
// (.../public), not what the browser actually requested — this app is
// reached through the root .htaccess rewrite, which forwards e.g.
// /codemoi1/index.php to public/index.php without the browser ever seeing
// a /public/ segment (see the matching comment in public/index.php).
// REQUEST_URI preserves that original, user-facing path.
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = $scheme . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($requestPath), '/');

$staticPages = [
    '/index.php' => '1.0',
    '/index.php?act=product' => '0.9',
    '/index.php?act=introduce' => '0.5',
    '/index.php?act=contact' => '0.5',
    '/index.php?act=question' => '0.4',
    '/index.php?act=login' => '0.3',
    '/index.php?act=register' => '0.3',
];

header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($staticPages as $path => $priority): ?>
    <url>
        <loc><?= htmlspecialchars($base . $path, ENT_XML1 | ENT_QUOTES, 'UTF-8') ?></loc>
        <priority><?= $priority ?></priority>
    </url>
<?php endforeach; ?>
<?php foreach (Category::all() as $category): ?>
    <url>
        <loc><?= htmlspecialchars($base . '/index.php?act=product&idcate=' . (int) $category['id_cate'], ENT_XML1 | ENT_QUOTES, 'UTF-8') ?></loc>
        <priority>0.7</priority>
    </url>
<?php endforeach; ?>
<?php foreach (Product::allIds() as $product): ?>
    <url>
        <loc><?= htmlspecialchars($base . '/index.php?act=prodetail&idpro=' . (int) $product['id_pro'], ENT_XML1 | ENT_QUOTES, 'UTF-8') ?></loc>
        <priority>0.8</priority>
    </url>
<?php endforeach; ?>
</urlset>
