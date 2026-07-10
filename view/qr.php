<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

use Codemoi\Core\Config;
use Codemoi\Model\Payment;

$_SESSION['check'] = 1;

if (isset($_SESSION['pay'])) {
    $amount = $_SESSION['pay'][1];
    $bill_code = $_SESSION['pay'][2];
    $payment = $_SESSION['pay'][0];
    $transferContent = Payment::transferMemo((string) $bill_code);
    $qrImageUrl = Payment::vietQrUrl((int) $amount, (string) $bill_code);
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán chuyển khoản - Turbotech</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" type="text/css" media="screen" href="../src/css/tailwind.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    /* Needed only because copyToClipboard()/showCopyFeedback() below create
       this toast element dynamically via JS (className set at runtime), so
       it can't be styled with static Tailwind utility classes. */
    .copy-feedback {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #16a34a;
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        animation: slideIn 0.3s ease;
        z-index: 1000;
    }

    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }

        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }

    .copy-feedback.hide {
        animation: slideOut 0.3s ease;
    }
    </style>
</head>

<body class="min-h-screen bg-ink-50 font-sans text-ink-900 antialiased flex items-center justify-center p-4 sm:p-6">
    <div class="w-full max-w-3xl overflow-hidden rounded-2xl border border-ink-200 bg-white shadow-sm">
        <!-- Header -->
        <div class="bg-brand-600 px-6 py-10 text-center text-white">
            <h1 class="font-heading text-2xl md:text-3xl font-bold">
                <i class="fas fa-university" aria-hidden="true"></i> Thanh Toán Chuyển Khoản
            </h1>
            <p class="mt-2 text-sm text-brand-50">Vui lòng chuyển khoản theo thông tin bên dưới</p>
        </div>

        <!-- Content -->
        <div class="p-6 md:p-10">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 items-start">
                <!-- QR Code Section -->
                <div class="text-center">
                    <div class="rounded-xl border border-ink-200 bg-ink-50 p-6">
                        <div class="mb-4 text-xs font-semibold uppercase tracking-wide text-brand-600">
                            <i class="fas fa-qrcode" aria-hidden="true"></i> Mã QR Chuyển Khoản
                        </div>
                        <img src="<?= htmlspecialchars($qrImageUrl ?? '') ?>" alt="QR Code Chuyển Khoản"
                            class="mx-auto h-56 w-56 rounded-lg object-cover" />
                        <div class="mt-4 text-sm text-ink-500">
                            <i class="fas fa-mobile-alt" aria-hidden="true"></i> Quét mã QR bằng ứng dụng ngân hàng
                        </div>
                    </div>
                </div>

                <!-- Bank Info Section -->
                <div class="flex flex-col gap-4">
                    <!-- Bank Name -->
                    <div class="rounded-xl border-l-4 border-brand-600 bg-ink-50 p-4">
                        <span class="mb-2 block text-xs font-semibold uppercase tracking-wide text-ink-500">
                            <i class="fas fa-university" aria-hidden="true"></i> Ngân Hàng
                        </span>
                        <div class="font-semibold text-ink-900"><?= htmlspecialchars(strtoupper(Config::bankCode())) ?></div>
                    </div>

                    <!-- Account Number -->
                    <div class="rounded-xl border-l-4 border-brand-600 bg-ink-50 p-4">
                        <span class="mb-2 block text-xs font-semibold uppercase tracking-wide text-ink-500">
                            <i class="fas fa-credit-card" aria-hidden="true"></i> Số Tài Khoản
                        </span>
                        <div class="flex items-center justify-between gap-2">
                            <span id="accountNumber" class="font-bold text-ink-900"><?= htmlspecialchars(Config::bankAccountNo()) ?></span>
                            <button type="button" class="copy-btn inline-flex items-center gap-1.5 rounded-lg bg-brand-600 px-3 py-2 text-xs font-semibold text-white hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2"
                                onclick="copyToClipboard('accountNumber')">
                                <i class="fas fa-copy" aria-hidden="true"></i> Copy
                            </button>
                        </div>
                    </div>

                    <!-- Account Holder -->
                    <div class="rounded-xl border-l-4 border-brand-600 bg-ink-50 p-4">
                        <span class="mb-2 block text-xs font-semibold uppercase tracking-wide text-ink-500">
                            <i class="fas fa-user" aria-hidden="true"></i> Chủ Tài Khoản
                        </span>
                        <div class="font-semibold text-ink-900"><?= htmlspecialchars(Config::bankAccountName()) ?></div>
                    </div>

                    <!-- Transfer Content -->
                    <div class="rounded-xl border-l-4 border-brand-600 bg-ink-50 p-4">
                        <span class="mb-2 block text-xs font-semibold uppercase tracking-wide text-ink-500">
                            <i class="fas fa-comment" aria-hidden="true"></i> Nội Dung Chuyển
                        </span>
                        <div class="flex items-center justify-between gap-2">
                            <span id="transferContent" class="font-bold text-ink-900"><?= htmlspecialchars($transferContent ?? '') ?></span>
                            <button type="button" class="copy-btn inline-flex items-center gap-1.5 rounded-lg bg-brand-600 px-3 py-2 text-xs font-semibold text-white hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2"
                                onclick="copyToClipboard('transferContent')">
                                <i class="fas fa-copy" aria-hidden="true"></i> Copy
                            </button>
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="rounded-xl bg-brand-600 p-6 text-center text-white">
                        <div class="mb-2 text-xs font-semibold uppercase tracking-wide text-brand-50">
                            <i class="fas fa-money-bill-wave" aria-hidden="true"></i> Số Tiền Cần Thanh Toán
                        </div>
                        <div class="text-2xl md:text-3xl font-bold" id="amountValue"><?= number_format($amount) ?>₫</div>
                    </div>

                    <!-- Note -->
                    <div class="rounded-lg border border-amber-300 bg-amber-50 p-4 text-sm text-amber-800">
                        <i class="fas fa-info-circle" aria-hidden="true"></i>
                        <strong>Lưu ý:</strong> Hãy nhập chính xác nội dung chuyển khoản để xác nhận đơn hàng của bạn
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex flex-wrap justify-center gap-3 border-t border-ink-200 bg-ink-50 p-6">
            <a href="../index.php?act=billconfirm"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                <i class="fas fa-check-circle" aria-hidden="true"></i> Đã Chuyển Khoản
            </a>
            <a href="../index.php?act=viewcart"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-ink-200 bg-white px-5 py-2.5 text-sm font-semibold text-ink-900 hover:bg-ink-50 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500">
                <i class="fas fa-arrow-left" aria-hidden="true"></i> Quay Lại Giỏ Hàng
            </a>
        </div>
    </div>

    <script>
    function copyToClipboard(elementId) {
        const element = document.getElementById(elementId);
        const text = element.textContent.trim();

        // Copy to clipboard
        navigator.clipboard.writeText(text).then(() => {
            // Show feedback
            showCopyFeedback();

            // Add animation
            const btn = event.target.closest('.copy-btn');
            btn.style.background = '#16a34a';
            btn.innerHTML = '<i class="fas fa-check"></i> Đã copy!';

            setTimeout(() => {
                btn.style.background = '';
                btn.innerHTML = '<i class="fas fa-copy"></i> Copy';
            }, 2000);
        }).catch(err => {
            alert('Lỗi: Không thể copy!');
        });
    }

    function showCopyFeedback() {
        const feedback = document.createElement('div');
        feedback.className = 'copy-feedback';
        feedback.innerHTML = '<i class="fas fa-check-circle"></i> Đã copy vào bộ nhớ!';
        document.body.appendChild(feedback);

        setTimeout(() => {
            feedback.classList.add('hide');
            setTimeout(() => feedback.remove(), 300);
        }, 2000);
    }

    // Auto copy bill code on load
    window.addEventListener('load', () => {
        console.log('Trang thanh toán đã tải thành công');
    });
    </script>
</body>

</html>
