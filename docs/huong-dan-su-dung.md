# HƯỚNG DẪN SỬ DỤNG WEBSITE & SOURCE CODE — TURBOTECH (CODEMOI)

Tài liệu này gồm 3 phần:
- **Phần A** — Hướng dẫn sử dụng website dành cho khách hàng
- **Phần B** — Hướng dẫn sử dụng trang quản trị (Admin)
- **Phần C** — Hướng dẫn cài đặt, vận hành và tìm hiểu source code (dành cho lập trình viên)

Toàn bộ nội dung được kiểm tra trực tiếp trên source code hiện tại (`src/`, `admin/`, `view/`, `public/`) để đảm bảo khớp với hành vi thực tế của hệ thống, không suy đoán.

---

# PHẦN A — HƯỚNG DẪN SỬ DỤNG (KHÁCH HÀNG)

## A.1. Đăng ký tài khoản
Vào **Đăng ký** (`?act=register`). Điền:
- Tên đăng nhập (tối thiểu 3 ký tự)
- Họ tên (tối thiểu 2 ký tự)
- Email hợp lệ
- Mật khẩu (tối thiểu 6 ký tự)
- Giới tính
- Địa chỉ: chọn **Tỉnh/Thành phố** trước, sau đó **Xã/Phường** sẽ hiện ra tương ứng (dữ liệu tải từ `assets/data/vietnam-locations.json`), rồi nhập địa chỉ chi tiết
- Số điện thoại Việt Nam hợp lệ (`0xxxxxxxxx` hoặc `+84xxxxxxxxx`)

Hệ thống kiểm tra trùng **tên đăng nhập / email / số điện thoại** với tài khoản đã có — nếu trùng sẽ báo rõ và gợi ý bấm **Quên mật khẩu?** thay vì chỉ báo lỗi chung chung. Đăng ký thành công sẽ chuyển sang trang đăng nhập.

## A.2. Đăng nhập / Đăng xuất
- **Đăng nhập** (`?act=login`): nhập tên đăng nhập + mật khẩu.
- **Đăng xuất** (`?act=logout`): xoá phiên đăng nhập, quay về trang đăng nhập.

## A.3. Quên mật khẩu
1. Vào **Quên mật khẩu** (`?act=mk`), nhập **email hoặc số điện thoại** đã đăng ký.
2. Hệ thống gửi **mã 6 số** về email tài khoản (qua Gmail SMTP, cấu hình ở `.env`).
3. Nhập mã tại trang **Xác nhận mã** (`?act=verification`).
4. Đặt **mật khẩu mới** tại `?act=changePass` (phải nhập lại mật khẩu khớp nhau).

> Lưu ý: mã xác nhận chỉ gửi qua **email**, không hỗ trợ SMS dù nhập số điện thoại để tra tài khoản.

## A.4. Duyệt & xem sản phẩm
- Trang **Sản phẩm** (`?act=product`) liệt kê theo danh mục.
- **Chi tiết sản phẩm** (`?act=prodetail`) hiển thị mô tả, giá, giảm giá, tồn kho, và **khu vực bình luận/đánh giá**.
- **Bình luận sản phẩm**: chỉ khách hàng **đã đặt và đã nhận hàng (đơn ở trạng thái "Đã giao")** với sản phẩm đó mới được viết bình luận — hệ thống kiểm tra lại điều kiện này ở phía server, không chỉ ẩn/hiện form trên giao diện.

## A.5. Giỏ hàng
- **Thêm vào giỏ** ngay tại trang sản phẩm.
- **Xem giỏ hàng** (`?act=viewcart`): sửa số lượng, xoá sản phẩm khỏi giỏ.
- Số lượng thêm vào giỏ được kiểm tra **tồn kho thực tế**, không cho thêm quá số hàng còn lại.

## A.6. Thanh toán
Trang **Thanh toán** (`?act=bill`):
1. Nhập thông tin người nhận: họ tên, địa chỉ (tỉnh/xã/chi tiết), số điện thoại, email.
2. **Áp mã giảm giá** (nếu có): nhập mã, hệ thống kiểm tra hạn dùng/số lượt còn lại/điều kiện đơn tối thiểu/sản phẩm áp dụng ngay lúc nhập — không cần tải lại trang. Mã được lưu theo **phiên đăng nhập**, không thể sửa số tiền giảm bằng cách chỉnh sửa HTML phía trình duyệt vì số tiền thực tính lại ở server lúc xác nhận đơn.
3. Chọn **phương thức thanh toán**:
   - **Thanh toán khi nhận hàng (COD)**
   - **Chuyển khoản ngân hàng** — hệ thống tạo **mã QR VietQR** đã điền sẵn số tiền + nội dung chuyển khoản, khách chỉ cần quét bằng app ngân hàng.
