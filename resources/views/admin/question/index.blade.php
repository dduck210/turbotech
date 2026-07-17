@extends('admin.layouts.app')

@section('page-title', 'Hỏi đáp')

@section('content')
<div class="overflow-x-auto rounded-md border border-ink-300 bg-white">
    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-ink-300 text-xs font-semibold uppercase text-ink-500">
                <th class="p-4">Người gửi</th><th class="p-4">Email</th><th class="p-4">Nội dung</th><th class="p-4">Trạng thái</th><th class="p-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-ink-200">
            @forelse ($questions as $question)
                <tr>
                    <td class="p-4">{{ $question->name }}</td>
                    <td class="p-4">{{ $question->email }}</td>
                    <td class="p-4 max-w-md">{{ $question->contennt }}</td>
                    <td class="p-4">
                        @if ($question->replied_at)
                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Đã trả lời</span>
                        @else
                            <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">Chưa trả lời</span>
                        @endif
                    </td>
                    <td class="p-4">
                        <a href="{{ route('admin.questions.reply', $question->id_ques) }}" class="text-amber-600 hover:underline">Trả lời</a>
                        <form action="{{ route('admin.questions.destroy', $question->id_ques) }}" method="post" class="inline" onsubmit="return confirm('Xóa câu hỏi này?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="ml-3 text-red-600 hover:underline">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="p-4 text-center text-ink-500">Chưa có câu hỏi nào.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
