# Kiến trúc Turbotech (Codemoi) — Backend vs Frontend

Dự án gồm 2 khu vực độc lập: **Client** (trang bán hàng cho khách) và **Admin** (trang quản trị).
Client đã được viết lại theo mô hình MVC gọn gàng (`src/`), còn Admin vẫn giữ phong cách PHP thủ tục
cũ (`admin/`) — cả hai cùng chạy song song, không phụ thuộc nhau.

Webroot thực sự là thư mục **`public/`** — chỉ những gì trong đó mới truy cập được qua trình duyệt
(được chặn ở tầng Apache `.htaccess`). Toàn bộ `src/`, `view/`, `admin/model`, `admin/controller`,
`vendor/`, `.env`, file `.sql` đều nằm "phía sau", chỉ được PHP `include`/`require` nội bộ.

---

## 1. BACKEND — xử lý logic & dữ liệu

### 1.1. Core framework dùng chung (Client)
| File | Vai trò |
|---|---|
| `src/Core/Router.php` | Router theo key `act` trong URL (`?act=product`), map sang Controller/method |
| `src/Core/Controller.php` | Class cha cho mọi Controller — có sẵn `view()` (render giao diện) và `redirect()` |
| `src/Core/Database.php` | Wrapper PDO — các hàm `query()`, `queryOne()`, `queryValue()`, `execute()`, `executeReturnId()` |
| `src/Core/View.php` | Render file `.php` trong `view/` với biến truyền vào |
| `src/Core/Config.php` | Đọc cấu hình (SMTP, tài khoản ngân hàng VietQR) từ file `.env` |

### 1.2. Controller (Client) — nhận request, gọi Model, chọn View
`src/Controller/`: `AccountController`, `AuthController`, `CartController`, `CheckoutController`,
`HomeController`, `PageController`, `PasswordController`, `ProductController`, `QuestionController`

Bảng route hiện có (đăng ký trong `public/index.php`):
```
product, prodetail        -> ProductController        (danh sách / chi tiết sản phẩm)
register, login, logout   -> AuthController            (đăng ký / đăng nhập / đăng xuất)
mk, verification,
changePass                -> PasswordController         (quên mật khẩu, xác thực OTP, đổi mật khẩu)
myaccount, cancelorder     -> AccountController          (trang tài khoản, hủy đơn)
viewcart, edit, addtocart,
removecart                -> CartController              (giỏ hàng)
bill, applycoupon,
removecoupon, pay,
billconfirm, viewbill      -> CheckoutController          (thanh toán, áp mã giảm giá, xác nhận đơn)
question                   -> QuestionController           (hỏi đáp)
introduce, contact         -> PageController                (trang tĩnh)
```

### 1.3. Model (Client) — truy vấn DB, quy tắc nghiệp vụ
`src/Model/`: `Auth`, `Cart`, `Category`, `Comment`, `Coupon`, `Order`, `Payment`, `Product`, `Question`, `User`

- `Payment.php` — tạo URL ảnh QR chuyển khoản VietQR (gọi API ngoài `img.vietqr.io`, không lưu ảnh)
- `Coupon.php` — kiểm tra mã giảm giá hợp lệ (hạn dùng, số lượt, đơn tối thiểu...)
- `Order.php` — tạo/huỷ đơn hàng (bảng `bill` + `cart` = dòng chi tiết đơn)

### 1.4. Gửi email
- `src/Mail/Mailer.php` — gói gọn việc gửi mail
- `email/PHPMailer/` — thư viện PHPMailer (bên thứ ba, vendor), dùng để gửi OTP/xác nhận qua Gmail SMTP

### 1.5. Admin — backend (kiểu thủ tục, chưa refactor sang MVC)
| File | Vai trò |
|---|---|
| `public/admin/index.php` | **Router kiêm Controller** — 1 file switch-case khổng lồ theo `?act=...`, xử lý toàn bộ nghiệp vụ admin (thêm/sửa/xoá sản phẩm, danh mục, mã giảm giá, đơn hàng, người dùng, bình luận, câu hỏi...) |
| `admin/controller/controller.php` | Hàm `render()` — include file view admin + truyền biến |
| `admin/model/pdo.php` | Kết nối PDO riêng cho admin (tách biệt với `src/Core/Database.php` bên client) |
| `admin/model/product.php` | CRUD sản phẩm |
| `admin/model/category.php` | CRUD danh mục |
| `admin/model/coupon.php` | CRUD mã giảm giá |
| `admin/model/bill.php` | Quản lý đơn hàng (duyệt, giao hàng, huỷ) |
| `admin/model/user.php` | Quản lý người dùng, đăng nhập admin |
| `admin/model/comment.php` | Duyệt/xoá bình luận |
| `admin/model/question.php` | Trả lời câu hỏi khách hàng |
| `admin/model/statistics.php` | Số liệu thống kê doanh thu, sản phẩm bán chạy, tồn kho |

