# Phân công nhóm 3 người — dự án Turbotech (Codemoi)

Nhóm 3 người (có Tuấn Anh). Vì chưa biết thế mạnh riêng của từng người, cách chia dưới đây theo
**"mảng nghiệp vụ dọc"** — mỗi người phụ trách trọn vẹn một nhóm tính năng (cả backend lẫn
frontend của nhóm đó), để ai cũng học được đủ cả hai phía và có thể tự trình bày phần của mình
độc lập khi báo cáo. Cuối file có thêm phương án chia ngang (1 người backend / 1 người frontend /
1 người hạ tầng) nếu nhóm thích cách đó hơn.

---

## Người 1 — Sản phẩm, Giỏ hàng & Thanh toán (Tuấn Anh)
*Gợi ý Tuấn Anh nhận mảng này vì đã trực tiếp làm phần thanh toán QR VietQR trước đó.*

**Backend:**
- `src/Controller/ProductController.php`, `CartController.php`, `CheckoutController.php`
- `src/Model/Product.php`, `Category.php`, `Cart.php`, `Coupon.php`, `Order.php`, `Payment.php`
- `admin/model/product.php`, `admin/model/category.php`, `admin/model/coupon.php`

**Frontend:**
- `view/product/product-list.php`, `product-detail.php`
- `view/cart/viewcart.php`, `cart-detail-table.php`, `bill.php`, `billconfirm.php`
- `public/view/qr.php` (trang QR chuyển khoản)
- `admin/view/{add,list,update}_product.php`, `{add,list,update}_category.php`, `{add,list,update}_coupon.php`

**Học được:** CRUD sản phẩm/danh mục, logic giỏ hàng, áp mã giảm giá, quy trình thanh toán, tích hợp
API bên ngoài (VietQR).

---

## Người 2 — Tài khoản, Đăng nhập & Tương tác khách hàng

**Backend:**
- `src/Controller/AuthController.php`, `AccountController.php`, `PasswordController.php`, `QuestionController.php`
- `src/Model/Auth.php`, `User.php`, `Comment.php`, `Question.php`
- `src/Mail/Mailer.php`, `email/PHPMailer/*` (gửi email OTP/xác thực)
- `admin/model/user.php`, `admin/model/comment.php`, `admin/model/question.php`

**Frontend:**
- `view/user/login.php`, `register.php`, `myaccount.php`, `changePass.php`, `forgot-password.php`, `verification.php`
- `view/qa/question.php`
- `public/view/comment-form.php`
- `admin/view/list_user.php`, `update_user.php`, `list_comment.php`, `list_question.php`
- `public/assets/js/address-select.js`, `form-validate.js` (dùng nhiều nhất ở form đăng ký/tài khoản)

**Học được:** xác thực người dùng, gửi email (SMTP), validate form, quản lý người dùng/bình luận
phía admin.

---

## Người 3 — Quản trị, Hạ tầng & Bảo mật

**Backend:**
- `src/Core/*` (Router, Controller, Database, View, Config) — nền tảng MVC dùng chung
- `public/index.php`, `public/admin/index.php` (2 điểm vào + định tuyến)
- `admin/model/bill.php`, `admin/model/statistics.php`, `admin/model/pdo.php`
- `admin/controller/controller.php`

**Frontend:**
- `admin/view/dashboard.php` (biểu đồ thống kê), `login.php`, `nav.php`, `topbar.php`, `header.php`, `footer.php`
- `admin/view/list_bill.php`, `update_bill.php`, `list_statistic.php`
- Build Tailwind: `src/css/*`, `public/assets/css/*`
- `public/assets/js/confirm-dialog.js`, `main.js`, `plugins.min.js` (JS dùng chung toàn site)

**Học được:** kiến trúc MVC, định tuyến, quản lý đơn hàng/thống kê, dashboard biểu đồ, cấu hình
Tailwind, bảo mật triển khai (`.htaccess`, tách `public/` webroot), CSDL (`Turbotech.sql`).

---

## Việc dùng chung cả 3 người (không thuộc riêng ai)
- `Turbotech.sql` — cấu trúc CSDL, cần cả 3 hiểu chung trước khi tách việc
- `.env` / `Config.php` — cấu hình chung (SMTP, ngân hàng)
- `.htaccess` (gốc + `public/`) — chặn truy cập trực tiếp ngoài `public/`

---

## Phương án khác: chia theo tầng (backend/frontend/hạ tầng)
Nếu nhóm thích rạch ròi backend riêng — frontend riêng thay vì chia theo tính năng:

| Người | Phụ trách |
|---|---|
| Người 1 | Toàn bộ **backend Client** (`src/Controller`, `src/Model`, `src/Core`, `src/Mail`) |
| Người 2 | Toàn bộ **backend Admin** (`public/admin/index.php`, `admin/model/*`, `admin/controller/*`) |
| Người 3 | Toàn bộ **frontend** cả 2 bên (`view/*`, `admin/view/*`, `public/assets/css`, `public/assets/js`) + build Tailwind |

Cách này giúp mỗi người đào sâu một tầng, nhưng người 3 sẽ không đụng tới logic nghiệp vụ/CSDL —
phù hợp nếu trong nhóm có bạn mạnh riêng về giao diện/UI và không cần học sâu PHP/SQL.
