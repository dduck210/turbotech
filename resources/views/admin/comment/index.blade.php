@extends('admin.layouts.app')

@section('page-title', 'Bình luận')

@section('content')
<div class="overflow-x-auto rounded-md border border-ink-300 bg-white">
    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-ink-300 text-xs font-semibold uppercase text-ink-500">
                <th class="p-4">Sản phẩm</th><th class="p-4">Người bình luận</th><th class="p-4">Nội dung</th><th class="p-4">Ngày</th><th class="p-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-ink-200">
            @forelse ($comments as $comment)
                <tr>
                    <td class="p-4">{{ $comment->product->name_pro ?? '—' }}</td>
                    <td class="p-4">{{ $comment->full_name ?: $comment->user_name }}</td>
                    <td class="p-4 max-w-md">{{ $comment->content }}</td>
                    <td class="p-4">{{ $comment->comment_date }}</td>
                    <td class="p-4">
                        <form action="{{ route('admin.comments.destroy', $comment->id_cmt) }}" method="post" onsubmit="return confirm('Xóa bình luận này?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="p-4 text-center text-ink-500">Chưa có bình luận nào.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">
    {{ $comments->links() }}
</div>
@endsection
