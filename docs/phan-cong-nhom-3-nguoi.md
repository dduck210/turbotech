# Phân công nhóm 3 người — dự án Turbotech (Codemoi)

Nhóm 3 người (có Tuấn Anh). Vì chưa biết thế mạnh riêng của từng người, cách chia dưới đây theo
**"mảng nghiệp vụ dọc"** — mỗi người phụ trách trọn vẹn một nhóm tính năng (cả backend lẫn
frontend của nhóm đó), để ai cũng học được đủ cả hai phía và có thể tự trình bày phần của mình
độc lập khi báo cáo. Cuối file có thêm phương án chia ngang (1 người backend / 1 người frontend /
1 người hạ tầng) nếu nhóm thích cách đó hơn.

**Dự án hiện đã chuyển sang Laravel 13** — đường dẫn file bên dưới đã cập nhật theo cấu trúc mới.

---

## Người 1 — Sản phẩm, Giỏ hàng & Thanh toán (Tuấn Anh)
*Gợi ý Tuấn Anh nhận mảng này vì đã trực tiếp làm phần thanh toán QR VietQR trước đó.*

**Backend:**
- `app/Http/Controllers/ProductController.php`, `CartController.php`, `CheckoutController.php`
- `app/Http/Controllers/Admin/ProductController.php`, `CategoryController.php`, `CouponController.php`
- `app/Models/Product.php`, `Category.php`, `Coupon.php`, `Order.php`, `OrderItem.php`
- `app/Services/CartService.php`, `CouponService.php`, `VietQrService.php`, `OrderCancellationService.php`

**Frontend:**
- `resources/views/product/list.blade.php`, `detail.blade.php`
- `resources/views/cart/view.blade.php`, `bill.blade.php`, `confirmation.blade.php`, `qr.blade.php`
- `resources/views/admin/product/*`, `admin/category/*`, `admin/coupon/*`

**Học được:** CRUD sản phẩm/danh mục, logic giỏ hàng, áp mã giảm giá, quy trình thanh toán, tích hợp
API bên ngoài (VietQR), Eloquent ORM.

---

## Người 2 — Tài khoản, Đăng nhập & Tương tác khách hàng

**Backend:**
- `app/Http/Controllers/AuthController.php`, `AccountController.php`, `PasswordController.php`, `QuestionController.php`
- `app/Http/Requests/LoginRequest.php`, `RegisterRequest.php`
- `app/Models/User.php`, `Comment.php`, `Question.php`
- `app/Http/Controllers/Admin/UserController.php`, `CommentController.php`, `QuestionController.php`

**Frontend:**
- `resources/views/auth/*` (login, register, forgot-password, verification, change-password)
- `resources/views/account/index.blade.php`
- `resources/views/pages/question.blade.php`
- `resources/views/admin/user/*`, `admin/comment/*`, `admin/question/*`

**Học được:** xác thực người dùng (Laravel Auth), gửi email (`Illuminate\Mail`), FormRequest
validate, quản lý người dùng/bình luận phía admin.

---

## Người 3 — Quản trị, Hạ tầng & Bảo mật

**Backend:**
- `bootstrap/app.php`, `routes/web.php` — cấu hình ứng dụng + định tuyến
- `app/Http/Middleware/EnsureUserIsAdmin.php`, `app/Policies/UserPolicy.php`
- `app/Http/Controllers/Admin/BillController.php`, `StatsController.php`, `DashboardController.php`
- `database/migrations/`, `database/seeders/DatabaseSeeder.php`

**Frontend:**
- `resources/views/admin/layouts/*` (khung trang admin: sidebar, topbar)
- `resources/views/admin/dashboard.blade.php`, `admin/bill/*`, `admin/stats/*`
- Build Tailwind: `resources/css/*`, `public/assets/css/*`
- `resources/views/vendor/pagination/tailwind.blade.php` (giao diện phân trang dùng chung)

**Học được:** kiến trúc Laravel (routing, middleware, migration), quản lý đơn hàng/thống kê,
dashboard, cấu hình Tailwind, bảo mật (CSRF, rate limiting, phân quyền admin), CSDL
(`database/migrations/`).

---

## Việc dùng chung cả 3 người (không thuộc riêng ai)
- `database/migrations/` — cấu trúc CSDL, cần cả 3 hiểu chung trước khi tách việc
- `.env` — cấu hình chung (DB, SMTP, ngân hàng)
- `routes/web.php` — bảng định tuyến chung, tránh đè route của nhau khi thêm tính năng mới

---

## Phương án khác: chia theo tầng (backend/frontend/hạ tầng)
Nếu nhóm thích rạch ròi backend riêng — frontend riêng thay vì chia theo tính năng:

| Người | Phụ trách |
|---|---|
| Người 1 | Toàn bộ **backend Client** (`app/Http/Controllers/*.php`, `app/Models`, `app/Services`) |
| Người 2 | Toàn bộ **backend Admin** (`app/Http/Controllers/Admin/*.php`, `app/Policies`) |
| Người 3 | Toàn bộ **frontend** cả 2 bên (`resources/views/*`) + build Tailwind |

Cách này giúp mỗi người đào sâu một tầng, nhưng người 3 sẽ không đụng tới logic nghiệp vụ/CSDL —
phù hợp nếu trong nhóm có bạn mạnh riêng về giao diện/UI và không cần học sâu PHP/SQL.
