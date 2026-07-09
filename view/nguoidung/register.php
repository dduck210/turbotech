<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <!-- Include any necessary CSS files or stylesheets -->
</head>

<body>

    <!-- Breadcrumb Section -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="breadcrumb-content">
                <ul>
                    <li><a href="index.php">Trang chủ</a></li>
                    <li class="active">Đăng ký</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- User Registration Form Section -->
    <div class="jb-login-register_area">
        <div class="container">
            <div class="row" style="justify-content: center;">

                <!-- Registration Form -->
                <div class="col-sm-12 col-md-12 col-lg-6 col-xs-12">
                    <form id="registrationForm" action="index.php?act=register" method="post" onsubmit="return validateForm()">
                        <div class="login-form">
                            <h4 class="login-title">Đăng ký tài khoản</h4>
                            <div class="row">
                                <div class="col-md-6 col-12 mb--20">
                                    <label>Tên đăng nhập</label>
                                    <input type="text" id="user_name" name="user_name" placeholder="Tạo tên đăng nhập của bạn" required>
                                </div>
                                <div class="col-md-6 col-12 mb--20">
                                    <label>Họ tên</label>
                                    <input type="text" id="full_name" name="full_name" placeholder="Nhập họ tên của bạn" required>
                                </div>
                                <div class="col-md-12">
                                    <label>Email</label>
                                    <input type="email" id="email_user" name="email_user" placeholder="Nhập địa chỉ email của bạn" required>
                                </div>
                                <div class="col-md-12">
                                    <label>Mật khẩu</label>
                                    <input type="password" id="password" name="password" placeholder="Tạo mật khẩu của bạn" required>
                                </div>
                                <div class="col-md-12">
                                    <input type="submit" class="btn-submit" name="btn_register" value="Đăng ký">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Include any necessary JavaScript files or scripts -->
    <script>
        function validateForm() {
            var userName = document.getElementById('user_name').value;
            var fullName = document.getElementById('full_name').value;
            var email = document.getElementById('email_user').value;
            var password = document.getElementById('password').value;

            if (userName.trim() == '') {
                alert('Vui lòng nhập tên đăng nhập');
                return false;
            }

            if (fullName.trim() == '') {
                alert('Vui lòng nhập họ tên');
                return false;
            }

            if (email.trim() == '') {
                alert('Vui lòng nhập địa chỉ email');
                return false;
            }

            if (password.trim() == '') {
                alert('Vui lòng nhập mật khẩu');
                return false;
            }

            return true;
        }
    </script>
</body>

</html>