4. Bấm xác nhận đặt hàng — hệ thống **kiểm tra lại tồn kho lần cuối** (đề phòng người khác vừa mua hết trong lúc mình đang thao tác), tạo đơn hàng, gửi email xác nhận, trừ tồn kho, và xoá giỏ hàng.

> Phải **đăng nhập** mới đặt hàng được; nếu chưa đăng nhập hệ thống sẽ yêu cầu đăng nhập trước.

## A.7. Theo dõi & huỷ đơn hàng
Vào **Tài khoản của tôi** (`?act=myaccount`) → tab **Đơn hàng**:
- Xem danh sách đơn đã đặt, trạng thái: *Mới → Đang xử lý → Đang giao → Đã giao*, hoặc *Đã hủy*.
- Chỉ có thể **tự huỷ đơn** khi đơn đang ở trạng thái **"Mới"** (chưa được admin xử lý) — nếu đơn đã chuyển trạng thái khác, hệ thống sẽ từ chối huỷ.

## A.8. Tài khoản cá nhân
Tại `?act=myaccount`, khách hàng có thể:
- Cập nhật họ tên, giới tính, email, địa chỉ, số điện thoại, ảnh đại diện.
- Đổi mật khẩu (yêu cầu nhập lại mật khẩu mới 2 lần khớp nhau).

## A.9. Hỏi đáp & Liên hệ
- **Hỏi đáp** (`?act=question`): gửi câu hỏi cho quản trị viên trả lời.
- **Giới thiệu** (`?act=introduce`) và **Liên hệ** (`?act=contact`): trang thông tin tĩnh về cửa hàng.

---

# PHẦN B — HƯỚNG DẪN SỬ DỤNG TRANG QUẢN TRỊ (ADMIN)

Truy cập: `http://localhost/codemoi1/admin/` (hoặc domain thật + `/admin/`).

## B.1. Đăng nhập quản trị
Đăng nhập bằng tài khoản có quyền admin (cột `role = 1` trong bảng `user`, khác với tài khoản khách hàng thường). Sai tài khoản/mật khẩu sẽ hiện thông báo lỗi dạng toast góc màn hình.

## B.2. Dashboard (Trang tổng quan)
Hiển thị biểu đồ thống kê (dùng Chart.js): số lượng sản phẩm theo danh mục, doanh thu theo ngày/tuần/tháng.

## B.3. Quản lý sản phẩm
- **Danh sách sản phẩm**: lọc theo danh mục, xem tồn kho/lượt xem/giảm giá.
- **Thêm/Sửa sản phẩm**: tên, giá, giảm giá (%), ảnh, mô tả ngắn/chi tiết, danh mục, tồn kho, thông báo tồn kho tuỳ chỉnh.
- **Xoá sản phẩm**: ⚠️ **chỉ xoá được sản phẩm chưa từng nằm trong bất kỳ đơn hàng nào**. Nếu sản phẩm đã có người đặt (dù đơn cũ hay đơn hiện tại), hệ thống sẽ **từ chối xoá và báo lỗi rõ ràng** ("Không thể xoá sản phẩm này vì đã có đơn hàng liên quan.") để tránh làm hỏng dữ liệu lịch sử đơn hàng — đây là hành vi **có chủ đích**, không phải lỗi phần mềm.

## B.4. Quản lý danh mục
Thêm/sửa/xoá danh mục sản phẩm (tên danh mục).

## B.5. Quản lý mã giảm giá
Thêm/sửa/xoá mã giảm giá với các thuộc tính: loại giảm (phần trăm/số tiền cố định), mức giảm tối đa, đơn tối thiểu để áp dụng, giới hạn theo sản phẩm cụ thể (tuỳ chọn), thời gian hiệu lực (từ ngày–đến ngày), số lượt sử dụng tối đa, trạng thái bật/tắt.

