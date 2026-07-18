# Kiến trúc Turbotech (Codemoi) — Backend vs Frontend

Dự án đã được **chuyển đổi hoàn toàn sang Laravel 13** (PHP 8.4) — không còn kiến trúc PHP thuần tự
viết trước đây. Client và Admin giờ dùng **chung một kiến trúc MVC của Laravel**, khác biệt duy nhất
là Admin nằm dưới tiền tố route `/admin` và middleware `admin` riêng.

Webroot thực sự là thư mục **`public/`** — chỉ `public/index.php` mới là điểm vào duy nhất qua HTTP.
Dự án không có vhost Apache riêng (`DocumentRoot` vẫn trỏ vào `htdocs/` dùng chung của XAMPP), nên
`.htaccess` ở thư mục gốc dự án tự rewrite mọi request vào `public/index.php`.

---

## 1. BACKEND — xử lý logic & dữ liệu

### 1.1. Điểm vào & định tuyến
| File | Vai trò |
|---|---|
| `bootstrap/app.php` | Cấu hình trung tâm: đăng ký `routes/web.php`, alias middleware `admin` → `EnsureUserIsAdmin`, cấu hình exception |
| `routes/web.php` | Toàn bộ route của dự án (route có tên thật, VD: `product.index`, `admin.orders.index`), KHÔNG còn kiểu `?act=` cũ |
| `app/Http/Middleware/EnsureUserIsAdmin.php` | Chặn `/admin/*` nếu user chưa đăng nhập hoặc không có `role = 1` |

### 1.2. Controller — nhận request, gọi Model/Service, chọn View
`app/Http/Controllers/` (phía Client): `AccountController`, `AuthController`, `CartController`,
`CheckoutController`, `HomeController`, `PageController`, `PasswordController`, `ProductController`,
`QuestionController`, `SitemapController`

`app/Http/Controllers/Admin/` (phía Admin — **đã là MVC đầy đủ**, không còn switch-case thủ tục):
`BillController`, `CategoryController`, `CommentController`, `CouponController`,
`DashboardController`, `ProductController`, `QuestionController`, `StatsController`, `UserController`

Bảng route chính (đăng ký trong `routes/web.php`):
```
GET  /                        HomeController              trang chủ
GET  /products                ProductController@index      danh sách sản phẩm (lọc danh mục/giá/từ khóa)
GET  /products/{idpro}        ProductController@show        chi tiết sản phẩm
POST /products/{idpro}/reviews ProductController@submitReview  gửi đánh giá

GET|POST /register, /login    AuthController                đăng ký / đăng nhập
POST /logout                  AuthController@logout          đăng xuất
GET|POST /forgot-password,
/verify-code, /change-password PasswordController            quên mật khẩu, xác thực OTP, đổi mật khẩu

GET  /cart                    CartController@view            xem giỏ hàng
POST /cart/add, /cart/edit    CartController                 thêm/sửa giỏ hàng
GET  /account                 AccountController@index         trang tài khoản
POST /account/orders/cancel   AccountController@cancelOrder    hủy đơn

GET  /checkout                CheckoutController@show          trang thanh toán
POST /checkout/coupon/apply   CheckoutController@applyCoupon   áp mã giảm giá (JSON, AJAX)
POST /checkout/confirm        CheckoutController@confirm       tạo đơn hàng
GET  /checkout/qr             CheckoutController@qr            trang QR chuyển khoản

/admin/dashboard               Admin\DashboardController        tổng quan
/admin/products, /categories,
/admin/coupons                 Route::resource(...)              CRUD (index/create/store/edit/update/destroy)
/admin/orders                  Admin\BillController              quản lý đơn hàng
/admin/users                    Admin\UserController              quản lý người dùng
/admin/comments, /questions     Admin\CommentController, QuestionController
/admin/stats                    Admin\StatsController             thống kê chi tiết
```
Xem đầy đủ: `php artisan route:list`.

### 1.3. Model — Eloquent ORM, ánh xạ vào schema cũ
`app/Models/`: `Category`, `Comment`, `Coupon`, `Order` (bảng `bill`), `OrderItem` (bảng `cart`),
`Product`, `User`, `Question`

Vì schema DB (tên bảng, tên cột, khóa chính) được giữ nguyên từ trước khi có Laravel — không theo
quy ước đặt tên mặc định của Laravel — mỗi Model đều khai báo rõ `protected $table` và
`protected $primaryKey`, và `public $timestamps = false` (không có cột `created_at`/`updated_at`).

- `app/Services/VietQrService.php` — tạo URL ảnh QR chuyển khoản VietQR (gọi API ngoài `img.vietqr.io`)
- `app/Services/CouponService.php` — kiểm tra & áp dụng mã giảm giá, tăng `used_count` trong transaction
- `app/Services/CartService.php` — giỏ hàng (lưu session, KHÔNG phải bảng `cart` trong DB)
- `app/Services/OrderCancellationService.php` — hủy đơn + hoàn kho + hoàn lượt dùng coupon, dùng
  chung cho cả khách tự hủy và admin hủy, tránh trùng logic

