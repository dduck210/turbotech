@extends('admin.layouts.app')

@section('page-title', 'Thêm danh mục')

@section('content')
<form action="{{ route('admin.categories.store') }}" method="post" class="max-w-md space-y-4 rounded-md border border-ink-300 bg-white p-6 shadow-sm">
    @csrf
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Tên danh mục</label>
        <input type="text" name="name_cate" required class="input-boutique">
    </div>
    <button type="submit" class="btn-boutique rounded-md px-5 py-2.5 text-sm">Lưu</button>
</form>
@endsection
