# CHUẨN BỊ BẢO VỆ ĐỒ ÁN — TURBOTECH (CODEMOI)

Tài liệu gồm 2 phần: **(1)** các luồng nên trình bày/demo trước hội đồng, theo thứ tự hợp lý để kể
một câu chuyện mạch lạc; **(2)** các câu hỏi thầy cô thường hỏi kèm gợi ý trả lời — trả lời **thành
thật cả điểm yếu**, vì hội đồng đánh giá cao việc biết rõ hạn chế của chính mình hơn là né tránh.

Toàn bộ nội dung được đối chiếu trực tiếp với source code hiện tại, không suy đoán.

---

# PHẦN 1 — CÁC LUỒNG CẦN TRÌNH BÀY KHI DEMO

## Luồng 1: Đăng ký → Đăng nhập → Đăng xuất
**Trình bày:** đăng ký tài khoản mới (chọn tỉnh/xã bằng dropdown liên động), đăng nhập, đăng xuất.
**Điểm kỹ thuật nên nhấn mạnh:**
- Validate cả 2 lớp: client (JS `data-rules` + `form-validate.js`) và server (`AuthController::validateRegistration()`), vì client validate có thể bị bỏ qua nếu người dùng gửi request trực tiếp.
- Kiểm tra trùng username/email/số điện thoại trước khi tạo tài khoản (`User::findDuplicateField`), báo lỗi cụ thể theo từng trường thay vì một thông báo chung.

## Luồng 2: Quên mật khẩu
**Trình bày:** nhập email/SĐT → nhận mã 6 số qua email → nhập mã xác nhận → đặt mật khẩu mới.
**Điểm kỹ thuật:**
- Mã xác nhận gửi qua **email thật** (PHPMailer + Gmail SMTP, cấu hình trong `.env`).
- Có thể tra tài khoản bằng **email hoặc số điện thoại**, nhưng mã luôn gửi tới email (không có kênh SMS).

## Luồng 3: Duyệt sản phẩm → Thêm giỏ hàng
**Trình bày:** xem danh sách sản phẩm theo danh mục, xem chi tiết, thêm vào giỏ, sửa số lượng trong giỏ.
**Điểm kỹ thuật:** số lượng thêm vào giỏ được kiểm tra tồn kho thực tế ngay lúc thêm (`Product::hasStock`).

## Luồng 4: Áp mã giảm giá (AJAX)
**Trình bày:** tại trang thanh toán, nhập mã giảm giá → hệ thống kiểm tra và áp dụng ngay không cần tải lại trang.
**Điểm kỹ thuật:**
- `Coupon::validateForCart()` kiểm tra đủ 5 điều kiện: mã tồn tại & đang bật, còn hạn, còn lượt dùng, đơn đạt giá trị tối thiểu, đúng phạm vi sản phẩm áp dụng (nếu mã giới hạn theo 1 sản phẩm).
- Mã áp dụng được lưu ở **session phía server**, không phải giấu trong form ẩn — số tiền giảm thực tế luôn được **tính lại ở server** lúc tạo đơn (`CheckoutController::resolveCoupon()`), nên không thể sửa DOM/HTML để gian lận số tiền giảm.

## Luồng 5: Thanh toán & tạo đơn hàng
**Trình bày:** điền thông tin nhận hàng → chọn phương thức (COD hoặc Chuyển khoản) → xác nhận đặt hàng → (nếu chuyển khoản) hiện mã QR VietQR đã điền sẵn số tiền/nội dung.
**Điểm kỹ thuật:**
- **Re-check tồn kho lần cuối ngay trước khi tạo đơn**, dù đã kiểm tra lúc thêm vào giỏ — phòng trường hợp 2 khách cùng mua sản phẩm cuối cùng trong lúc người này còn đang điền form.
- `Product::decrementStock()` trừ kho bằng một câu `UPDATE ... WHERE stock >= ?` — điều kiện nằm ngay trong câu lệnh nên việc trừ kho có tính **atomic ở tầng CSDL**, không cần transaction/khoá riêng mà vẫn tránh bán âm kho khi có nhiều yêu cầu đồng thời.
- QR chuyển khoản dùng **API công khai của VietQR.io** (`img.vietqr.io`), không tự cài đặt thuật toán sinh mã QR — chỉ gọi URL kèm số tài khoản/số tiền/nội dung đã cấu hình.
- Gửi email xác nhận đơn hàng ngay sau khi tạo đơn thành công.