## B.6. Quản lý đơn hàng
- **Danh sách đơn hàng**: lọc theo trạng thái (Tất cả / Mới / Đang xử lý / Đang giao / Đã giao / Đã hủy).
- **Chi tiết đơn hàng**: xem đầy đủ sản phẩm, số lượng, người nhận.
- Các thao tác chuyển trạng thái:
  - **Duyệt đơn** → chuyển sang "Đang xử lý"
  - **Giao hàng** → chuyển sang "Đang giao"
  - **Huỷ đơn** → chuyển sang "Đã hủy"
  - Đánh dấu "Đã giao" thực hiện qua form **Cập nhật đơn hàng** (chỉnh trạng thái + trạng thái thanh toán thủ công).

## B.7. Quản lý người dùng
Xem danh sách, sửa thông tin, xoá tài khoản người dùng.

## B.8. Quản lý bình luận
Xem/xoá bình luận sản phẩm của khách hàng (dùng hộp thoại xác nhận Tailwind trước khi xoá, không phải `confirm()` mặc định của trình duyệt).

## B.9. Quản lý câu hỏi (Hỏi đáp)
Xem và trả lời câu hỏi khách hàng gửi từ trang Hỏi đáp.

## B.10. Thống kê chi tiết
Xem doanh thu theo khoảng ngày tuỳ chọn, sản phẩm bán chạy nhất (theo số lượng/doanh thu), tồn kho theo sản phẩm.

---

# PHẦN C — HƯỚNG DẪN CÀI ĐẶT & TÌM HIỂU SOURCE CODE (DEV)

## C.1. Yêu cầu môi trường
- **XAMPP** (Apache + PHP 8.0+ + MariaDB/MySQL) — dự án đang phát triển trên PHP 8.0.30.
- **Composer** (quản lý autoload PSR-4, không có dependency ngoài ngoài PHPMailer đã vendor sẵn).

