@extends('admin.layouts.app')

@section('page-title', 'Sản phẩm')

@section('content')
<div class="mb-6 flex items-center justify-end">
    <a href="{{ route('admin.products.create') }}" class="btn-boutique rounded-md px-4 py-2 text-sm"><i class="fas fa-plus"></i> Thêm sản phẩm</a>
</div>
<div class="overflow-x-auto rounded-md border border-ink-300 bg-white">
    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-ink-300 text-xs font-semibold uppercase text-ink-500">
                <th class="p-4">Ảnh</th><th class="p-4">Tên</th><th class="p-4">Giá</th><th class="p-4">Giảm giá</th><th class="p-4">Tồn kho</th><th class="p-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-ink-200">
            @forelse ($products as $product)
                <tr>
                    <td class="p-4"><img src="{{ asset('storage/products/'.$product->img_pro) }}" class="h-12 w-12 rounded-md object-cover"></td>
                    <td class="p-4">{{ $product->name_pro }}</td>
                    <td class="p-4">{{ number_format($product->price) }}₫</td>
                    <td class="p-4">{{ $product->discount }}%</td>
                    <td class="p-4">{{ $product->stock }}</td>
                    <td class="p-4">
                        <a href="{{ route('admin.products.edit', $product->id_pro) }}" class="text-amber-600 hover:underline">Sửa</a>
                        <form action="{{ route('admin.products.destroy', $product->id_pro) }}" method="post" class="inline" onsubmit="return confirm('Xóa sản phẩm này?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="ml-3 text-red-600 hover:underline">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="p-4 text-center text-ink-500">Chưa có sản phẩm nào.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">
    {{ $products->links() }}
</div>
@endsection