## Luồng 6: Theo dõi & huỷ đơn hàng
**Trình bày:** vào trang tài khoản → tab đơn hàng → xem trạng thái → huỷ đơn (nếu còn ở trạng thái "Mới").
**Điểm kỹ thuật:** điều kiện huỷ đơn được khoá ngay trong câu SQL (`UPDATE bill SET status=4 WHERE id_bill=? AND id_user=? AND status=0`) — vừa đảm bảo đúng chủ đơn, vừa đảm bảo chỉ huỷ được đơn còn "Mới", không thể bypass bằng cách gọi thẳng route với `id_bill` bất kỳ.

## Luồng 7: Đánh giá/bình luận sản phẩm
**Trình bày:** vào sản phẩm đã mua và đã nhận hàng → viết bình luận.
**Điểm kỹ thuật:** điều kiện "đã mua và đã nhận hàng" được **kiểm tra lại ở server** (`Order::hasDeliveredPurchase()`), không chỉ ẩn/hiện form ở giao diện — người dùng không thể bypass bằng cách gửi POST trực tiếp.

## Luồng 8: Quản trị — CRUD sản phẩm/danh mục/mã giảm giá
**Trình bày:** đăng nhập admin → thêm/sửa/xoá sản phẩm, danh mục, mã giảm giá.
**Điểm kỹ thuật (rất nên demo):** thử xoá một sản phẩm **đã từng được đặt hàng** → hệ thống hiện thông báo lỗi thân thiện thay vì crash, vì ràng buộc khoá ngoại `lk_pro_cart` bảo vệ lịch sử đơn hàng cũ (bảng `cart` = dòng chi tiết đơn, không phải giỏ tạm). Đây là ví dụ tốt để nói về **toàn vẹn dữ liệu (data integrity)**.

## Luồng 9: Quản trị — Quản lý đơn hàng
**Trình bày:** duyệt đơn ("Mới" → "Đang xử lý"), chuyển giao hàng ("Đang giao"), huỷ đơn ("Đã hủy"), xem chi tiết từng đơn.

## Luồng 10: Kiến trúc & bảo mật hạ tầng
**Trình bày (nói, không cần thao tác):**
- Sơ đồ luồng request: `Browser → public/index.php → Router (?act=) → Controller → Model → View`.
- `public/` là **webroot thật sự**; toàn bộ `src/`, `view/`, `admin/model`, `.env`, file `.sql` nằm ngoài webroot, không truy cập được qua URL trực tiếp.
- File `.htaccess` gốc dự án **chặn truy cập (403)** nếu ai đó (vô tình) cấu hình server trỏ thẳng vào thư mục gốc thay vì `public/` — một lớp phòng thủ dự phòng, không phụ thuộc hoàn toàn vào việc cấu hình đúng.

---

# PHẦN 2 — CÂU HỎI THẦY CÔ THƯỜNG HỎI & GỢI Ý TRẢ LỜI

## A. Kiến trúc & công nghệ

**Q: Dự án dùng mô hình/kiến trúc gì?**
Mô hình MVC tự xây dựng (không dùng framework có sẵn) cho phần giao diện khách hàng: `Router` đọc
tham số `?act=` trên URL, tra bảng định tuyến để gọi đúng `Controller`, Controller gọi `Model` để
lấy/ghi dữ liệu rồi trả về `View` để hiển thị.

