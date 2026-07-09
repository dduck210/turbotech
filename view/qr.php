<?php
session_start();
$_SESSION['check'] = 1;

if (isset($_SESSION['pay'])) {
    $amount = $_SESSION['pay'][1];
    $bill_code = $_SESSION['pay'][2];
    $payment = $_SESSION['pay'][0];
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán chuyển khoản - Turbotech</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 20px;
    }

    .payment-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        max-width: 900px;
        width: 100%;
    }

    .payment-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px 30px;
        text-align: center;
    }

    .payment-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .payment-header p {
        font-size: 1.1rem;
        opacity: 0.95;
        margin: 0;
    }

    .payment-content {
        padding: 40px 30px;
    }

    .row-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        align-items: center;
    }

    .qr-section {
        text-align: center;
    }

    .qr-box {
        background: #f8f9fa;
        padding: 30px;
        border-radius: 15px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .qr-box:hover {
        border-color: #667eea;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.15);
    }

    .qr-box img {
        width: 250px;
        height: 250px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .qr-label {
        color: #667eea;
        font-weight: 600;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
    }

    .qr-hint {
        color: #6c757d;
        font-size: 0.9rem;
        margin-top: 15px;
    }

    .bank-info {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .info-item {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        background: #e7f3ff;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
    }

    .info-label {
        font-size: 0.85rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }

    .info-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: #212529;
        word-break: break-all;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .copy-btn {
        background: #667eea;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .copy-btn:hover {
        background: #764ba2;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .copy-btn:active {
        transform: translateY(0);
    }

    .icon-bank {
        color: #667eea;
        font-size: 1.8rem;
        margin-bottom: 10px;
    }

    .amount-highlight {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 12px;
        text-align: center;
    }

    .amount-label {
        font-size: 0.9rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .amount-value {
        font-size: 2.2rem;
        font-weight: 700;
    }

    .payment-footer {
        background: #f8f9fa;
        padding: 30px;
        border-top: 1px solid #e9ecef;
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-success {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .btn-secondary-custom {
        background: #e9ecef;
        color: #212529;
    }

    .btn-secondary-custom:hover {
        background: #dee2e6;
        color: #212529;
    }

    .success-badge {
        display: inline-block;
        background: #28a745;
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-top: 15px;
    }

    .note-box {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 10px;
        padding: 15px;
        margin-top: 20px;
        color: #856404;
    }

    .note-box i {
        margin-right: 10px;
        color: #ff9800;
    }

    @media (max-width: 768px) {
        .row-content {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .payment-header h1 {
            font-size: 2rem;
        }

        .qr-box img {
            width: 200px;
            height: 200px;
        }

        .payment-content {
            padding: 25px 20px;
        }

        .payment-footer {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }
    }

    .copy-feedback {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
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

<body>
    <div class="payment-container">
        <!-- Header -->
        <div class="payment-header">
            <h1><i class="fas fa-university"></i> Thanh Toán Chuyển Khoản</h1>
            <p>Vui lòng chuyển khoản theo thông tin bên dưới</p>
        </div>

        <!-- Content -->
        <div class="payment-content">
            <div class="row-content">
                <!-- QR Code Section -->
                <div class="qr-section">
                    <div class="qr-box">
                        <div class="qr-label">
                            <i class="fas fa-qrcode"></i> Mã QR Chuyển Khoản
                        </div>
                        <img src="../src/image/qr_code/qrne.jpg" alt="QR Code Chuyển Khoản" />
                        <div class="qr-hint">
                            <i class="fas fa-mobile-alt"></i> Quét mã QR bằng ứng dụng ngân hàng
                        </div>
                    </div>
                </div>

                <!-- Bank Info Section -->
                <div class="bank-info">
                    <!-- Bank Name -->
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-university"></i> Ngân Hàng</span>
                        <div class="info-value">
                            <span>BIDV (Ngân hàng Đầu tư và Phát triển Việt Nam)</span>
                        </div>
                    </div>

                    <!-- Account Number -->
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-credit-card"></i> Số Tài Khoản</span>
                        <div class="info-value">
                            <span id="accountNumber">1234567890</span>
                            <button class="copy-btn" onclick="copyToClipboard('accountNumber')">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </div>
                    </div>

                    <!-- Account Holder -->
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-user"></i> Chủ Tài Khoản</span>
                        <div class="info-value">
                            <span>TURBOTECH VIETNAM</span>
                        </div>
                    </div>

                    <!-- Transfer Content -->
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-comment"></i> Nội Dung Chuyển</span>
                        <div class="info-value">
                            <span id="transferContent">UTP-<?= $bill_code ?></span>
                            <button class="copy-btn" onclick="copyToClipboard('transferContent')">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="amount-highlight">
                        <div class="amount-label"><i class="fas fa-money-bill-wave"></i> Số Tiền Cần Thanh Toán</div>
                        <div class="amount-value" id="amountValue"><?= number_format($amount) ?>₫</div>
                    </div>

                    <!-- Note -->
                    <div class="note-box">
                        <i class="fas fa-info-circle"></i>
                        <strong>Lưu ý:</strong> Hãy nhập chính xác nội dung chuyển khoản để xác nhận đơn hàng của bạn
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="payment-footer">
            <a href="../index.php?act=billconfirm" class="btn-action btn-success">
                <i class="fas fa-check-circle"></i> Đã Chuyển Khoản
            </a>
            <a href="../index.php?act=viewcart" class="btn-action btn-secondary-custom">
                <i class="fas fa-arrow-left"></i> Quay Lại Giỏ Hàng
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
            btn.style.background = '#28a745';
            btn.innerHTML = '<i class="fas fa-check"></i> Đã copy!';

            setTimeout(() => {
                btn.style.background = '#667eea';
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>