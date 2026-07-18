# CHUẨN BỊ BẢO VỆ ĐỒ ÁN — TURBOTECH (CODEMOI)

Tài liệu gồm 2 phần: **(1)** các luồng nên trình bày/demo trước hội đồng; **(2)** các câu hỏi thầy cô
thường hỏi kèm gợi ý trả lời — trả lời **thành thật cả điểm yếu**, vì hội đồng đánh giá cao việc biết
rõ hạn chế của chính mình hơn là né tránh.

**Lưu ý quan trọng:** dự án đã được **chuyển đổi hoàn toàn sang Laravel 13** (PHP 8.4) sau bản PHP
thuần ban đầu. Toàn bộ nội dung dưới đây đã đối chiếu lại trực tiếp với source code hiện tại, không
suy đoán từ phiên bản cũ — **nếu bạn có tài liệu cũ ghi mật khẩu lưu plaintext / chưa có CSRF / OTP
dùng `rand()`, những điều đó KHÔNG còn đúng nữa và không nên nói trước hội đồng**, vì hiện tại đã
được khắc phục (chi tiết ở Phần 2.C bên dưới).

---

# PHẦN 1 — CÁC LUỒNG CẦN TRÌNH BÀY KHI DEMO

## Luồng 1: Đăng ký → Đăng nhập → Đăng xuất
**Trình bày:** đăng ký tài khoản mới, đăng nhập, đăng xuất.
**Điểm kỹ thuật nên nhấn mạnh:**
- Validate 2 lớp: phía trình duyệt và phía server (`RegisterRequest`) — không thể bỏ qua bằng cách
  tắt JavaScript hay gửi request trực tiếp.
- Kiểm tra trùng username/email trước khi tạo tài khoản, báo lỗi cụ thể theo từng trường.
- **Giới hạn tốc độ (rate limiting)**: đăng nhập giới hạn 5 lần thử/phút để chống dò mật khẩu
  tự động — nên demo thử sai liên tục để thấy bị chặn tạm thời.

## Luồng 2: Quên mật khẩu
**Trình bày:** nhập email → nhận mã 6 số qua email thật → nhập mã xác nhận → đặt mật khẩu mới.
**Điểm kỹ thuật:**
- Mã sinh bằng `random_int()` (hàm ngẫu nhiên an toàn cho bảo mật), **hết hạn sau 10 phút**, giới
  hạn 5 lần nhập sai/phút.
- Gửi qua Gmail SMTP bằng `Illuminate\Mail` (Laravel built-in), cấu hình trong `.env`.

## Luồng 3: Duyệt sản phẩm → Thêm giỏ hàng
**Trình bày:** xem danh sách sản phẩm (có **phân trang**), lọc theo danh mục/khoảng giá, xem chi
tiết, thêm vào giỏ.
**Điểm kỹ thuật:** lọc khoảng giá tính trên **giá sau khi giảm** (giá thực khách trả), không phải
giá gốc — tránh trường hợp sản phẩm đang giảm giá bị lọc nhầm ra ngoài khoảng ngân sách khách chọn.

## Luồng 4: Áp mã giảm giá (AJAX)
**Trình bày:** tại trang thanh toán, nhập mã giảm giá → áp dụng ngay không cần tải lại trang.
**Điểm kỹ thuật:**
- Kiểm tra đủ điều kiện: mã tồn tại & đang bật, còn hạn, còn lượt dùng, đơn đạt giá trị tối thiểu,
  đúng phạm vi sản phẩm áp dụng.
- Số tiền giảm luôn **tính lại ở server** lúc tạo đơn, không tin vào giá trị phía trình duyệt.
- **Race condition đã được xử lý**: việc tăng số lượt đã dùng của mã giảm giá nằm trong cùng
  transaction với việc tạo đơn — nếu 2 khách cùng lúc dùng 1 mã sắp hết lượt, chỉ 1 người được áp
  dụng thành công.