**Q: Vì sao không dùng Laravel/framework có sẵn?**
Dự án bắt đầu từ PHP thuần, sau đó được refactor dần từng phần sang kiến trúc MVC tự viết để kiểm
soát rủi ro thay đổi và hiểu rõ nguyên lý MVC từ gốc, thay vì phụ thuộc toàn bộ vào một framework.

**Q: Vì sao trang quản trị (admin) không theo cùng kiến trúc MVC với trang khách hàng?**
Đây là hạn chế thật: phần admin (`public/admin/index.php`) vẫn là một khối `switch-case` xử lý toàn
bộ nghiệp vụ, chưa được refactor. Việc refactor Client được ưu tiên làm trước để giảm rủi ro động
vào toàn hệ thống cùng lúc; admin là hướng phát triển tiếp theo.

**Q: Router hoạt động thế nào, có gì khác so với `switch case` truyền thống?**
`Router::add('act', [Controller::class, 'method'])` đăng ký route, `dispatch()` tra cứu theo khoá
`act` và gọi handler tương ứng; nếu `act` rỗng hoặc không khớp route nào thì gọi handler mặc định —
hành vi tương đương `switch...default` cũ nhưng tách biệt được từng route thành 1 đơn vị độc lập,
dễ mở rộng/kiểm thử hơn khối switch khổng lồ.

## B. Cơ sở dữ liệu

**Q: Vì sao bảng `cart` vừa giống "giỏ hàng" vừa giống "chi tiết đơn hàng"?**
Tên bảng dễ gây hiểu nhầm nhưng thực tế: giỏ hàng lúc đang mua sắm (chưa đặt) được lưu ở **PHP
session**, hoàn toàn không đụng tới bảng `cart`. Chỉ khi đơn hàng được tạo (`Order::create()` +
`Order::addItem()`), mỗi sản phẩm trong đơn mới được ghi thành 1 dòng vào bảng `cart` kèm `id_bill`
thật. Vậy bảng `cart` trong CSDL thực chất là **bảng dòng chi tiết đơn hàng**, tên gọi kế thừa từ
thiết kế ban đầu — điểm có thể cải thiện là đổi tên bảng cho đúng bản chất (ví dụ `order_items`).

**Q: Vì sao xoá sản phẩm bị chặn/báo lỗi?**
Ràng buộc khoá ngoại `lk_pro_cart` (FK từ `cart.id_pro` → `product.id_pro`) không cho xoá sản phẩm
nếu đã có dòng chi tiết đơn hàng tham chiếu tới nó — nhằm bảo vệ lịch sử đơn hàng cũ không bị mất
dữ liệu tham chiếu. Hệ thống bắt lỗi này (`SQLSTATE 23000`) và hiển thị thông báo rõ ràng cho admin
thay vì để crash.

**Q: Dữ liệu đã được chuẩn hoá (normalize) chưa?**
Còn hạn chế: địa chỉ lưu dạng 1 chuỗi ghép `"chi tiết, xã, tỉnh"` thay vì tách cột riêng; ảnh sản
phẩm lưu tên file dạng string trong chính bảng `product` thay vì tách bảng ảnh 1-nhiều nếu sau này
cần nhiều ảnh/sản phẩm.

**Q: Trong file `.sql` có vài bảng lạ (`users`, `migrations`, `failed_jobs`,
`personal_access_tokens`...) dùng để làm gì?**
Đây là các bảng mang tên/kiểu dáng của Laravel, hiện **không được bất kỳ đoạn code nào trong dự án
sử dụng** — nhiều khả năng là tàn dư từ một lần thử nghiệm/import khác, có thể dọn bỏ sau khi xác
nhận chắc chắn không còn phụ thuộc.

## C. Bảo mật (hội đồng thường hỏi sâu nếu đề tài có yếu tố web/security)

**Q: Chống SQL Injection bằng cách nào?**
Toàn bộ truy vấn đi qua PDO **prepared statements** với tham số được bind (`Database::query/queryOne
/execute` luôn gọi `$stmt->execute($args)`), không có chỗ nào nối trực tiếp giá trị người dùng vào
chuỗi SQL.

