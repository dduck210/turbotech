<?php

namespace Codemoi\Core;

/**
 * Per-request page title/description/image, set by a controller before
 * rendering and read back by `public/index.php` to fill the placeholder
 * markers `view/head.php` writes into the (fully output-buffered) page —
 * see the comment there for why a placeholder-replace is needed instead of
 * a plain variable: head.php's <title> is echoed before the router even
 * picks a controller, so the real value isn't known yet at that point.
 */
class Seo
{
    private static ?string $title = null;
    private static ?string $description = null;
    private static ?string $image = null;

    public static function setTitle(string $title): void
    {
        self::$title = $title;
    }

    public static function setDescription(string $description): void
    {
        // Search results / social previews truncate well before this, but
        // keep the source text itself reasonable so nothing downstream has
        // to guess where a hard cut lands mid-word.
        self::$description = mb_substr(trim($description), 0, 160);
    }

    public static function setImage(string $imageUrl): void
    {
        self::$image = $imageUrl;
    }

    public static function title(): string
    {
        return self::$title ?? 'Turbotech - Laptop Gaming & PC Chính Hãng, Giá Tốt Nhất';
    }

    public static function description(): string
    {
        return self::$description
            ?? 'Turbotech - laptop gaming và PC hiệu năng cao chính hãng, cấu hình mạnh mẽ, giá cạnh tranh. Miễn phí vận chuyển toàn quốc, bảo hành chính hãng 12 tháng.';
    }

    public static function image(): string
    {
        return self::$image ?? 'assets/images/menu/logo/logo-wordmark-light.svg';
    }
}
