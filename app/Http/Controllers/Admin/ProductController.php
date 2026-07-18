<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Ported from `Codemoi\Controller\Admin\ProductController` (legacy
 * `src/Controller/Admin/ProductController.php`).
 */
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $idcate = (int) $request->query('idcate', 0);
        $query = Product::query();
        if ($idcate > 0) {
            $query->where('idcate', $idcate);
        }

        return view('admin.product.index', [
            'products' => $query->orderByDesc('id_pro')->paginate(15)->withQueryString(),
            'categories' => Category::all(),
            'idcate' => $idcate,
        ]);
    }

    public function create()
    {
        return view('admin.product.create', ['categories' => Category::all()]);
    }

    /**
     * Discount bound 0-100 enforced server-side — a discount over 100
     * would make Product::discounted_price go negative both on-screen and
     * at checkout (client-side only ever capped it at max="100").
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name_pro' => ['required', 'string'],
            'price' => ['required', 'integer', 'min:1'],
            'discount' => ['required', 'integer', 'min:0', 'max:100'],
            'short_des' => ['required', 'string'],
            'detail_des' => ['nullable', 'string'],
            'idcate' => ['required', 'integer'],
            'stock' => ['required', 'integer', 'min:0'],
            'stock_message' => ['nullable', 'string'],
        ], [
            'price.min' => 'Giá nhập không đúng !',
            'discount.max' => 'Giảm giá phải từ 0 đến 100 !',
            'stock.min' => 'Số lượng tồn kho không đúng !',
        ]);

        // detail_des is a NOT NULL column with no default — 'nullable'
        // only means the FIELD is optional in the request, not that the
        // column can actually store a null; default to '' so Eloquent's
        // INSERT always has a value for it. stock_message IS nullable at
        // the DB level, so it's fine left absent/null.
        $data['detail_des'] ??= '';

        return $data;
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $request->validate(['img_pro' => ['required', 'image', 'mimes:jpg,jpeg,png,gif']]);

        $data['img_pro'] = $this->storeUpload($request);
        Product::create($data);

        return redirect()->route('admin.products.index')->with('flash_success', 'Thêm sản phẩm thành công!');
    }

    public function edit(int $id)
    {
        return view('admin.product.edit', [
            'product' => Product::findOrFail($id),
            'categories' => Category::all(),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $data = $this->validated($request);

        if ($request->hasFile('img_pro')) {
            $request->validate(['img_pro' => ['image', 'mimes:jpg,jpeg,png,gif']]);
            $data['img_pro'] = $this->storeUpload($request);
        }

        Product::where('id_pro', $id)->update($data);

        return redirect()->route('admin.products.index')->with('flash_success', 'Cập nhật sản phẩm thành công!');
    }

    /** Blocked by FK if the product is still referenced by an order line or a review. */
    public function destroy(int $id)
    {
        try {
            Product::where('id_pro', $id)->delete();
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('admin.products.index')
                    ->with('flash_error', 'Không thể xoá sản phẩm này vì đã có đơn hàng hoặc đánh giá liên quan.');
            }
            throw $e;
        }

        return redirect()->route('admin.products.index')->with('flash_success', 'Xoá sản phẩm thành công!');
    }

    /**
     * Randomly generated stored filename, never the client's — prevents
     * same-name-overwrite collisions between admins (the exact incident
     * the legacy `ProductController::moveUpload()` fix addressed).
     */
    private function storeUpload(Request $request): string
    {
        $file = $request->file('img_pro');
        $storedName = 'pro_' . Str::random(16) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move(storage_path('app/public/products'), $storedName);

        return $storedName;
    }
}