**Q: Mật khẩu người dùng được lưu và kiểm tra như thế nào? Có mã hoá/hash không?**
*(Câu hỏi gần như chắc chắn sẽ gặp — nên trả lời thẳng, không né tránh.)*
Hiện tại mật khẩu đang lưu **dạng plaintext** (`User::check()` so sánh trực tiếp `password = ?`
trong câu SQL) — đây là điểm yếu bảo mật thật sự, kế thừa từ code gốc ban đầu, **chưa kịp nâng cấp**
sang băm mật khẩu bằng `password_hash()`/`password_verify()` (thuật toán bcrypt) trước khi lưu và so
sánh. Hướng khắc phục đã xác định rõ: băm mật khẩu khi đăng ký/đổi mật khẩu, so sánh bằng
`password_verify()` khi đăng nhập, và cần viết migration để băm lại mật khẩu của các tài khoản cũ
đang có trong CSDL.

**Q: Chống XSS (Cross-Site Scripting) thế nào?**
Chưa đồng bộ: chỉ 11/42 file giao diện có dùng `htmlspecialchars()` khi in dữ liệu ra HTML, phần còn
lại in trực tiếp giá trị từ CSDL (ví dụ nội dung bình luận `content_cmt` trong
`public/view/comment-form.php` chưa được escape) — nếu người dùng nhập nội dung chứa thẻ
`<script>`, nội dung đó có thể được thực thi khi hiển thị lại cho người khác xem. Hướng khắc phục:
escape nhất quán mọi giá trị do người dùng nhập trước khi in ra HTML.

**Q: Có cơ chế chống CSRF (Cross-Site Request Forgery) không?**
Chưa có — không có token CSRF ở bất kỳ form nào (đăng nhập, đổi mật khẩu, đặt hàng...), các thao tác
thay đổi dữ liệu chỉ dựa vào session đăng nhập. Hướng khắc phục: sinh token ngẫu nhiên theo session,
nhúng vào form ẩn, kiểm tra khớp token ở server trước khi xử lý.

**Q: Mã OTP quên mật khẩu có đủ an toàn không?**
Mã sinh bằng `rand(0, 999999)` — hàm ngẫu nhiên **không dành cho mục đích bảo mật**
(không phải `random_int()`), và **không có thời hạn hết hạn** được kiểm tra (mã vẫn hợp lệ cho tới
khi có mã mới được sinh ra đè lên). Hướng khắc phục: dùng `random_int()`, lưu kèm thời điểm hết hạn
(ví dụ 5–10 phút) và kiểm tra khi xác nhận.

**Q: Vì sao lại tách `public/` ra làm webroot riêng?**
Trước đây toàn bộ thư mục dự án (gồm cả `src/`, `.env`, file `.sql`, code admin) nằm trong webroot,
tức truy cập trực tiếp được qua URL nếu biết đường dẫn. Việc tách `public/` (theo mô hình
Laravel/Symfony) + cấu hình Apache `Alias` trỏ đúng vào `public/`, cùng file `.htaccess` chặn truy
cập ở gốc dự án, đảm bảo dù server có bị cấu hình sai (trỏ thẳng vào gốc dự án) thì source code và
dữ liệu nhạy cảm vẫn không bị lộ — trả về lỗi 403 thay vì hiển thị danh sách file.

## D. Nghiệp vụ / xử lý logic

**Q: Làm sao đảm bảo không bán vượt tồn kho khi nhiều khách mua cùng lúc?**
`Product::decrementStock()` thực hiện `UPDATE product SET stock = stock - ? WHERE id_pro = ? AND
stock >= ?` — điều kiện đủ hàng nằm ngay trong câu UPDATE, nên việc kiểm tra và trừ kho diễn ra
**trong cùng 1 câu lệnh, có tính atomic ở tầng CSDL**. Nếu không đủ hàng, câu lệnh ảnh hưởng 0 dòng
và hệ thống biết ngay để xử lý, mà không cần dùng transaction/khoá bảng riêng.