## Luồng 5: Thanh toán & tạo đơn hàng
**Trình bày:** điền thông tin nhận hàng → chọn COD hoặc Chuyển khoản → xác nhận đặt hàng → (nếu
chuyển khoản) hiện mã QR VietQR đã điền sẵn số tiền/nội dung.
**Điểm kỹ thuật:**
- Re-check tồn kho ngay trước khi tạo đơn (phòng 2 khách cùng mua sản phẩm cuối cùng).
- Trừ kho bằng `UPDATE ... WHERE stock >= ?` — điều kiện nằm ngay trong câu lệnh, có tính **atomic
  ở tầng CSDL**, không cần khóa bảng riêng mà vẫn tránh bán âm kho.
- Mã đơn hàng (5 chữ số) được sinh và **kiểm tra trùng** trước khi lưu.
- Nếu gửi email xác nhận thất bại (SMTP lỗi/timeout), đơn hàng **vẫn được tạo bình thường** — lỗi
  chỉ được ghi log, không làm khách tưởng đặt hàng thất bại rồi đặt trùng lần nữa.
- QR chuyển khoản dùng API công khai của VietQR.io, không tự cài thuật toán sinh mã QR.

## Luồng 6: Theo dõi & hủy đơn hàng
**Trình bày:** vào trang tài khoản → tab đơn hàng → hủy đơn (nếu còn "Mới").
**Điểm kỹ thuật:** điều kiện hủy đơn (đúng chủ đơn + đơn còn "Mới") được kiểm tra ở server, không
thể bypass bằng cách gọi thẳng route với ID đơn bất kỳ.

## Luồng 7: Đánh giá/bình luận sản phẩm
**Trình bày:** vào sản phẩm đã mua và đã nhận hàng → viết bình luận.
**Điểm kỹ thuật:** điều kiện "đã mua và đã nhận" kiểm tra lại ở server; mỗi khách chỉ đánh giá được
**1 lần/sản phẩm** — chặn cả ở tầng ứng dụng (thông báo thân thiện) lẫn ràng buộc UNIQUE trong CSDL
(lớp bảo vệ cuối cùng nếu tầng ứng dụng có sai sót).

## Luồng 8: Quản trị — CRUD sản phẩm/danh mục/mã giảm giá
**Trình bày:** đăng nhập admin → thêm/sửa/xóa sản phẩm, danh mục, mã giảm giá (đều có **phân
trang**).
**Điểm kỹ thuật (rất nên demo):**
- Thử xóa một sản phẩm **đã từng được đặt hàng** → hệ thống hiện thông báo lỗi thân thiện thay vì
  crash, vì ràng buộc khóa ngoại bảo vệ lịch sử đơn hàng cũ. Ví dụ tốt để nói về **toàn vẹn dữ liệu**.
- Thử sửa mã giảm giá/tên đăng nhập trùng với mã/tài khoản đã có → báo lỗi rõ ràng, không crash.

## Luồng 9: Quản trị — Quản lý đơn hàng
**Trình bày:** duyệt đơn, chuyển giao hàng, hủy đơn, xem chi tiết.
**Điểm kỹ thuật:** chuyển trạng thái theo **một chiều duy nhất** (Mới → Đang xử lý → Đang giao →
Đã giao) — thử chuyển ngược hoặc hủy một đơn **đã giao** để thấy hệ thống từ chối, tránh hoàn kho
nhầm cho hàng đã giao tới tay khách.

## Luồng 10: Quản trị — Quản lý người dùng
**Trình bày:** thử xóa/hạ quyền tài khoản admin **cuối cùng** trong hệ thống → bị chặn, tránh khóa
quyền truy cập trang quản trị vĩnh viễn.

## Luồng 11: Kiến trúc & bảo mật hạ tầng
**Trình bày (nói, không cần thao tác):**
- Sơ đồ luồng request: `Browser → public/index.php → Laravel Router → Middleware → Controller →
  Model/Service → Blade View`.
