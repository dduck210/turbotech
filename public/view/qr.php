<?php
session_start();
require __DIR__ . '/../../vendor/autoload.php';

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
    <link rel="stylesheet" type="text/css" media="screen" href="../assets/css/tailwind.css" />
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

        .copy-feedback.error {
            background: #dc2626;
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
    <div class="payment-card w-full max-w-3xl overflow-hidden rounded-3xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl shadow-2xl shadow-ink-900/10">
        <!-- Header -->
        <div class="relative overflow-hidden bg-linear-to-br from-brand-600 via-brand-700 to-indigo-800 px-6 py-9 text-center text-white">
            <div class="pointer-events-none absolute inset-0 opacity-[0.15]"
                style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 18px 18px;"></div>
            <div class="pointer-events-none absolute -right-12 -top-12 h-44 w-44 rounded-full bg-white/10"></div>
            <div class="pointer-events-none absolute -bottom-20 -left-12 h-44 w-44 rounded-full bg-white/5"></div>
            <div class="relative">
                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 shadow-inner backdrop-blur-sm">
                    <i class="fas fa-university text-xl" aria-hidden="true"></i>
                </div>
                <h1 class="font-heading text-2xl md:text-3xl font-bold tracking-tight">Thanh Toán Chuyển Khoản</h1>
                <p class="mt-2 text-sm text-brand-50/90">Vui lòng chuyển khoản theo thông tin bên dưới</p>
                <div class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3.5 py-1.5 text-xs font-semibold tracking-wide backdrop-blur-sm ring-1 ring-white/20">
                    <i class="fas fa-receipt" aria-hidden="true"></i> Mã đơn hàng UTP-<?= htmlspecialchars($bill_code) ?>
                </div>
            </div>
        </div>

        <!-- Steps -->
        <div class="flex items-center justify-center gap-2.5 border-b border-ink-100 bg-ink-50/60 px-6 py-3.5 text-xs font-semibold">
            <div class="flex items-center gap-1.5 text-emerald-600">
                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100"><i class="fas fa-check text-[10px]" aria-hidden="true"></i></span>
                Đặt hàng
            </div>
            <div class="h-px w-8 bg-ink-200"></div>
            <div class="flex items-center gap-1.5 text-brand-600">
                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-brand-600 text-white">2</span>
                Thanh toán
            </div>
            <div class="h-px w-8 bg-ink-200"></div>
            <div class="flex items-center gap-1.5 text-ink-400">
                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-ink-100">3</span>
                Hoàn tất
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 md:p-10">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 items-start">
                <!-- QR Code Section -->
                <div class="text-center">
                    <div class="rounded-2xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl p-6 shadow-sm transition-shadow hover:shadow-md">
                        <div class="mb-4 text-xs font-semibold uppercase tracking-wide text-brand-600">
                            <i class="fas fa-qrcode" aria-hidden="true"></i> Mã QR Chuyển Khoản
                        </div>
                        <div class="relative mx-auto w-fit rounded-xl bg-ink-50 p-4 ring-1 ring-ink-100">
                            <span class="pointer-events-none absolute -left-1 -top-1 h-6 w-6 rounded-tl-xl border-l-2 border-t-2 border-brand-500"></span>
                            <span class="pointer-events-none absolute -right-1 -top-1 h-6 w-6 rounded-tr-xl border-r-2 border-t-2 border-brand-500"></span>
                            <span class="pointer-events-none absolute -bottom-1 -left-1 h-6 w-6 rounded-bl-xl border-b-2 border-l-2 border-brand-500"></span>
                            <span class="pointer-events-none absolute -bottom-1 -right-1 h-6 w-6 rounded-br-xl border-b-2 border-r-2 border-brand-500"></span>
                            <img src="<?= htmlspecialchars($qrImageUrl ?? '') ?>" alt="QR Code Chuyển Khoản"
                                class="mx-auto h-64 w-auto rounded-lg object-contain" loading="lazy" />
                        </div>
                        <div class="mt-4 flex items-center justify-center gap-1.5 text-sm text-ink-500">
                            <i class="fas fa-mobile-alt" aria-hidden="true"></i> Quét mã QR bằng ứng dụng ngân hàng bất kỳ
                        </div>
                        <div class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                            <i class="fas fa-shield-alt" aria-hidden="true"></i> Chuẩn VietQR · NAPAS 247
                        </div>
                    </div>
                </div>

                <!-- Bank Info Section -->
                <div class="flex flex-col gap-4">
                    <div class="divide-y divide-ink-100 overflow-hidden rounded-2xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl shadow-sm">
                        <!-- Bank Name -->
                        <div class="flex items-center gap-3 px-4 py-3.5">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-600">
                                <i class="fas fa-university text-xs" aria-hidden="true"></i>
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-medium uppercase tracking-wide text-ink-400">Ngân hàng</div>
                                <div class="font-semibold text-ink-900"><?= htmlspecialchars(strtoupper(Config::bankCode())) ?></div>
                            </div>
                        </div>

                        <!-- Account Number -->
                        <div class="flex items-center gap-3 px-4 py-3.5">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-600">
                                <i class="fas fa-credit-card text-xs" aria-hidden="true"></i>
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-medium uppercase tracking-wide text-ink-400">Số tài khoản</div>
                                <div class="flex items-center justify-between gap-2">
                                    <span id="accountNumber" class="font-semibold text-ink-900"><?= htmlspecialchars(Config::bankAccountNo()) ?></span>
                                    <button type="button" class="copy-btn shrink-0 rounded-md p-1.5 text-ink-400 transition-colors hover:bg-brand-50 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500"
                                        onclick="copyToClipboard('accountNumber')" title="Sao chép" aria-label="Sao chép số tài khoản">
                                        <i class="fas fa-copy" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Account Holder -->
                        <div class="flex items-center gap-3 px-4 py-3.5">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-600">
                                <i class="fas fa-user text-xs" aria-hidden="true"></i>
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-medium uppercase tracking-wide text-ink-400">Chủ tài khoản</div>
                                <div class="font-semibold text-ink-900"><?= htmlspecialchars(Config::bankAccountName()) ?></div>
                            </div>
                        </div>

                        <!-- Transfer Content -->
                        <div class="flex items-center gap-3 px-4 py-3.5">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-600">
                                <i class="fas fa-comment text-xs" aria-hidden="true"></i>
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-medium uppercase tracking-wide text-ink-400">Nội dung chuyển khoản</div>
                                <div class="flex items-center justify-between gap-2">
                                    <span id="transferContent" class="font-semibold text-ink-900"><?= htmlspecialchars($transferContent ?? '') ?></span>
                                    <button type="button" class="copy-btn shrink-0 rounded-md p-1.5 text-ink-400 transition-colors hover:bg-brand-50 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500"
                                        onclick="copyToClipboard('transferContent')" title="Sao chép" aria-label="Sao chép nội dung chuyển khoản">
                                        <i class="fas fa-copy" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="relative overflow-hidden rounded-2xl bg-linear-to-br from-brand-600 via-brand-700 to-indigo-800 p-6 text-center text-white shadow-lg shadow-brand-700/20">
                        <div class="pointer-events-none absolute -right-8 -top-8 h-28 w-28 rounded-full bg-white/10"></div>
                        <div class="relative mb-2 text-xs font-semibold uppercase tracking-wide text-brand-50/90">
                            <i class="fas fa-money-bill-wave" aria-hidden="true"></i> Số Tiền Cần Thanh Toán
                        </div>
                        <div class="relative font-heading text-4xl font-extrabold tracking-tight" id="amountValue"><?= number_format($amount) ?>₫</div>
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
                class="group inline-flex items-center justify-center gap-2 rounded-xl bg-linear-to-br from-brand-600 to-brand-700 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-brand-700/20 transition-all hover:shadow-lg hover:shadow-brand-700/25 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                <i class="fas fa-check-circle transition-transform group-hover:scale-110" aria-hidden="true"></i> Đã Chuyển Khoản
            </a>
            <a href="../index.php?act=viewcart"
                class="group inline-flex items-center justify-center gap-2 rounded-xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-6 py-3 text-sm font-semibold text-ink-700 transition-all hover:bg-ink-50 hover:shadow-sm active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-brand-500">
                <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-0.5" aria-hidden="true"></i> Quay Lại Giỏ Hàng
            </a>
        </div>
    </div>

    <script>
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const text = element.textContent.trim();
            const btn = event.target.closest('.copy-btn');

            function onCopySuccess() {
                showCopyFeedback();
                btn.classList.add('text-emerald-600');
                btn.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => {
                    btn.classList.remove('text-emerald-600');
                    btn.innerHTML = '<i class="fas fa-copy"></i>';
                }, 2000);
            }

            function onCopyFailure() {
                showCopyFeedback('Không thể sao chép, vui lòng thử lại!', true);
            }

            // Legacy fallback (temporary off-screen textarea + execCommand)
            // for browsers/contexts where the async Clipboard API is
            // unavailable or its permission is denied — gives copying an
            // actual second chance instead of just swallowing the error.
            function legacyCopy() {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.focus();
                textarea.select();
                let ok = false;
                try {
                    ok = document.execCommand('copy');
                } catch (e) {
                    ok = false;
                }
                document.body.removeChild(textarea);
                if (ok) {
                    onCopySuccess();
                } else {
                    onCopyFailure();
                }
            }

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(onCopySuccess).catch(legacyCopy);
            } else {
                legacyCopy();
            }
        }

        function showCopyFeedback(message, isError) {
            const feedback = document.createElement('div');
            feedback.className = isError ? 'copy-feedback error' : 'copy-feedback';
            const icon = isError ? 'fa-circle-exclamation' : 'fa-check-circle';
            feedback.innerHTML = '<i class="fas ' + icon + '"></i> ' + (message || 'Đã copy vào bộ nhớ!');
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