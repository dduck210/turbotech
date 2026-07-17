@extends('admin.layouts.app')

@section('page-title', 'Thêm sản phẩm')

@section('content')
<form action="{{ route('admin.products.store') }}" method="post" enctype="multipart/form-data" class="max-w-2xl space-y-4 rounded-md border border-ink-300 bg-white p-6 shadow-sm">
    @csrf
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Tên sản phẩm</label>
        <input type="text" name="name_pro" required class="input-boutique">
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Giá</label>
            <input type="number" name="price" required min="1" class="input-boutique">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Giảm giá (%)</label>
            <input type="number" name="discount" value="0" min="0" max="100" class="input-boutique">
        </div>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Danh mục</label>
        <select name="idcate" required class="input-boutique">
            @foreach ($categories as $cate)
                <option value="{{ $cate->id_cate }}">{{ $cate->name_cate }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Tồn kho</label>
        <input type="number" name="stock" value="0" min="0" required class="input-boutique">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Mô tả ngắn</label>
        <textarea name="short_des" required rows="2" class="input-boutique"></textarea>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Mô tả chi tiết</label>
        <textarea name="detail_des" rows="5" class="input-boutique"></textarea>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Ảnh sản phẩm</label>
        <input type="file" name="img_pro" accept=".jpg,.jpeg,.png,.gif" required class="w-full text-sm">
    </div>
    <button type="submit" class="btn-boutique rounded-md px-5 py-2.5 text-sm">Lưu</button>
</form>
@endsection