## C.2. Cài đặt lần đầu
1. Đặt source code vào `C:\xampp\htdocs\codemoi1` (hoặc thư mục bất kỳ trong `htdocs`).
2. Chạy `composer install` tại thư mục gốc để sinh `vendor/`.
3. Tạo database, import `Turbotech.sql` (tạo DB tên `codemoi2` theo cấu hình mặc định).
4. Copy `.env.example` → `.env`, điền:
   - `SMTP_HOST/USERNAME/PASSWORD/PORT/FROM_EMAIL/FROM_NAME` — dùng để gửi email OTP/xác nhận đơn (Gmail App Password, KHÔNG dùng mật khẩu Gmail thường).
   - `BANK_CODE/BANK_ACCOUNT_NO/BANK_ACCOUNT_NAME` — tài khoản nhận tiền hiển thị trên mã QR VietQR (`BANK_CODE` lấy từ danh sách https://api.vietqr.io/v2/banks).
5. Thông tin kết nối DB hiện đang **hard-code** trong 2 nơi (không đọc từ `.env`):
   - `src/Core/Config.php` (dùng cho phần Client MVC mới) — mặc định `host=localhost, db=codemoi2, user=root, pass=(rỗng)`.
   - `admin/model/pdo.php` (dùng riêng cho Admin, phong cách thủ tục cũ) — mặc định `host=127.0.0.1, db=codemoi2`.
   Nếu đổi thông tin DB, phải sửa **cả hai file** để client và admin cùng trỏ đúng.

## C.3. Cấu hình Apache — bắt buộc trỏ vào `public/`
Webroot thực sự là thư mục **`public/`**, KHÔNG phải thư mục gốc dự án. Thêm vào
`C:\xampp\apache\conf\extra\httpd-vhosts.conf`:
```apache
Alias /codemoi1 "C:/xampp/htdocs/codemoi1/public"
<Directory "C:/xampp/htdocs/codemoi1/public">
    Options -Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```
Khởi động lại Apache, truy cập `http://localhost/codemoi1/`.

Nếu trỏ document root thẳng vào thư mục gốc dự án (không qua `public/`) — dù là do quên cấu hình
hay do giải nén source code ở máy khác — file `.htaccess` ở gốc dự án sẽ **chặn truy cập (403)**
thay vì để lộ source code/CSDL ra ngoài; đây là lớp an toàn dự phòng, không phải cách chạy đúng.

Test nhanh không cần sửa Apache: `php -S localhost:8000 -t public` rồi vào `http://localhost:8000/`.

## C.4. Cấu trúc thư mục & vai trò từng phần
Xem chi tiết đầy đủ (bảng route, danh sách file backend/frontend) tại:
**`docs/kien-truc-backend-frontend.md`**. Tóm tắt nhanh:

| Thư mục | Vai trò |
|---|---|
| `public/` | Webroot — điểm vào duy nhất truy cập được qua HTTP |
| `src/Core` | Router, Controller cha, Database (PDO), View, Config — nền tảng MVC |
| `src/Controller`, `src/Model` | Logic nghiệp vụ phía Client (MVC hiện đại) |
| `src/Mail`, `email/PHPMailer` | Gửi email (PHPMailer qua Gmail SMTP) |
| `view/` | Giao diện Client (PHP trộn HTML) |
| `admin/model`, `admin/controller` | Logic nghiệp vụ Admin (thủ tục, chưa MVC hoá) |
| `admin/view` | Giao diện Admin |
| `public/assets` | CSS đã build (Tailwind), JS, ảnh, dữ liệu tĩnh |
| `src/css` | CSS nguồn Tailwind (trước khi build) |

## C.5. Luồng xử lý request
- **Client**: `public/index.php` → đọc `?act=...` → `Codemoi\Core\Router` → Controller tương ứng
  (`src/Controller/*`) → gọi Model (`src/Model/*`) → render View (`view/*`).
- **Admin**: `public/admin/index.php` → khối `switch ($_GET['act'])` khổng lồ xử lý trực tiếp trong
  1 file → gọi hàm trong `admin/model/*.php` → `render()` (định nghĩa ở `admin/controller/controller.php`)
  include file `admin/view/*.php`.

## C.6. Build lại giao diện (Tailwind CSS)
Khi sửa class Tailwind trong `view/*` hoặc `admin/view/*`, phải build lại CSS (không tự động):
```bash
./tailwindcss.exe -i src/css/tailwind-input.css -o public/assets/css/tailwind.css --minify
./tailwindcss.exe -i src/css/admin-tailwind-input.css -o public/assets/css/admin-tailwind.css --minify
```

## C.7. Cơ chế hộp thoại xác nhận dùng chung
`public/assets/js/confirm-dialog.js` thay thế `confirm()` mặc định của trình duyệt bằng modal
Tailwind — dùng chung cho cả Client và Admin qua thuộc tính `data-confirm="nội dung"` gắn trên thẻ
`<a>` hoặc `<form>`. Markup modal đặt trong `view/footer.php` (Client) và `admin/view/footer.php`
(Admin) — không đặt trong file JS vì Tailwind CLI không quét class trong file `.js`.

## C.8. Những lỗi/thiết kế cần lưu ý khi phát triển tiếp
- Bảng `cart` trong CSDL thực chất là **dòng chi tiết đơn hàng đã đặt** (luôn gắn với `id_bill`
  thật), KHÔNG phải giỏ hàng tạm — giỏ hàng lúc đang mua sắm lưu ở PHP session
  (`Codemoi\Model\Cart`), chỉ ghi vào bảng `cart` khi đặt hàng xong. Vì vậy sản phẩm đã từng bán
  không xoá được (ràng buộc khoá ngoại `lk_pro_cart`) — xử lý đúng là bắt lỗi và báo rõ cho admin,
  không nên ép xoá.
- Không có cột "ẩn/hiện sản phẩm" (soft delete) trong bảng `product` — muốn ngừng bán một sản phẩm
  đã có đơn, hiện tại chỉ có thể đặt tồn kho = 0, chưa có cơ chế ẩn hẳn khỏi danh sách.
- Một số bảng trong CSDL dump (`users`, `migrations`, `failed_jobs`, `password_resets`,
  `personal_access_tokens`, `history_bank`) mang tên kiểu Laravel, hiện không được code nào trong
  dự án sử dụng — có thể là tàn dư từ thử nghiệm khác, nên xác minh kỹ trước khi xoá.
- File `.env` chứa thông tin nhạy cảm (mật khẩu SMTP, số tài khoản ngân hàng) — đã được `.gitignore`,
  tuyệt đối không commit lên git.

## C.9. Triển khai (Deploy)
Xem kế hoạch triển khai chi tiết (nếu áp dụng) tại `plans/260711-2351-vercel-deployment/plan.md` —
lưu ý nền tảng serverless như Vercel **không tương thích trực tiếp** với kiến trúc hiện tại (kết nối
MySQL hard-code, session lưu file, upload ảnh vào ổ đĩa cục bộ) và cần tái cấu trúc trước khi deploy.
Với hosting PHP truyền thống (VPS, shared hosting, Railway/Render dạng full server), chỉ cần lặp lại
bước C.3 (trỏ document root vào `public/`) là chạy được ngay.
