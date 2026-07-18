# HƯỚNG DẪN SỬ DỤNG WEBSITE & SOURCE CODE — TURBOTECH (CODEMOI)

Tài liệu này gồm 3 phần:
- **Phần A** — Hướng dẫn sử dụng website dành cho khách hàng
- **Phần B** — Hướng dẫn sử dụng trang quản trị (Admin)
- **Phần C** — Hướng dẫn cài đặt, vận hành và tìm hiểu source code (dành cho lập trình viên)

Dự án hiện chạy trên **Laravel 13** (PHP 8.4) — toàn bộ nội dung dưới đây đã kiểm tra lại trực tiếp
trên source code hiện tại (`app/`, `resources/views/`, `routes/`, `database/migrations/`), không
suy đoán từ phiên bản cũ.

---

# PHẦN A — HƯỚNG DẪN SỬ DỤNG (KHÁCH HÀNG)

## A.1. Đăng ký tài khoản
Vào trang **Đăng ký** (`/register`). Điền:
- Tên đăng nhập, Họ tên, Email hợp lệ, Mật khẩu (tối thiểu 6 ký tự), Giới tính
- Địa chỉ, Số điện thoại Việt Nam hợp lệ (`0xxxxxxxxx` hoặc `+84xxxxxxxxx`)

Hệ thống kiểm tra trùng **tên đăng nhập / email** với tài khoản đã có ở cả 2 lớp: validate phía
trình duyệt và validate lại phía server (không thể bỏ qua bằng cách tắt JavaScript hay gửi request
trực tiếp). Đăng ký thành công sẽ tự động đăng nhập.

## A.2. Đăng nhập / Đăng xuất
- **Đăng nhập** (`/login`): nhập tên đăng nhập + mật khẩu. Giới hạn **5 lần thử/phút** để chống dò
  mật khẩu tự động (brute-force).
- **Đăng xuất** (`/logout`).

## A.3. Quên mật khẩu
1. Vào **Quên mật khẩu** (`/forgot-password`), nhập email đã đăng ký.
2. Hệ thống gửi **mã 6 số** (sinh bằng hàm ngẫu nhiên an toàn `random_int()`) về email, **hết hạn
   sau 10 phút**.
3. Nhập mã tại trang xác nhận (`/verify-code`) — giới hạn 5 lần thử/phút.
4. Đặt mật khẩu mới tại `/change-password`.

## A.4. Duyệt & xem sản phẩm
- Trang **Sản phẩm** (`/products`) liệt kê theo danh mục, có **phân trang** (12 sản phẩm/trang);
  lọc theo khoảng giá tính trên **giá sau khi giảm** (giá thực khách phải trả), không phải giá gốc.
- **Chi tiết sản phẩm** hiển thị mô tả, giá, giảm giá, tồn kho, và khu vực bình luận/đánh giá.
- **Bình luận sản phẩm**: chỉ khách hàng đã đặt và **đã nhận hàng** (đơn ở trạng thái "Đã giao") với
  sản phẩm đó mới được viết — kiểm tra lại ở server, và mỗi khách chỉ được đánh giá 1 lần/sản phẩm
  (chặn cả ở tầng ứng dụng lẫn ràng buộc UNIQUE trong CSDL).

## A.5. Giỏ hàng
- **Thêm vào giỏ** tại trang sản phẩm; **Xem giỏ hàng** (`/cart`): sửa số lượng, xóa sản phẩm.
- Số lượng được kiểm tra tồn kho thực tế; không cho sửa thành số lượng âm.

## A.6. Thanh toán
Trang **Thanh toán** (`/checkout`):
1. Nhập thông tin người nhận.
2. **Áp mã giảm giá** (nếu có) qua AJAX — kiểm tra hạn dùng/số lượt/đơn tối thiểu ngay lúc nhập,
   số tiền giảm thực tế luôn tính lại ở server lúc xác nhận đơn.