### 1.4. Gửi email
Dùng **`Illuminate\Support\Facades\Mail`** (Laravel built-in) qua Gmail SMTP — không còn PHPMailer
vendor riêng. Cấu hình đọc từ `.env` (`MAIL_HOST`, `MAIL_USERNAME`, `MAIL_PASSWORD`...).

### 1.5. Cơ sở dữ liệu
- **`database/migrations/`** (10 file) là nguồn xác thực duy nhất của schema — chạy
  `php artisan migrate --seed` để dựng DB từ đầu (file `Turbotech.sql` dump cũ đã được xóa vì
  không còn cần thiết).
- Các bảng đang dùng: `product`, `category`, `user`, `bill` (= đơn hàng), `cart` (= dòng chi tiết
  đơn hàng ĐÃ ĐẶT, KHÔNG phải giỏ hàng tạm — giỏ hàng tạm lưu ở session, chỉ ghi vào bảng `cart`
  lúc đặt hàng xong), `coupons`, `comment`, `question`, cộng thêm `migrations` (bảng nội bộ của
  Laravel để theo dõi migration nào đã chạy).
- Bảng `bank` (tàn dư từ schema cũ, 0 dòng, không code nào dùng) đã được xóa qua migration —
  thông tin tài khoản ngân hàng cho VietQR giờ đọc từ `.env`, không lưu ở DB.
- Kết nối DB đọc từ **`.env`** qua `config/database.php` chuẩn của Laravel (không còn hard-code
  ở 2 nơi như trước).

---

## 2. FRONTEND — giao diện & tương tác trình duyệt

### 2.1. View (Blade template) — thay thế hoàn toàn `view/` PHP thuần cũ
`resources/views/`:
- `layouts/app.blade.php`, `layouts/partials/{header,footer}.blade.php` — khung trang Client
- `admin/layouts/app.blade.php`, `admin/layouts/partials/{sidebar,topbar}.blade.php` — khung trang Admin
- `product/list.blade.php`, `product/detail.blade.php` — trang sản phẩm
- `cart/view.blade.php`, `cart/bill.blade.php`, `cart/confirmation.blade.php`, `cart/qr.blade.php` — giỏ hàng, thanh toán
- `auth/*.blade.php` — đăng ký, đăng nhập, quên mật khẩu, xác thực OTP, đổi mật khẩu
- `account/index.blade.php` — trang tài khoản cá nhân
- `pages/{question,introduce,contact}.blade.php` — hỏi đáp, giới thiệu, liên hệ
- `admin/{bill,category,comment,coupon,product,question,stats,user}/*.blade.php` — các trang quản trị
- `vendor/pagination/tailwind.blade.php` — giao diện phân trang tùy chỉnh theo theme của site (tự
  động được Laravel dùng cho mọi `->paginate()`, không cần gọi gì thêm)

Blade tự động escape HTML khi in biến bằng `{{ $bien }}` — chống XSS mặc định, không cần gọi
`htmlspecialchars()` thủ công như trước. Chỉ có **một ngoại lệ có chủ đích**: mô tả chi tiết sản
phẩm (`product.detail_des`) dùng `{!! !!}` để cho phép admin định dạng HTML (in đậm, danh sách...)
— chấp nhận được vì trường này chỉ do admin (đã xác thực) nhập, không phải nội dung khách hàng gửi.

### 2.2. Tài nguyên tĩnh (`public/assets/`)
| Loại | File |
|---|---|
| CSS đã build (Tailwind) | `assets/css/tailwind.css` (client), `assets/css/admin-tailwind.css` (admin) |
| CSS nguồn (trước build) | `resources/css/tailwind-input.css`, `resources/css/admin-tailwind-input.css`, `resources/css/partials/*` |
| Ảnh upload sản phẩm/avatar | `storage/app/public/{products,avatars}/`, truy cập qua symlink `public/storage/` |

Build lại CSS bằng `tailwindcss.exe` (binary chạy trực tiếp, không cần Node/npm) — xem lệnh cụ thể
tại `docs/huong-dan-su-dung.md`, mục C.

---

## 3. Tổng hợp nhanh

| Khu vực | Backend (logic + DB) | Frontend (giao diện) |
|---|---|---|
| **Client** | `app/Http/Controllers/*.php`, `app/Models`, `app/Services` | `resources/views/{layouts,product,cart,auth,account,pages}` |
| **Admin** | `app/Http/Controllers/Admin/*.php` | `resources/views/admin/*` |
| **Dùng chung** | `routes/web.php`, `database/migrations/`, `.env`/`config/*.php`, `bootstrap/app.php` | `resources/css/*`, `public/assets/css/*` |