### 1.6. Cơ sở dữ liệu
- File dump: `Turbotech.sql` (tạo DB `codemoi2`)
- Các bảng chính đang dùng: `product`, `category`, `user`, `bill`, `cart` (= dòng chi tiết đơn hàng
  đã đặt, KHÔNG phải giỏ hàng tạm — giỏ hàng tạm nằm ở PHP session), `coupons`, `comment`, `question`, `bank`
- Một số bảng còn sót từ thử nghiệm khác, hiện không dùng trong code: `users`, `migrations`,
  `failed_jobs`, `password_resets`, `personal_access_tokens`, `history_bank` (đặt tên kiểu Laravel,
  có thể an toàn để bỏ qua/dọn sau nếu xác nhận không còn dùng)

---

## 2. FRONTEND — giao diện & tương tác trình duyệt

### 2.1. View (giao diện) phía Client — file PHP trộn HTML
`view/`:
- `head.php`, `header.php`, `footer.php`, `content.php` — khung trang dùng chung
- `product/product-list.php`, `product/product-detail.php` — trang sản phẩm
- `cart/viewcart.php`, `cart/cart-detail-table.php`, `cart/bill.php`, `cart/billconfirm.php` — giỏ hàng, thanh toán
- `user/login.php`, `user/register.php`, `user/myaccount.php`, `user/changePass.php`,
  `user/forgot-password.php`, `user/verification.php` — tài khoản
- `qa/question.php` — hỏi đáp
- `introduce.php`, `contact.php` — trang giới thiệu/liên hệ
- `public/view/qr.php` — trang hiển thị mã QR chuyển khoản
- `public/view/comment-form.php` — form gửi bình luận (AJAX)

### 2.2. View (giao diện) phía Admin
`admin/view/`: `dashboard.php` (biểu đồ thống kê), `login.php`, `nav.php`, `topbar.php`, `header.php`,
`footer.php`, và các cặp `list_*` / `add_*` / `update_*` cho từng nghiệp vụ (`product`, `category`,
`coupon`, `bill`, `user`, `comment`, `question`, `statistic`)

### 2.3. Tài nguyên tĩnh (`public/assets/`)
| Loại | File |
|---|---|
| CSS đã build (Tailwind) | `assets/css/tailwind.css` (client), `assets/css/admin-tailwind.css` (admin) |
| CSS nguồn (trước build) | `src/css/tailwind-input.css`, `src/css/admin-tailwind-input.css` |
| JavaScript | `assets/js/main.js` (tương tác chung), `assets/js/form-validate.js` (validate form),
  `assets/js/address-select.js` (dropdown tỉnh/thành → xã/phường), `assets/js/confirm-dialog.js`
  (hộp thoại xác nhận thay `confirm()` gốc, dùng chung cả client & admin), `assets/js/ajax-mail.js`
  (gửi form liên hệ/bình luận qua AJAX), `assets/js/plugins.min.js` (thư viện ngoài, đã minify) |
| Ảnh | `assets/images/` (banner, slider, logo, ảnh demo), `assets/admin-images/` (logo admin) |
| Dữ liệu tĩnh | `assets/data/vietnam-locations.json` (danh sách tỉnh/thành + xã/phường) |
| Ảnh upload người dùng/admin | `public/uploads/` (avatar), `public/admin/uploads/` (ảnh sản phẩm) |

---

## 3. Tổng hợp nhanh — ai/phần nào làm gì

| Khu vực | Backend (logic + DB) | Frontend (giao diện + JS/CSS) |
|---|---|---|
| **Client** | `src/Core`, `src/Controller`, `src/Model`, `src/Mail`, `email/PHPMailer` | `view/*`, `public/view/*`, `public/assets/css`, `public/assets/js`, `src/css/*` |
| **Admin** | `public/admin/index.php`, `admin/controller`, `admin/model` | `admin/view/*`, `public/assets/css/admin-tailwind.css` |
| **Dùng chung 2 bên** | `public/.htaccess` / `.htaccess` gốc (chặn truy cập trực tiếp), cấu hình `.env`/`Config.php` | `assets/js/confirm-dialog.js` (hộp thoại xác nhận dùng chung) |