**Q: Mã giảm giá được tính như thế nào?**
Hai loại: giảm theo **phần trăm** (có thể giới hạn mức giảm tối đa) hoặc giảm **số tiền cố định**;
số tiền giảm không bao giờ vượt quá tổng giá trị đơn hàng. Toàn bộ điều kiện áp dụng (hạn dùng, số
lượt còn lại, đơn tối thiểu, giới hạn theo sản phẩm) được kiểm tra lại **ngay tại thời điểm tạo đơn**
(`CheckoutController::resolveCoupon()`), không tin vào giá trị đã tính trước đó ở phiên trước — tránh
trường hợp mã hết hạn/hết lượt ngay giữa lúc khách đang thao tác nhưng đơn vẫn được tạo với giá đã giảm.

**Q: Điều kiện để một khách hàng được bình luận/đánh giá sản phẩm là gì?**
Phải có ít nhất một đơn hàng chứa sản phẩm đó **và đơn hàng đã ở trạng thái "Đã giao"** — kiểm tra
lại ở server (`Order::hasDeliveredPurchase()`), không chỉ ẩn nút trên giao diện, nên không thể lách
bằng cách gửi request trực tiếp.

## E. Vận hành / triển khai

**Q: Ứng dụng được triển khai (deploy) như thế nào?**
Hiện chạy trên bộ Apache + PHP + MySQL (XAMPP), với **document root trỏ vào thư mục `public/`**
(không phải gốc dự án). Nhóm có tìm hiểu khả năng triển khai lên nền tảng serverless (Vercel) nhưng
phát hiện **không tương thích trực tiếp**: Vercel không có ổ đĩa bền vững (ảnh hưởng upload ảnh),
không host MySQL, và session file mặc định không hoạt động ổn định trên hàm serverless — muốn deploy
lên đó cần tái cấu trúc (đổi kết nối DB sang cloud, đổi session sang lưu DB, đổi upload ảnh sang
object storage).

**Q: Gửi email dùng dịch vụ/thư viện gì?**
Thư viện **PHPMailer** (vendor bên thứ ba), gửi qua **Gmail SMTP** với App Password — thông tin đăng
nhập SMTP đọc từ file `.env` (không commit lên git) chứ không hard-code trong source.

## F. Hạn chế & hướng phát triển (hội đồng gần như luôn hỏi câu này)

Trả lời gộp, chủ động liệt kê để thể hiện đã tự đánh giá được sản phẩm của mình:
1. **Mật khẩu chưa được băm (hash)** — đang lưu plaintext, cần chuyển sang `password_hash()`.
2. **Chưa có CSRF token** ở các form thay đổi dữ liệu.
3. **Escape XSS chưa đồng bộ** toàn bộ giao diện, đặc biệt nội dung do người dùng nhập (bình luận).
4. **Mã OTP** dùng `rand()` không đủ ngẫu nhiên về mặt bảo mật, và chưa kiểm tra thời hạn hết hạn.
5. **Phần admin chưa được refactor theo MVC** — vẫn là 1 file switch-case thủ tục lớn.
6. **Chưa có tính năng ẩn/ngừng bán sản phẩm (soft delete)** — chỉ có xoá cứng, và bị chặn nếu sản
   phẩm đã từng bán được (đúng thiết kế bảo vệ dữ liệu, nhưng thiếu giải pháp thay thế cho admin).
7. **Một số bảng CSDL dư thừa/không dùng** (tàn dư từ thử nghiệm khác), nên dọn dẹp.
8. **Chưa triển khai (deploy) lên môi trường production/tên miền thật** — mới chạy ổn định ở môi
   trường local (XAMPP).

> Gợi ý cách trình bày phần này: nói ngắn gọn "Nhóm đã tự nhận diện được các hạn chế sau và có
> hướng khắc phục rõ ràng cho từng điểm" rồi liệt kê — điều này thường ghi điểm tốt hơn nhiều so với
> việc bị hỏi dồn và mới nhận ra vấn đề ngay tại chỗ.
