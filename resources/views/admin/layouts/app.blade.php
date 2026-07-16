<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $metaTitle ?? 'Quản trị - Turbotech' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-tailwind.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-ink-100 font-sans text-ink-900 antialiased">
    @if (session('flash_success'))
        <script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:@json(session('flash_success')),showConfirmButton:false,timer:3000}));</script>
    @endif
    @if (session('flash_error'))
        <script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:@json(session('flash_error')),showConfirmButton:false,timer:3500}));</script>
    @endif
    @if ($errors->any())
        <script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:@json($errors->first()),showConfirmButton:false,timer:3500}));</script>
    @endif

    <div class="flex min-h-screen">
        <aside class="w-56 shrink-0 border-r border-ink-300 bg-white p-4">
            <a href="{{ route('admin.dashboard') }}" class="mb-6 block font-heading text-lg font-bold text-brand-600">Turbotech Admin</a>
            <nav class="space-y-1 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="block rounded-md px-3 py-2 text-ink-700 hover:bg-ink-100">Dashboard</a>
                <a href="{{ route('admin.categories.index') }}" class="block rounded-md px-3 py-2 text-ink-700 hover:bg-ink-100">Danh mục</a>
                <a href="{{ route('admin.products.index') }}" class="block rounded-md px-3 py-2 text-ink-700 hover:bg-ink-100">Sản phẩm</a>
                <a href="{{ route('admin.orders.index') }}" class="block rounded-md px-3 py-2 text-ink-700 hover:bg-ink-100">Đơn hàng</a>
                <a href="{{ route('admin.users.index') }}" class="block rounded-md px-3 py-2 text-ink-700 hover:bg-ink-100">Người dùng</a>
                <a href="{{ route('admin.coupons.index') }}" class="block rounded-md px-3 py-2 text-ink-700 hover:bg-ink-100">Mã giảm giá</a>
                <a href="{{ route('admin.comments.index') }}" class="block rounded-md px-3 py-2 text-ink-700 hover:bg-ink-100">Bình luận</a>
                <a href="{{ route('admin.questions.index') }}" class="block rounded-md px-3 py-2 text-ink-700 hover:bg-ink-100">Hỏi đáp</a>
                <a href="{{ route('admin.stats.index') }}" class="block rounded-md px-3 py-2 text-ink-700 hover:bg-ink-100">Thống kê</a>
            </nav>
        </aside>

        <div class="flex-1">
            <header class="flex items-center justify-between border-b border-ink-300 bg-white px-6 py-4">
                <div></div>
                <div class="flex items-center gap-4 text-sm">
                    <a href="{{ route('home') }}" class="text-ink-700 hover:text-brand-600">Xem trang web</a>
                    <span class="text-ink-700">Xin chào, {{ auth()->user()->full_name }}</span>
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="text-red-600 hover:underline">Đăng xuất</button>
                    </form>
                </div>
            </header>
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
