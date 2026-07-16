<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Turbotech')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-ink-50 font-sans text-ink-900 antialiased">
    @if (session('flash_success'))
        <script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:@json(session('flash_success')),showConfirmButton:false,timer:3000}));</script>
    @endif
    @if ($errors->any())
        <script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:@json($errors->first()),showConfirmButton:false,timer:3500}));</script>
    @endif

    <header class="border-b border-ink-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="font-heading text-xl font-bold text-brand-600">Turbotech</a>
            <nav class="flex items-center gap-4 text-sm font-medium text-ink-700">
                @auth
                    <a href="{{ route('account.index') }}" class="hover:text-brand-600">{{ auth()->user()->full_name }}</a>
                    @if ((int) auth()->user()->role === 1)
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600">Trang Admin</a>
                    @endif
                    <form action="{{ route('logout') }}" method="post" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-brand-600">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('login.show') }}" class="hover:text-brand-600">Đăng nhập</a>
                    <a href="{{ route('register.show') }}" class="hover:text-brand-600">Đăng ký</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <footer class="mt-16 border-t border-ink-200 py-8 text-center text-sm text-ink-500">
        &copy; {{ date('Y') }} Turbotech
    </footer>
</body>

</html>