3. Chọn **COD** hoặc **Chuyển khoản ngân hàng** (quét mã QR VietQR đã điền sẵn số tiền).
4. Xác nhận đặt hàng — hệ thống kiểm tra lại tồn kho lần cuối, tạo đơn (mã đơn 5 số duy nhất,
   không trùng), gửi email xác nhận (nếu email lỗi, đơn vẫn được tạo bình thường), trừ tồn kho.

> Phải **đăng nhập** mới đặt hàng được.

## A.7. Theo dõi & hủy đơn hàng
Vào **Tài khoản của tôi** (`/account`) → tab Đơn hàng: xem trạng thái *Mới → Đang xử lý → Đang
giao → Đã giao*, hoặc *Đã hủy*. Chỉ tự hủy được khi đơn còn ở trạng thái **"Mới"**.

## A.8. Tài khoản cá nhân
Tại `/account`: cập nhật thông tin, ảnh đại diện, đổi mật khẩu.

## A.9. Hỏi đáp & Liên hệ
**Hỏi đáp** (`/question`), **Giới thiệu** (`/introduce`), **Liên hệ** (`/contact`).

---

# PHẦN B — HƯỚNG DẪN SỬ DỤNG TRANG QUẢN TRỊ (ADMIN)

Truy cập: `http://localhost/codemoi1/admin/dashboard` (yêu cầu đăng nhập tài khoản có `role = 1`).

## B.1. Đăng nhập quản trị
Dùng chung trang đăng nhập với khách hàng (`/login`) — hệ thống tự chuyển vào trang quản trị nếu
tài khoản có quyền admin.

## B.2. Dashboard
Số liệu tổng quan, đơn hàng gần đây, sản phẩm sắp hết hàng.

## B.3. Quản lý sản phẩm (có phân trang, 15 dòng/trang)
- Thêm/sửa: tên, giá, giảm giá (0–100%, kiểm tra ở server), ảnh (validate đúng định dạng ảnh thật,
  tên file lưu ngẫu nhiên để tránh trùng/ghi đè giữa các lần upload), mô tả, danh mục, tồn kho.
- **Xóa sản phẩm**: chỉ xóa được sản phẩm chưa từng nằm trong đơn hàng nào — nếu đã có người đặt,
  hệ thống từ chối và báo lỗi rõ ràng thay vì làm hỏng dữ liệu lịch sử đơn hàng.

## B.4. Quản lý danh mục
Thêm/sửa/xóa danh mục sản phẩm.

## B.5. Quản lý mã giảm giá (có phân trang)
Loại giảm (%/số tiền cố định), đơn tối thiểu, giới hạn sản phẩm, thời hạn, số lượt dùng tối đa,
bật/tắt. Sửa mã trùng với mã đã có sẽ báo lỗi rõ ràng (không còn crash trang).

## B.6. Quản lý đơn hàng (có phân trang, lọc theo trạng thái/từ khóa/khoảng ngày)
Chuyển trạng thái theo đúng luồng một chiều (Mới → Đang xử lý → Đang giao → Đã giao, hoặc hủy ở 2
bước đầu) — không cho phép chuyển ngược hay hủy đơn đã giao, tránh hoàn kho nhầm cho hàng đã giao.

## B.7. Quản lý người dùng (có phân trang)
Xem danh sách, sửa thông tin, xóa tài khoản — không cho xóa/hạ quyền **admin cuối cùng** của hệ
thống (tránh khóa quyền truy cập admin vĩnh viễn).

## B.8. Quản lý bình luận (có phân trang)
Xem/xóa bình luận sản phẩm.

## B.9. Quản lý câu hỏi (có phân trang)
Trả lời câu hỏi khách hàng gửi — câu trả lời được **gửi qua email** tới địa chỉ khách đã nhập (vì
người hỏi không cần tài khoản để gửi câu hỏi).

## B.10. Thống kê chi tiết
Doanh thu theo khoảng ngày, sản phẩm bán chạy nhất, tồn kho — **loại trừ đơn đã hủy** khỏi mọi số
liệu doanh thu.

---

# PHẦN C — HƯỚNG DẪN CÀI ĐẶT & TÌM HIỂU SOURCE CODE (DEV)

## C.1. Yêu cầu môi trường
- **XAMPP** (Apache + PHP 8.4 + MySQL/MariaDB), **Composer**.