- `public/` là webroot thật sự; `.env`, `app/`, `database/` nằm ngoài webroot, không truy cập được
  qua URL trực tiếp.

---

# PHẦN 2 — CÂU HỎI THẦY CÔ THƯỜNG HỎI & GỢI Ý TRẢ LỜI

## A. Kiến trúc & công nghệ

**Q: Dự án dùng framework/kiến trúc gì?**
**Laravel 13** (PHP 8.4), mô hình MVC chuẩn: Router (`routes/web.php`) tra route theo URL, gọi
Controller tương ứng, Controller gọi Model (Eloquent ORM)/Service để lấy/ghi dữ liệu, trả về View
(Blade template) để hiển thị.

**Q: Dự án bắt đầu như thế nào, vì sao lại chuyển sang Laravel?**
Ban đầu viết bằng PHP thuần với kiến trúc MVC tự xây dựng (router tự viết theo tham số `?act=`,
PDO thủ công). Sau khi nắm vững nguyên lý MVC từ gốc, nhóm chuyển hẳn sang Laravel để tận dụng các
cơ chế bảo mật/validate/ORM đã được kiểm chứng rộng rãi, thay vì tự duy trì các cơ chế đó thủ công
— đồng thời giữ nguyên toàn bộ dữ liệu và quy tắc nghiệp vụ đã xây dựng trước đó (migrate tại chỗ,
không viết lại từ đầu).

**Q: Vì sao trang quản trị (admin) và trang khách hàng dùng chung 1 kiến trúc?**
Cả 2 đều là Controller/Model/View chuẩn của Laravel, khác biệt duy nhất là admin nằm dưới tiền tố
route `/admin` và middleware `admin` (`EnsureUserIsAdmin`) kiểm tra quyền. Đây là điểm đã cải thiện
so với bản đầu — trước đây admin là 1 khối switch-case thủ tục riêng biệt, chưa theo MVC.

## B. Cơ sở dữ liệu

**Q: Vì sao bảng `cart` vừa giống "giỏ hàng" vừa giống "chi tiết đơn hàng"?**
Tên bảng dễ gây hiểu nhầm nhưng thực tế: giỏ hàng lúc đang mua sắm (chưa đặt) lưu ở **session**,
hoàn toàn không đụng bảng `cart`. Chỉ khi đơn hàng được tạo, mỗi sản phẩm trong đơn mới được ghi
thành 1 dòng vào bảng `cart` kèm `id_bill` thật — tên gọi kế thừa từ thiết kế ban đầu, điểm có thể
cải thiện là đổi tên bảng cho đúng bản chất (VD `order_items`), nhưng giữ nguyên để không phá vỡ
dữ liệu 30+ đơn hàng thật đã có.

**Q: Vì sao xóa sản phẩm bị chặn/báo lỗi?**
Ràng buộc khóa ngoại không cho xóa sản phẩm nếu đã có dòng chi tiết đơn hàng tham chiếu tới nó —
bảo vệ lịch sử đơn hàng cũ. Hệ thống bắt lỗi này và hiển thị thông báo rõ ràng cho admin.

**Q: Dữ liệu đã được chuẩn hóa (normalize) chưa?**
Còn hạn chế thật, giữ nguyên từ schema gốc: địa chỉ lưu dạng chuỗi ghép thay vì tách cột riêng; một
số cột kiểu dữ liệu chưa tối ưu (VD số điện thoại đơn hàng lưu kiểu số nguyên thay vì chuỗi, mất số
0 ở đầu nếu convert qua lại) — nhóm đã xác định rõ nhưng chọn **giữ nguyên schema** khi chuyển sang
Laravel để không ảnh hưởng dữ liệu thật đang có, thay vì sửa và có rủi ro làm sai lệch dữ liệu cũ.

