<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

/**
 * Ported from `Codemoi\Controller\Admin\CategoryController` (legacy
 * `src/Controller/Admin/CategoryController.php`).
 */
class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.category.index', ['categories' => Category::orderByDesc('id_cate')->get()]);
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name_cate' => ['required', 'string']]);
        Category::create($data);

        return redirect()->route('admin.categories.index')->with('flash_success', 'Thêm danh mục thành công!');
    }

    public function edit(int $id)
    {
        return view('admin.category.edit', ['category' => Category::findOrFail($id)]);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate(['name_cate' => ['required', 'string']]);
        Category::where('id_cate', $id)->update($data);

        return redirect()->route('admin.categories.index')->with('flash_success', 'Cập nhật danh mục thành công!');
    }

    /** Blocked by the `lk_cate_product` FK if any product is still in this category. */
    public function destroy(int $id)
    {
        try {
            Category::where('id_cate', $id)->delete();
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('admin.categories.index')
                    ->with('flash_error', 'Không thể xoá danh mục này vì vẫn còn sản phẩm thuộc danh mục.');
            }
            throw $e;
        }

        return redirect()->route('admin.categories.index')->with('flash_success', 'Xoá danh mục thành công!');
    }
}
