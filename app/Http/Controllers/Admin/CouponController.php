<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * Ported from `Codemoi\Controller\Admin\CouponController` (legacy
 * `src/Controller/Admin/CouponController.php`). The legacy controller had
 * no server-side field validation at all (relied entirely on HTML5
 * attributes) — added proper validation here since Laravel makes it easy
 * and there was never a good reason not to.
 */
class CouponController extends Controller
{
    public function index()
    {
        return view('admin.coupon.index', ['coupons' => Coupon::orderByDesc('id_coupon')->get()]);
    }

    public function create()
    {
        return view('admin.coupon.create', ['products' => Product::all()]);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'code' => ['required', 'string'],
            'discount_type' => ['required', 'in:1,2'],
            'discount_value' => ['required', 'integer', 'min:1'],
            'max_discount' => ['nullable', 'integer', 'min:0'],
            'min_order_value' => ['nullable', 'integer', 'min:0'],
            'product_id' => ['nullable', 'integer'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'usage_limit' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:0,1'],
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['max_discount'] ??= 0;
        $data['min_order_value'] ??= 0;
        $data['product_id'] ??= 0;
        $data['usage_limit'] ??= 0;
        $data['used_count'] = 0;

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')->with('flash_success', 'Thêm mã giảm giá thành công!');
    }

    public function edit(int $id)
    {
        return view('admin.coupon.edit', ['coupon' => Coupon::findOrFail($id), 'products' => Product::all()]);
    }

    public function update(Request $request, int $id)
    {
        $data = $this->validated($request);
        $data['max_discount'] ??= 0;
        $data['min_order_value'] ??= 0;
        $data['product_id'] ??= 0;
        $data['usage_limit'] ??= 0;

        Coupon::where('id_coupon', $id)->update($data);

        return redirect()->route('admin.coupons.index')->with('flash_success', 'Cập nhật mã giảm giá thành công!');
    }

    public function destroy(int $id)
    {
        Coupon::where('id_coupon', $id)->delete();

        return redirect()->route('admin.coupons.index')->with('flash_success', 'Xoá mã giảm giá thành công!');
    }
}
