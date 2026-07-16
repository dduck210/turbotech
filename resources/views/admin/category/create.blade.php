@extends('admin.layouts.app')

@section('content')
<h1 class="mb-6 font-heading text-2xl font-semibold text-ink-900">Thêm danh mục</h1>
<form action="{{ route('admin.categories.store') }}" method="post" class="max-w-md space-y-4 rounded-md border border-ink-300 bg-white p-6">
    @csrf
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Tên danh mục</label>
        <input type="text" name="name_cate" required class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm">
    </div>
    <button type="submit" class="btn-boutique rounded-md px-5 py-2.5 text-sm font-semibold">Lưu</button>
</form>
@endsection
