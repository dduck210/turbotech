@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-lg">
    <span class="eyebrow justify-center">Bạn cần hỗ trợ?</span>
    <h1 class="mb-6 mt-2 text-center font-heading text-2xl font-semibold text-ink-900">Hỏi đáp</h1>
    <form action="{{ route('question.submit') }}" method="post" class="space-y-4 rounded-md border border-ink-200 bg-white p-7 shadow-sm">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Họ và tên</label>
            <input type="text" name="name" required class="input-boutique">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Email</label>
            <input type="email" name="email" required class="input-boutique">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Số điện thoại</label>
            <input type="text" name="phone" required class="input-boutique">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Câu hỏi</label>
            <textarea name="contennt" rows="4" required class="input-boutique"></textarea>
        </div>
        <button type="submit" class="btn-boutique w-full rounded-md px-6 py-3 text-sm">Gửi câu hỏi</button>
    </form>
</div>
@endsection
