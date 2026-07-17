@extends('admin.layouts.app')

@section('content')
<h1 class="mb-6 font-heading text-2xl font-semibold text-ink-900">Trả lời câu hỏi</h1>
<div class="max-w-xl space-y-4 rounded-md border border-ink-300 bg-white p-6">
    <div>
        <div class="text-xs font-semibold uppercase text-ink-500">Người gửi</div>
        <div class="text-sm text-ink-900">{{ $question->name }} — {{ $question->email }} @if ($question->phone) — {{ $question->phone }} @endif</div>
    </div>
    <div>
        <div class="text-xs font-semibold uppercase text-ink-500">Nội dung câu hỏi</div>
        <div class="whitespace-pre-line text-sm text-ink-900">{{ $question->contennt }}</div>
    </div>

    @if ($question->reply)
        <div>
            <div class="text-xs font-semibold uppercase text-ink-500">Đã trả lời lúc {{ optional($question->replied_at)->format('d/m/Y H:i') }}</div>
            <div class="whitespace-pre-line text-sm text-ink-900">{{ $question->reply }}</div>
        </div>
    @endif

    <form action="{{ route('admin.questions.reply.send', $question->id_ques) }}" method="post" class="space-y-3">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Nội dung trả lời (sẽ gửi qua email)</label>
            <textarea name="reply" rows="5" required class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm">{{ old('reply', $question->reply) }}</textarea>
        </div>
        <button type="submit" class="btn-boutique rounded-md px-5 py-2.5 text-sm font-semibold">Gửi trả lời</button>
    </form>
</div>
@endsection
