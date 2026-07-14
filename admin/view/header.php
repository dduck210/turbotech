<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Turbotech Admin - Quản trị cửa hàng</title>
    <link rel="icon" type="image/svg+xml" href="../assets/images/menu/logo/favicon.svg" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Manrope:wght@600;700;800&family=Playfair+Display:wght@500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Tailwind CSS: precompiled via standalone CLI (src/css/admin-tailwind-input.css),
         replacing the @tailwindcss/browser CDN runtime compiler — faster, no external
         network dependency at page-load time. Rebuild after changing any admin/view/*.php
         class names: tailwindcss.exe -i src/css/admin-tailwind-input.css -o public/assets/css/admin-tailwind.css -m -->
    <link rel="stylesheet" href="../assets/css/admin-tailwind.css" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="//cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
</head>

<body class="bg-ink-50 text-ink-800 font-sans">
    <div class="flex h-screen overflow-hidden">
        <?php include_once "nav.php"; ?>
        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <?php include_once "topbar.php"; ?>
            <!-- Main Scrollable Content -->
            <main class="flex-1 overflow-y-auto bg-ink-100 p-4 md:p-6 lg:p-8">
                <div class="animate-admin-fade-in mx-auto max-w-7xl">