**Q: Bạn quản lý migration CSDL như thế nào?**
`database/migrations/` mô tả lại đúng schema hiện có bằng cú pháp Laravel, để môi trường mới có thể
`php artisan migrate --seed` dựng DB từ đầu thay vì phải import file `.sql` thủ công.

## C. Bảo mật (hội đồng thường hỏi sâu nếu đề tài có yếu tố web/security)

**Q: Chống SQL Injection bằng cách nào?**
Toàn bộ truy vấn đi qua **Eloquent ORM/Query Builder của Laravel**, tự động dùng prepared statement
với tham số bind — không có chỗ nào nối trực tiếp giá trị người dùng vào chuỗi SQL. Vài truy vấn
đặc biệt dùng `whereRaw()` (ví dụ lọc theo giá sau giảm) vẫn dùng tham số bind (`?`), không nối
chuỗi trực tiếp.

**Q: Mật khẩu người dùng được lưu và kiểm tra như thế nào?**
Băm bằng **bcrypt** (`Hash::make()` lúc đăng ký/đổi mật khẩu, `Hash::check()` lúc đăng nhập) — đây
là thuật toán băm mật khẩu tiêu chuẩn của Laravel, không lưu plaintext.

**Q: Chống XSS (Cross-Site Scripting) thế nào?**
Blade (template engine của Laravel) **tự động escape HTML** mọi biến in ra bằng cú pháp `{{ $bien }}`
— mặc định trên toàn bộ giao diện, không cần gọi `htmlspecialchars()` thủ công từng chỗ như cách làm
truyền thống. Có **đúng 1 ngoại lệ có chủ đích**: mô tả chi tiết sản phẩm cho phép HTML thô (`{!! !!}`)
để admin định dạng (in đậm, danh sách...) — chấp nhận được vì trường này chỉ do admin đã xác thực
nhập, không phải nội dung khách hàng gửi tự do (bình luận, câu hỏi... đều dùng `{{ }}` escape).

**Q: Có cơ chế chống CSRF (Cross-Site Request Forgery) không?**
Có — Laravel tự động bật CSRF protection cho mọi route, mỗi form POST đều nhúng token CSRF
(`@csrf`), server so khớp token trước khi xử lý. Toàn bộ form thay đổi dữ liệu trong dự án (30 form)
đều có token này.

**Q: Mã OTP quên mật khẩu có đủ an toàn không?**
Có — sinh bằng `random_int()` (hàm ngẫu nhiên an toàn cho mục đích bảo mật, khác `rand()`), lưu kèm
thời điểm hết hạn (**10 phút**) và kiểm tra khi xác nhận, đồng thời giới hạn 5 lần thử/phút để
chống dò mã bằng script tự động.

**Q: Vì sao lại tách `public/` ra làm webroot riêng?**
Chỉ những gì trong `public/` mới truy cập được trực tiếp qua URL; `app/`, `.env`, `database/` nằm
ngoài webroot nên không thể bị lộ dù có ai đó biết đường dẫn file. Đây cũng là quy ước mặc định của
Laravel, không phải tùy biến riêng của dự án.

## D. Nghiệp vụ / xử lý logic

**Q: Làm sao đảm bảo không bán vượt tồn kho khi nhiều khách mua cùng lúc?**
`UPDATE product SET stock = stock - ? WHERE id_pro = ? AND stock >= ?` — điều kiện đủ hàng nằm
ngay trong câu UPDATE, việc kiểm tra và trừ kho diễn ra trong **cùng 1 câu lệnh, atomic ở tầng
CSDL**. Nếu không đủ hàng, câu lệnh ảnh hưởng 0 dòng và hệ thống biết ngay.