## C.2. Cài đặt lần đầu
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```
- `migrate --seed` dựng toàn bộ schema từ `database/migrations/` và tạo **1 tài khoản admin mặc
  định** (`admin` / `password`) — **đổi mật khẩu này ngay** nếu triển khai thật.
- Điền `.env`: `DB_*` (MySQL), `MAIL_*` (SMTP, dùng cho email OTP và trả lời câu hỏi), `BANK_*`
  (thông tin tài khoản hiển thị trên QR VietQR).

## C.3. Cấu hình Apache — không cần cấu hình gì thêm
Webroot thực sự là `public/`, nhưng dự án **không có vhost riêng** — máy chạy chung `htdocs/` của
XAMPP. `.htaccess` ở gốc dự án rewrite mọi request vào `public/index.php`; file này tự sửa lại
`SCRIPT_NAME` trước khi Laravel xử lý request (cần thiết vì request bị rewrite qua 1 thư mục con,
nếu không sửa thì Laravel sẽ nhận sai base path). Nếu sau này deploy lên server có vhost riêng trỏ
thẳng vào `public/`, phần sửa `SCRIPT_NAME` này tự động vô hại (no-op).

## C.4. Cấu trúc thư mục & vai trò từng phần
Xem chi tiết đầy đủ tại **`docs/kien-truc-backend-frontend.md`**. Tóm tắt nhanh:

| Thư mục | Vai trò |
|---|---|
| `public/` | Webroot — điểm vào duy nhất qua HTTP (`index.php`) |
| `app/Http/Controllers` | Logic xử lý request (Client + `Admin/`) |
| `app/Models` | Eloquent ORM — ánh xạ vào schema DB hiện có |
| `app/Services` | Nghiệp vụ tái sử dụng (coupon, giỏ hàng, hủy đơn, VietQR) |
| `resources/views` | Giao diện Blade template |
| `routes/web.php` | Bảng định tuyến |
| `database/migrations` | Schema CSDL — nguồn xác thực duy nhất |
| `storage/app/public` | Ảnh upload (sản phẩm, avatar) |

## C.5. Luồng xử lý request
`public/index.php` → Laravel Router (`routes/web.php`) → Middleware (`auth`, `admin`, `throttle`...)
→ Controller → Model/Service → Blade View.

## C.6. Build lại giao diện (Tailwind CSS)
Không dùng Node/npm — build bằng binary CLI độc lập:
```bash
./tailwindcss.exe -i resources/css/tailwind-input.css -o public/assets/css/tailwind.css --minify
./tailwindcss.exe -i resources/css/admin-tailwind-input.css -o public/assets/css/admin-tailwind.css --minify
```

## C.7. Chạy test
```bash
php artisan test
```

## C.8. Những điểm cần lưu ý khi phát triển tiếp
- Bảng `cart` thực chất là **dòng chi tiết đơn hàng đã đặt** (Model `OrderItem`), KHÔNG phải giỏ
  hàng tạm — giỏ hàng đang mua sắm lưu ở session (`app/Services/CartService.php`), chỉ ghi vào
  bảng `cart` lúc đặt hàng xong.
- Không có cột "ẩn/hiện sản phẩm" (soft delete) — muốn ngừng bán sản phẩm đã có đơn, hiện chỉ có
  thể đặt tồn kho = 0.
- `bill.phone` là kiểu số nguyên (mất số 0 đầu nếu convert qua lại) và `bill.status_pay` là kiểu
  chuỗi — giữ nguyên từ schema gốc để không phá vỡ dữ liệu đơn hàng cũ, không phải lỗi thiết kế mới.

## C.9. Triển khai (Deploy)
Với hosting PHP truyền thống (VPS, shared hosting), chỉ cần lặp lại bước C.3 (trỏ document root
vào `public/` nếu có vhost riêng) là chạy được. Nền tảng serverless (Vercel) không tương thích trực
tiếp — không có ổ đĩa bền vững cho ảnh upload, không host MySQL, cần tái cấu trúc trước khi deploy.
