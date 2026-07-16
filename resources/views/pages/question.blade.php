@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-lg">
    <h1 class="mb-6 font-heading text-2xl font-semibold text-ink-900">Hỏi đáp</h1>
    <form action="{{ route('question.submit') }}" method="post" class="space-y-4 rounded-md border border-ink-200 bg-white p-6">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Họ và tên</label>
            <input type="text" name="name" required class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Email</label>
            <input type="email" name="email" required class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Số điện thoại</label>
            <input type="text" name="phone" required class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Câu hỏi</label>
            <textarea name="contennt" rows="4" required class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm"></textarea>
        </div>
        <button type="submit" class="btn-boutique rounded-md px-6 py-2.5 text-sm font-semibold">Gửi câu hỏi</button>
    </form>
</div>
@endsection
