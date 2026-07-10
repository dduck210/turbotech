<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

use Codemoi\Core\Config;
use Codemoi\Model\Payment;

$_SESSION['check'] = 1;

// Only reachable mid-checkout via CheckoutController's redirect, which
// always sets $_SESSION['pay'] first. Visiting this URL directly (no
// active payment) used to fall through with $amount/$bill_code undefined,
// dumping raw PHP warnings into the markup instead of a real page.
if (!isset($_SESSION['pay'])) {
    header('Location: ../index.php?act=viewcart');
    exit;
}

$amount = $_SESSION['pay'][1];
$bill_code = $_SESSION['pay'][2];
$payment = $_SESSION['pay'][0];
$transferContent = Payment::transferMemo((string) $bill_code);
$qrImageUrl = Payment::vietQrUrl((int) $amount, (string) $bill_code);
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

        /* Entrance animation for the payment card — pure CSS so it plays
           immediately on page load with no JS/FOUC dependency. */
        @keyframes cardEnter {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .payment-card {
            animation: cardEnter 0.45s cubic-bezier(0.16, 1, 0.3, 1);
        }
    </style>
</head>

<body class="min-h-screen bg-linear-to-b from-ink-50 to-ink-100 font-sans text-ink-900 antialiased flex items-center justify-center p-4 sm:p-6">
    <div class="payment-card w-full max-w-3xl overflow-hidden rounded-2xl border border-ink-200 bg-white shadow-xl shadow-ink-900/5">
        <!-- Header -->
        <div class="relative overflow-hidden bg-linear-to-br from-brand-600 to-brand-700 px-6 py-8 text-center text-white">
            <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/10"></div>
            <div class="pointer-events-none absolute -bottom-16 -left-10 h-40 w-40 rounded-full bg-white/5"></div>
            <div class="relative">
                <h1 class="font-heading text-2xl md:text-3xl font-bold">
                    <i class="fas fa-university" aria-hidden="true"></i> Thanh Toán Chuyển Khoản
                </h1>
                <p class="mt-2 text-sm text-brand-50">Vui lòng chuyển khoản theo thông tin bên dưới</p>
                <div class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3.5 py-1.5 text-xs font-semibold tracking-wide backdrop-blur-sm">
                    <i class="fas fa-receipt" aria-hidden="true"></i> Mã đơn hàng UTP-<?= htmlspecialchars($bill_code) ?>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 md:p-10">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 items-start">
                <!-- QR Code Section -->
                <div class="text-center">
                    <div class="rounded-2xl border border-ink-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
                        <div class="mb-4 text-xs font-semibold uppercase tracking-wide text-brand-600">
                            <i class="fas fa-qrcode" aria-hidden="true"></i> Mã QR Chuyển Khoản
                        </div>
                        <div class="mx-auto w-fit rounded-xl bg-ink-50 p-3 ring-1 ring-ink-100">
                            <img src="<?= htmlspecialchars($qrImageUrl ?? '') ?>" alt="QR Code Chuyển Khoản"
                                class="mx-auto h-56 w-56 rounded-lg object-cover" loading="lazy" />
                        </div>
                        <div class="mt-4 flex items-center justify-center gap-1.5 text-sm text-ink-500">
                            <i class="fas fa-mobile-alt" aria-hidden="true"></i> Quét mã QR bằng ứng dụng ngân hàng bất kỳ
                        </div>
                    </div>
                </div>

                <!-- Bank Info Section -->
                <div class="flex flex-col gap-4">
                    <div class="divide-y divide-ink-100 overflow-hidden rounded-2xl border border-ink-200 bg-white shadow-sm">
                        <!-- Bank Name -->
                        <div class="px-4 py-3">
                            <span class="flex items-center gap-1.5 text-xs font-medium uppercase tracking-wide text-ink-400">
                                <i class="fas fa-university w-4 text-center text-brand-500" aria-hidden="true"></i> Ngân hàng
                            </span>
                            <div class="mt-1 font-semibold text-ink-900"><?= htmlspecialchars(strtoupper(Config::bankCode())) ?></div>
                        </div>

                        <!-- Account Number -->
                        <div class="px-4 py-3">
                            <span class="flex items-center gap-1.5 text-xs font-medium uppercase tracking-wide text-ink-400">
                                <i class="fas fa-credit-card w-4 text-center text-brand-500" aria-hidden="true"></i> Số tài khoản
                            </span>
                            <div class="mt-1 flex items-center justify-between gap-2">
                                <span id="accountNumber" class="font-semibold text-ink-900"><?= htmlspecialchars(Config::bankAccountNo()) ?></span>
                                <button type="button" class="copy-btn shrink-0 rounded-md p-1.5 text-ink-400 transition-colors hover:bg-brand-50 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500"
                                    onclick="copyToClipboard('accountNumber')" title="Sao chép" aria-label="Sao chép số tài khoản">
                                    <i class="fas fa-copy" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Account Holder -->
                        <div class="px-4 py-3">
                            <span class="flex items-center gap-1.5 text-xs font-medium uppercase tracking-wide text-ink-400">
                                <i class="fas fa-user w-4 text-center text-brand-500" aria-hidden="true"></i> Chủ tài khoản
                            </span>
                            <div class="mt-1 font-semibold text-ink-900"><?= htmlspecialchars(Config::bankAccountName()) ?></div>
                        </div>

                        <!-- Transfer Content -->
                        <div class="px-4 py-3">
                            <span class="flex items-center gap-1.5 text-xs font-medium uppercase tracking-wide text-ink-400">
                                <i class="fas fa-comment w-4 text-center text-brand-500" aria-hidden="true"></i> Nội dung chuyển khoản
                            </span>
                            <div class="mt-1 flex items-center justify-between gap-2">
                                <span id="transferContent" class="font-semibold text-ink-900"><?= htmlspecialchars($transferContent ?? '') ?></span>
                                <button type="button" class="copy-btn shrink-0 rounded-md p-1.5 text-ink-400 transition-colors hover:bg-brand-50 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500"
                                    onclick="copyToClipboard('transferContent')" title="Sao chép" aria-label="Sao chép nội dung chuyển khoản">
                                    <i class="fas fa-copy" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="rounded-2xl bg-linear-to-br from-brand-600 to-brand-700 p-6 text-center text-white shadow-sm">
                        <div class="mb-2 text-xs font-semibold uppercase tracking-wide text-brand-50">
                            <i class="fas fa-money-bill-wave" aria-hidden="true"></i> Số Tiền Cần Thanh Toán
                        </div>
                        <div class="text-3xl font-bold tracking-tight" id="amountValue"><?= number_format($amount) ?>₫</div>
                    </div>

                    <!-- Note -->
                    <div class="flex items-start gap-2.5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                        <i class="fas fa-info-circle mt-0.5 shrink-0" aria-hidden="true"></i>
                        <span><strong>Lưu ý:</strong> Hãy nhập chính xác nội dung chuyển khoản để xác nhận đơn hàng của bạn</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex flex-wrap justify-center gap-3 border-t border-ink-200 bg-ink-50 p-6">
            <a href="../index.php?act=billconfirm"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-brand-700 hover:shadow-md active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                <i class="fas fa-check-circle" aria-hidden="true"></i> Đã Chuyển Khoản
            </a>
            <a href="../index.php?act=viewcart"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-ink-200 bg-white px-5 py-2.5 text-sm font-semibold text-ink-900 transition-all hover:bg-ink-50 hover:shadow-sm active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-brand-500">
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