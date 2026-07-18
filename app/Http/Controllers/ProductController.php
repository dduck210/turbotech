<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * Ported from `Codemoi\Controller\ProductController` (legacy
 * `src/Controller/ProductController.php`).
 */
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->query('kyw', ''));
        $idcate = max(0, (int) $request->query('idcate', 0));
        $min = max(0, (int) $request->query('min_price', 0));
        $max = max(0, (int) $request->query('max_price', 0));

        if ($min > 0 && $max > 0 && $min > $max) {
            [$min, $max] = [$max, $min];
        }

        $categoryName = $idcate > 0 ? trim((string) (Category::find($idcate)?->name_cate)) : '';

        if ($keyword !== '') {
            $metaTitle = 'Kết quả tìm kiếm "'.$keyword.'" - Turbotech';
            $metaDescription = 'Kết quả tìm kiếm sản phẩm "'.$keyword.'" tại Turbotech - laptop gaming và PC hiệu năng cao chính hãng.';
        } elseif ($idcate > 0 && $categoryName !== '') {
            $metaTitle = 'Laptop '.$categoryName.' chính hãng, giá tốt - Turbotech';
            $metaDescription = 'Danh sách laptop '.$categoryName.' chính hãng tại Turbotech - cấu hình mạnh mẽ, giá cạnh tranh, bảo hành 12 tháng.';
        } else {
            $metaTitle = 'Tất cả sản phẩm - Turbotech';
            $metaDescription = 'Toàn bộ laptop gaming và PC hiệu năng cao tại Turbotech - lọc theo danh mục, khoảng giá, tìm kiếm theo từ khóa.';
        }

        return view('product.list', [
            'listpro' => Product::search($keyword, $idcate, $min, $max)->withQueryString(),
            'namecate' => $categoryName,
            'listcate' => Category::all(),
            'listTopsp' => Product::featured()->get(),
            'metaTitle' => $metaTitle,
            'metaDescription' => $metaDescription,
        ]);
    }

    /**
     * Renders before incrementing the view counter (session-gated, once
     * per session) — matches the legacy behavior of showing the
     * pre-increment `view` value on the page.
     */
    public function show(Request $request, int $idpro)
    {
        $product = Product::find($idpro);

        if (! $product) {
            return redirect()->route('product.index');
        }

        $similar = $product->similar();
        $comments = Comment::where('id_pro', $idpro)->orderByDesc('id_cmt')->limit(8)->get();

        $user = $request->user();
        $canReview = false;
        $alreadyReviewed = false;
        if ($user) {
            $alreadyReviewed = Comment::where('id_user', $user->id_user)->where('id_pro', $idpro)->exists();
            $canReview = ! $alreadyReviewed && $this->hasDeliveredPurchase($user->id_user, $idpro);
        }

        $view = view('product.detail', [
            'product' => $product,
            'similar' => $similar,
            'comments' => $comments,
            'canReview' => $canReview,
            'alreadyReviewed' => $alreadyReviewed,
            'metaTitle' => $product->name_pro.' - Giá '.number_format($product->price).'đ - Turbotech',
            'metaDescription' => $product->short_des ?: $product->name_pro,
        ]);

        $sessionKey = 'post_'.$idpro;
        if (! $request->session()->has($sessionKey)) {
            $request->session()->put($sessionKey, true);
            $product->increment('view');
        }

        return $view;
    }

    /**
     * Review eligibility gate — only a verified purchaser (a delivered,
     * status=3 order containing this product) who hasn't already reviewed
     * it can submit one. `comment.UNIQUE(id_user, id_pro)` is the DB-level
     * backstop; this is just the friendlier check in front of it.
     */
    public function submitReview(Request $request, int $idpro)
    {
        $user = $request->user();
        $data = $request->validate(['content_cmt' => ['required', 'string', 'min:2']]);

        $alreadyReviewed = Comment::where('id_user', $user->id_user)->where('id_pro', $idpro)->exists();
        if ($alreadyReviewed) {
            return back()->withErrors(['review' => 'Bạn đã đánh giá sản phẩm này rồi !']);
        }

        if (! $this->hasDeliveredPurchase($user->id_user, $idpro)) {
            return back()->withErrors(['review' => 'Bạn cần mua và nhận sản phẩm này để đánh giá !']);
        }

        Comment::create([
            'content' => $data['content_cmt'],
            'id_user' => $user->id_user,
            'user_name' => $user->user_name,
            'full_name' => $user->full_name,
            'id_pro' => $idpro,
            'comment_date' => now()->format('m/d/Y h:i:sa'),
        ]);

        return back()->with('flash_success', 'Cảm ơn bạn đã đánh giá!');
    }

    private function hasDeliveredPurchase(int $idUser, int $idPro): bool
    {
        return \App\Models\OrderItem::where('id_user', $idUser)
            ->where('id_pro', $idPro)
            ->whereHas('order', fn ($q) => $q->where('status', Order::STATUS_DELIVERED))
            ->exists();
    }
}
