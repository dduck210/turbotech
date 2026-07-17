<!DOCTYPE html>
<html lang="vi" class="overflow-x-hidden">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $metaTitle ?? 'Turbotech' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Turbotech - laptop gaming và PC hiệu năng cao chính hãng.' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="overflow-x-hidden bg-ink-50 font-sans text-ink-900 antialiased">
    @if (session('flash_success'))
        <script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:@json(session('flash_success')),showConfirmButton:false,timer:3000}));</script>
    @endif
    @if ($errors->any())
        <script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:@json($errors->first()),showConfirmButton:false,timer:3500}));</script>
    @endif

    @include('layouts.partials.header')

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    <script src="{{ asset('assets/js/boutique-effects.js') }}" defer></script>
</body>

</html>
