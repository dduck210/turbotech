@extends('admin.layouts.app')

@section('page-title', 'Danh mục')

@section('content')
<div class="mb-6 flex items-center justify-end">
    <a href="{{ route('admin.categories.create') }}" class="btn-boutique rounded-md px-4 py-2 text-sm"><i class="fas fa-plus"></i> Thêm danh mục</a>
</div>
<div class="overflow-x-auto rounded-md border border-ink-300 bg-white">
    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-ink-300 text-xs font-semibold uppercase text-ink-500">
                <th class="p-4">ID</th><th class="p-4">Tên danh mục</th><th class="p-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-ink-200">
            @foreach ($categories as $cate)
                <tr>
                    <td class="p-4">#{{ $cate->id_cate }}</td>
                    <td class="p-4">{{ $cate->name_cate }}</td>
                    <td class="p-4">
                        <a href="{{ route('admin.categories.edit', $cate->id_cate) }}" class="text-amber-600 hover:underline">Sửa</a>
                        <form action="{{ route('admin.categories.destroy', $cate->id_cate) }}" method="post" class="inline" onsubmit="return confirm('Xóa danh mục này?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="ml-3 text-red-600 hover:underline">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