**Q: Mã giảm giá được tính như thế nào, có tránh được race condition không?**
Hai loại: phần trăm hoặc số tiền cố định, số tiền giảm không vượt tổng giá trị đơn. Điều kiện áp
dụng được kiểm tra lại ngay tại thời điểm tạo đơn. Việc **tăng số lượt đã dùng** nằm trong cùng
transaction với việc tạo đơn — nếu 2 khách cùng lúc dùng 1 mã sắp hết lượt, chỉ người vào trước
được áp dụng, người sau bị từ chối đúng lúc đó thay vì cả hai đều được giảm giá.

**Q: Điều kiện để một khách hàng được bình luận/đánh giá sản phẩm là gì?**
Phải có đơn hàng chứa sản phẩm đó **và đơn đã "Đã giao"** — kiểm tra lại ở server, và mỗi khách chỉ
được đánh giá 1 lần/sản phẩm (ràng buộc UNIQUE trong CSDL là lớp bảo vệ cuối).

**Q: Đơn hàng có thể bị chuyển trạng thái tùy tiện không (VD hủy đơn đã giao)?**
Không — chuyển trạng thái theo bảng ánh xạ một chiều cố định, áp dụng cho cả thao tác nhanh lẫn
form sửa thủ công. Hủy một đơn đã giao hoặc chuyển ngược trạng thái đều bị từ chối ở server.

## E. Vận hành / triển khai

**Q: Ứng dụng được triển khai (deploy) như thế nào?**
Hiện chạy trên Apache + PHP + MySQL (XAMPP), document root vẫn là thư mục gốc dùng chung của
XAMPP (không có vhost riêng) — `.htaccess` tự rewrite request vào `public/`. Nhóm có tìm hiểu khả
năng triển khai lên nền tảng serverless (Vercel) nhưng phát hiện không tương thích trực tiếp: không
có ổ đĩa bền vững (ảnh hưởng upload ảnh), không host MySQL — muốn deploy lên đó cần tái cấu trúc.

**Q: Gửi email dùng dịch vụ/thư viện gì?**
`Illuminate\Mail` (built-in của Laravel) qua Gmail SMTP với App Password, cấu hình trong `.env`.

## F. Hạn chế & hướng phát triển (hội đồng gần như luôn hỏi câu này)

Trả lời gộp, chủ động liệt kê để thể hiện đã tự đánh giá được sản phẩm của mình:
1. **Địa chỉ chưa chuẩn hóa** — lưu dạng chuỗi ghép thay vì tách cột riêng; giữ nguyên để không ảnh
   hưởng dữ liệu đơn hàng thật đã có.
2. **Một vài cột trong bảng đơn hàng có kiểu dữ liệu chưa tối ưu** (kế thừa từ schema gốc, giữ
   nguyên để không phá vỡ tương thích ngược với dữ liệu cũ).
3. **Chưa có test tự động đầy đủ** — mới có 1 smoke test kiểm tra ứng dụng khởi động đúng, chưa
   bao phủ toàn bộ luồng nghiệp vụ bằng test tự động.
4. **Thanh toán chuyển khoản chưa có xác nhận tự động** — hệ thống chỉ tạo mã QR, việc xác nhận đã
   nhận được tiền vẫn do admin tự kiểm tra và đánh dấu thủ công (chưa tích hợp webhook ngân hàng
   thật để tự động xác nhận).
5. **Chưa có tính năng ẩn/ngừng bán sản phẩm (soft delete)** — chỉ có xóa cứng, và bị chặn nếu sản
   phẩm đã từng bán được (đúng thiết kế bảo vệ dữ liệu, nhưng thiếu giải pháp thay thế cho admin).
6. **Chưa triển khai (deploy) lên môi trường production/tên miền thật** — mới chạy ổn định ở môi
   trường local (XAMPP).

> Gợi ý cách trình bày phần này: nói ngắn gọn "Nhóm đã tự nhận diện được các hạn chế sau và có
> hướng khắc phục rõ ràng cho từng điểm" rồi liệt kê — điều này thường ghi điểm tốt hơn nhiều so với
> việc bị hỏi dồn và mới nhận ra vấn đề ngay tại chỗ.
