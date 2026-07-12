<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/Core/helpers.php';
require_once __DIR__ . '/../../admin/controller/controller.php';

use Codemoi\Core\Csrf;

// Every admin POST action goes through here — one guard covers the whole
// panel. Failure sets a flash message and bounces back to the referring
// page instead of a white-screen 403 (list_* pages already display
// $_SESSION['flash_error']).
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !Csrf::verify($_POST['_token'] ?? null)) {
    $_SESSION['flash_error'] = 'Phiên làm việc đã hết hạn, vui lòng thử lại.';
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
}

// These delete/approve/ship/cancel actions are plain GET links (not forms),
// so the POST-only check above doesn't cover them — without this, an
// <img src="admin/index.php?act=delete_product&..."> on any page an admin
// visits would trigger the action via their session cookie (CSRF-via-GET).
$get_mutating_actions = [
    'delete_cate', 'delete_product', 'delete_usser', 'approve_bill',
    'ship_bill', 'cancel_bill', 'delete_cmt', 'delete_ques', 'delete_coupon',
];
if (
    $_SERVER['REQUEST_METHOD'] === 'GET'
    && in_array($_GET['act'] ?? '', $get_mutating_actions, true)
    && !Csrf::verify($_GET['_token'] ?? null)
) {
    $_SESSION['flash_error'] = 'Phiên làm việc đã hết hạn, vui lòng thử lại.';
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
}

//include dao để dùng các functione:

include __DIR__ . '/../../admin/model/pdo.php';
include __DIR__ . '/../../admin/model/category.php';
include __DIR__ . '/../../admin/model/product.php';
include __DIR__ . '/../../admin/model/user.php';
include __DIR__ . '/../../admin/model/bill.php';
include __DIR__ . '/../../admin/model/comment.php';
include __DIR__ . '/../../admin/model/statistics.php';
include __DIR__ . '/../../admin/model/question.php';
include __DIR__ . '/../../admin/model/coupon.php';
// controller
if (isset($_GET['act'])) {
    $act = $_GET['act'];
    switch ($act) {
        case '/':

        case 'dashboard':
            if (isset($_SESSION['admin'])) {
                render('dashboard');
            } else {
                header("location: index.php?act=login");
            }
            // render('dashboard');
            break;
        case 'logout':
            session_unset();
            header('Location: index.php?act=login');
            break;
        case 'login':
            if (isset($_SESSION['admin'])) {
                header('location: index.php');
            } else {
                if (isset($_POST['btn_login']) && $_POST['btn_login']) {
                    $user_name = $_POST['user_name'];
                    $password = $_POST['password'];
                    if ($user_name == null || $password == null) {
                        echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Điền đầy đủ thông tin !",showConfirmButton:false,timer:3000}));</script>';
                    } else {
                        $check = check_user_admin($user_name, $password);
                        if (is_array($check)) {
                            $_SESSION['admin'] = $check;
                            Csrf::rotate();
                            $_SESSION['flash_success'] = 'Đăng nhập thành công!';
                            header('Location: index.php');
                        } else {
                            echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Tài khoản sai hoặc không tồn tại!",showConfirmButton:false,timer:3000}));</script>';
                        }
                    }
                }
                $flash_error = $_SESSION['flash_error'] ?? null;
                unset($_SESSION['flash_error']);
                render('login', ['flash_error' => $flash_error]);
            }
            break;

        // CONTROLLER LOẠI:
        case "add_category":
            if (isset($_SESSION['admin'])) {
                if (isset($_POST['btn_add']) && ($_POST['btn_add'])) {
                    $name_cate = $_POST['name_cate'];
                    if ($name_cate == null) {
                        echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Vui lòng nhập đầy đủ !",showConfirmButton:false,timer:3000}));</script>';
                    } else {
                        them_loai($name_cate);
                        echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:"Thêm loại thành công!",showConfirmButton:false,timer:3000}));</script>';
                    }
                }
                render('add_category');
            } else {
                header("location: index.php?act=login");
            }

            break;
        case "list_category":

            if (isset($_SESSION['admin'])) {
                $ds_loai = loadall_loai();
                $flash_success = $_SESSION['flash_success'] ?? null;
                $flash_error = $_SESSION['flash_error'] ?? null;
                unset($_SESSION['flash_success'], $_SESSION['flash_error']);
                render(
                    'list_category',
                    ['ds_loai' => $ds_loai, 'flash_success' => $flash_success, 'flash_error' => $flash_error]
                );
            } else {
                header("location: index.php?act=login");
            }

            break;
        case "edit_category":

            if (isset($_SESSION['admin'])) {
                if (isset($_GET['id_cate']) && ($_GET['id_cate'] > 0)) {
                    $id_cate = $_GET['id_cate'];
                    $one_loai = loadone_loai($id_cate);
                }
                render(
                    'update_category',
                    ['one_loai' => $one_loai]
                );
            } else {
                header("location: index.php?act=login");
            }

            break;
        case "update_category":
            if (isset($_SESSION['admin'])) {
                if (isset($_POST['btn_update']) && ($_POST['btn_update'])) {
                    $id_cate = $_POST['id_cate'];
                    $name_cate = trim($_POST['name_cate']);
                    if ($name_cate === '') {
                        echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Vui lòng nhập tên loại !",showConfirmButton:false,timer:3000}));</script>';
                        $one_loai = loadone_loai($id_cate);
                        render('update_category', ['one_loai' => $one_loai]);
                        break;
                    }
                    capnhat_loai($id_cate, $name_cate);
                    $_SESSION['flash_success'] = 'Cập nhật loại thành công!';
                }
                header('location:index.php?act=list_category');
            } else {
                header("location: index.php?act=login");
            }
            break;
        case "delete_cate":
            if (isset($_SESSION['admin'])) {
                if (isset($_GET['id_cate']) && ($_GET['id_cate'] > 0)) {
                    $id_cate = $_GET['id_cate'];
                    try {
                        xoa_loai($id_cate);
                    } catch (PDOException $e) {
                        // SQLSTATE 23000: category still referenced by a product
                        // (lk_cate_product) — block instead of a raw fatal error.
                        if ($e->getCode() === '23000') {
                            $_SESSION['flash_error'] = 'Không thể xoá loại này vì vẫn còn sản phẩm thuộc loại đó.';
                        } else {
                            throw $e;
                        }
                    }
                }
                header('location:index.php?act=list_category');
            } else {
                header("location: index.php?act=login");
            }
            break;

        // CONTROLLER SẢN PHẨM:
        case "add_product":

            if (isset($_SESSION['admin'])) {
                if (isset($_POST['btn_add']) && ($_POST['btn_add'])) {
                    // $id_pro = $_POST['id_pro'];
                    $name_pro = $_POST['name_pro'];
                    $price = $_POST['price'];
                    $discount = $_POST['discount'];
                    $short_des = $_POST['short_des'];
                    $detail_des = $_POST['detail_des'];
                    $idcate = $_POST['idcate'];
                    $stock = $_POST['stock'] ?? 0;
                    $stock_message = trim($_POST['stock_message'] ?? '') ?: null;
                    $img_pro = basename($_FILES['img_pro']['name']);
                    $target_dir = "./uploads/";
                    $target_file = $target_dir . $img_pro;
                    $extension = strtolower(pathinfo($img_pro, PATHINFO_EXTENSION));

                    $allowed_extensions = array("jpg", "jpeg", "png", "gif");

                    if ($name_pro == null || $price == null || $short_des == null || $idcate == null) {
                        echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Vui lòng nhập đầy đủ nội dung !",showConfirmButton:false,timer:3000}));</script>';
                    } elseif ($price <= 0) {
                        echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Giá nhập không đúng !",showConfirmButton:false,timer:3000}));</script>';
                    } elseif ($stock < 0) {
                        echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Số lượng tồn kho không đúng !",showConfirmButton:false,timer:3000}));</script>';
                    } elseif (!in_array($extension, $allowed_extensions, true) || preg_match('/\.(php|phtml|pht)/i', $img_pro)) {
                        echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"File ảnh không phù hợp !",showConfirmButton:false,timer:3000}));</script>';
                    } elseif (!move_uploaded_file($_FILES["img_pro"]["tmp_name"], $target_file)) {
                        echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Tải ảnh lên thất bại !",showConfirmButton:false,timer:3000}));</script>';
                    } else {
                        add_pro($name_pro, $price, $discount, $img_pro, $short_des, $detail_des, $idcate, $stock, $stock_message);
                        echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:"Thêm sản phẩm thành công !",showConfirmButton:false,timer:3000}));</script>';
                    }
                }
                $ds_loai = loadall_loai();
                render(
                    'add_product',
                    ['ds_loai' => $ds_loai]
                );
            } else {
                header("location: index.php?act=login");
            }

            break;
        case "list_product":

            if (isset($_SESSION['admin'])) {
                if (isset($_POST['btn_filter']) && ($_POST['btn_filter'])) {
                    $idcate = $_POST['idcate'];
                } else {
                    $idcate = 0;
                }
                $ds_loai = loadall_loai();
                $listpro = loadall_pro($idcate);
                $flash_error = $_SESSION['flash_error'] ?? null;
                $flash_success = $_SESSION['flash_success'] ?? null;
                unset($_SESSION['flash_error'], $_SESSION['flash_success']);
                render(
                    "list_product",
                    ['ds_loai' => $ds_loai, 'listpro' => $listpro, 'idcate' => $idcate, 'flash_error' => $flash_error, 'flash_success' => $flash_success]
                );
            } else {
                header("location: index.php?act=login");
            }

            break;
        case "edit_product":

            if (isset($_SESSION['admin'])) {
                if (isset($_GET['id_pro']) && $_GET['id_pro'] > 0) {
                    $id_pro = $_GET['id_pro'];
                    $pro = loadone_pro($id_pro);
                }

                $ds_loai = loadall_loai();
                render(
                    'update_product',
                    ['ds_loai' => $ds_loai, 'pro' => $pro]
                );
            } else {
                header("location: index.php?act=login");
            }

            break;
        case "update_product":
            if (isset($_SESSION['admin'])) {
                if (isset($_POST['btn_update']) && $_POST['btn_update'] > 0) {
                    $id_pro = $_POST['id_pro'];
                    $idcate = $_POST['idcate'];
                    $name_pro = $_POST['name_pro'];
                    $price = $_POST['price'];
                    $discount = $_POST['discount'];
                    $short_des = $_POST['short_des'];
                    $detail_des = $_POST['detail_des'];
                    $stock = $_POST['stock'] ?? 0;
                    $stock_message = trim($_POST['stock_message'] ?? '') ?: null;
                    $img_pro = '';
                    if (!empty($_FILES['img_pro']['tmp_name'])) {
                        $new_name = basename($_FILES['img_pro']['name']);
                        $extension = strtolower(pathinfo($new_name, PATHINFO_EXTENSION));
                        $allowed_extensions = array("jpg", "jpeg", "png", "gif");
                        if (in_array($extension, $allowed_extensions, true) && !preg_match('/\.(php|phtml|pht)/i', $new_name)) {
                            $target_dir = "./uploads/";
                            $target_file = $target_dir . $new_name;
                            if (move_uploaded_file($_FILES["img_pro"]["tmp_name"], $target_file)) {
                                $img_pro = $new_name;
                            }
                        }
                    }
                    update_pro($id_pro, $name_pro, $price, $discount, $short_des, $detail_des, $img_pro, $idcate, $stock, $stock_message);
                    $_SESSION['flash_success'] = 'Cập nhật sản phẩm thành công!';
                    header('location:index.php?act=list_product');
                }
            } else {
                header("location: index.php?act=login");
            }
            break;
        case "delete_product":
            if (isset($_SESSION['admin'])) {
                if (isset($_GET['id_pro']) && ($_GET['id_pro']) > 0) {
                    $id_pro = $_GET['id_pro'];
                    try {
                        remove_pro($id_pro);
                    } catch (PDOException $e) {
                        // SQLSTATE 23000: product already referenced by an order
                        // line in `cart` (lk_pro_cart) — deleting it would break
                        // that order's history, so block it with a clear message
                        // instead of the raw fatal error.
                        if ($e->getCode() === '23000') {
                            $_SESSION['flash_error'] = 'Không thể xoá sản phẩm này vì đã có đơn hàng liên quan.';
                        } else {
                            throw $e;
                        }
                    }
                }
                header('location:index.php?act=list_product');
            } else {
                header("location: index.php?act=login");
            }
            break;

        // CONTROLLER NGƯỜI DÙNG: 
        // danh sách người dùng
        case 'list_user':

            if (isset($_SESSION['admin'])) {
                $listuser = loadall_user();
                $flash_success = $_SESSION['flash_success'] ?? null;
                unset($_SESSION['flash_success']);
                render(
                    'list_user',
                    ['listuser' => $listuser, 'flash_success' => $flash_success]
                );
            } else {
                header("location: index.php?act=login");
            }

            break;
        // chỉnh sửa user
        case 'edit_user':

            if (isset($_SESSION['admin'])) {
                if (isset($_GET['id_user']) && ($_GET['id_user'] > 0)) {
                    $id_user = $_GET['id_user'];
                    $user = loadone_user($id_user);
                }
                render(
                    'update_user',
                    ['user' => $user]
                );
            } else {
                header("location: index.php?act=login");
            }

            break;
        case 'update_user':
            if (isset($_SESSION['admin'])) {
                if (isset($_POST['btn_update']) && ($_POST['btn_update'])) {
                    $id_user = $_POST['id_user'];
                    $user_name = trim($_POST['user_name']);
                    $full_name = trim($_POST['full_name']);
                    $email_user = trim($_POST['email_user']);
                    $password = $_POST['password'];
                    $role = $_POST['role'];
                    if ($user_name === '' || $full_name === '') {
                        echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Vui lòng nhập đầy đủ nội dung !",showConfirmButton:false,timer:3000}));</script>';
                        $user = loadone_user($id_user);
                        render('update_user', ['user' => $user]);
                        break;
                    } elseif (!filter_var($email_user, FILTER_VALIDATE_EMAIL)) {
                        echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Email không hợp lệ !",showConfirmButton:false,timer:3000}));</script>';
                        $user = loadone_user($id_user);
                        render('update_user', ['user' => $user]);
                        break;
                    } elseif ($password !== '' && strlen($password) < 6) {
                        // Blank is allowed (means "keep current password");
                        // only enforce the minimum length when actually changing it.
                        echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Mật khẩu phải có ít nhất 6 ký tự !",showConfirmButton:false,timer:3000}));</script>';
                        $user = loadone_user($id_user);
                        render('update_user', ['user' => $user]);
                        break;
                    }
                    update_user($id_user, $user_name, $full_name, $email_user, $password === '' ? null : $password, $role);
                    $_SESSION['flash_success'] = 'Cập nhật tài khoản thành công!';
                }
                header('location: index.php?act=list_user');
            } else {
                header("location: index.php?act=login");
            }
            break;
        // Xóa người dùng
        case "delete_usser":
            if (isset($_SESSION['admin'])) {
                if (isset($_GET['id_user']) && ($_GET['id_user'] > 0)) {
                    $id_user = $_GET['id_user'];
                    delete_user($id_user);
                }
                header('location:index.php?act=list_user');
            } else {
                header("location: index.php?act=login");
            }
            break;

        //CONTROLLER HÓA ĐƠN

        // show all bill
        case 'list_bill':
            if (isset($_SESSION['admin'])) {
                $status = -1;
                $keyword = "";
                $from_date = "";
                $to_date = "";
                if (isset($_POST['btn_filter'])) {
                    if (isset($_POST['status'])) {
                        $status = (int)$_POST['status'];
                    }
                    if (isset($_POST['keyword'])) {
                        $keyword = trim($_POST['keyword']);
                    }
                    if (isset($_POST['from_date'])) {
                        $from_date = $_POST['from_date'];
                    }
                    if (isset($_POST['to_date'])) {
                        $to_date = $_POST['to_date'];
                    }
                }
                $listbill = loadall_bill(0, $status, $keyword, $from_date, $to_date);
                $flash_error = $_SESSION['flash_error'] ?? null;
                $flash_success = $_SESSION['flash_success'] ?? null;
                unset($_SESSION['flash_error'], $_SESSION['flash_success']);
                render('list_bill', [
                    'listbill' => $listbill,
                    'status' => $status,
                    'keyword' => $keyword,
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'flash_error' => $flash_error,
                    'flash_success' => $flash_success
                ]);
            } else {
                header("location: index.php?act=login");
            }
            break;
        //     xóa bill: 
        // case 'removebill':
        //     if (isset($_GET['idbill']) && ($_GET['idbill'])) {
        //         $idbill = $_GET['idbill'];
        //         remove_bill($idbill);
        //     }
        //     $listbill = loadall_bill(0);
        //     include "view/hoadon/list.php";
        //     break;
        case 'edit_bill':
            if (isset($_SESSION['admin'])) {
                $one_bill = (isset($_GET['idbill']) && ($_GET['idbill']) > 0)
                    ? loadone_bill($_GET['idbill'])
                    : false;

                if (!is_array($one_bill)) {
                    $_SESSION['flash_error'] = 'Không tìm thấy đơn hàng này.';
                    header('location:index.php?act=list_bill');
                    break;
                }

                render(
                    'update_bill',
                    ['one_bill' => $one_bill]
                );
            } else {
                header("location: index.php?act=login");
            }

            break;
        case 'update_bill':
            if (isset($_SESSION['admin'])) {
                if (isset($_POST['btn_update']) && ($_POST['btn_update'])) {
                    $id_bill = $_POST['id_bill'];
                    $status = $_POST['status'];
                    $status_pay = $_POST['status_pay'];
                    if ($status == 3) {
                        $status_pay = 1;
                    }
                    update_bill($id_bill, $status, $status_pay);
                    $_SESSION['flash_success'] = 'Cập nhật đơn hàng thành công!';
                    header('location:index.php?act=list_bill');
                }
            } else {
                header("location: index.php?act=login");
            }
            break;
        case 'approve_bill':
            if (isset($_SESSION['admin'])) {
                if (isset($_GET['idbill']) && ($_GET['idbill'] > 0)) {
                    $idbill = $_GET['idbill'];
                    $bill = loadone_bill($idbill);
                    if ($bill && $bill['status'] == 0) {
                        update_bill($idbill, '1', $bill['status_pay']);
                        $_SESSION['flash_success'] = 'Đã duyệt đơn hàng thành công!';
                    }
                }
                header('location:index.php?act=list_bill');
            } else {
                header("location: index.php?act=login");
            }
            break;
        case 'ship_bill':
            if (isset($_SESSION['admin'])) {
                if (isset($_GET['idbill']) && ($_GET['idbill'] > 0)) {
                    $idbill = $_GET['idbill'];
                    $bill = loadone_bill($idbill);
                    if ($bill && $bill['status'] == 1) {
                        update_bill($idbill, '2', $bill['status_pay']);
                        $_SESSION['flash_success'] = 'Đã chuyển sang đang giao hàng!';
                    }
                }
                header('location:index.php?act=list_bill');
            } else {
                header("location: index.php?act=login");
            }
            break;
        case 'cancel_bill':
            if (isset($_SESSION['admin'])) {
                if (isset($_GET['idbill']) && ($_GET['idbill'] > 0)) {
                    $idbill = $_GET['idbill'];
                    $bill = loadone_bill($idbill);
                    if ($bill && ($bill['status'] == 0 || $bill['status'] == 1)) {
                        update_bill($idbill, '4', $bill['status_pay']);
                        $_SESSION['flash_success'] = 'Đã hủy đơn hàng!';
                    }
                }
                header('location:index.php?act=list_bill');
            } else {
                header("location: index.php?act=login");
            }
            break;
        case 'billdetail':
            if (isset($_SESSION['admin'])) {
                $one_bill = (isset($_GET['idbill']) && ($_GET['idbill']) > 0)
                    ? loadone_bill($_GET['idbill'])
                    : false;

                if (!is_array($one_bill)) {
                    $_SESSION['flash_error'] = 'Không tìm thấy đơn hàng này.';
                    header('location:index.php?act=list_bill');
                    break;
                }

                render(
                    'billdetail',
                    ['one_bill' => $one_bill]
                );
            } else {
                header("location: index.php?act=login");
            }

            break;
        //CONTROLLER BÌNH LUẬN
        //show list: 
        case 'list_cmt':
            if (isset($_SESSION['admin'])) {
                $listcmt = loadall_cmt();
                render(
                    'list_comment',
                    ['listcmt' => $listcmt]
                );
            } else {
                header("location: index.php?act=login");
            }

            break;
        //xóa bì-nh luận: 
        case 'delete_cmt':
            if (isset($_SESSION['admin'])) {
                if (isset($_GET['idcmt']) && ($_GET['idcmt']) > 0) {
                    $id_cmt = $_GET['idcmt'];
                    remove_cmt($id_cmt);
                }
                header('location: index.php?act=list_cmt');
            } else {
                header("location: index.php?act=login");
            }
            break;

        //CONTROLLER THỐNG KÊ
        // Danh sách thống kê
        case 'list_thongke':
            if (isset($_SESSION['admin'])) {
                $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
                $to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';
                $sort_product = isset($_POST['sort_product']) ? $_POST['sort_product'] : 'DESC';
                
                $revenue_stats = get_revenue_by_date($from_date, $to_date);
                $product_sold_stats = get_products_sold_by_date($from_date, $to_date, $sort_product);
                $inventory_stats = get_inventory();
                
                render('list_statistic', [
                    'revenue_stats' => $revenue_stats, 
                    'product_sold_stats' => $product_sold_stats,
                    'inventory_stats' => $inventory_stats,
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'sort_product' => $sort_product
                ]);
            } else {
                header("location: index.php?act=login");
            }
            break;
        // Danh sách hỏi đáp
        case 'list_ques':
            if (isset($_SESSION['admin'])) {
                $listques = question();
                render(
                    'list_question',
                    ['listques' => $listques]
                );
            } else {
                header("location: index.php?act=login");
            }
            break;
        //xóa hỏi đáp: 
        case 'delete_ques':
            if (isset($_SESSION['admin'])) {
                if (isset($_GET['id_ques']) && ($_GET['id_ques']) > 0) {
                    $id_ques = $_GET['id_ques'];
                    delete_ques($id_ques);
                }
                header('location: index.php?act=list_ques');
            } else {
                header("location: index.php?act=login");
            }
            break;

        case 'list_coupon':
            if (isset($_SESSION['admin'])) {
                $listcoupon = loadall_coupon();
                $flash_success = $_SESSION['flash_success'] ?? null;
                unset($_SESSION['flash_success']);
                render('list_coupon', ['listcoupon' => $listcoupon, 'flash_success' => $flash_success]);
            } else {
                header("location: index.php?act=login");
            }
            break;

        case 'add_coupon':
            if (isset($_SESSION['admin'])) {
                if (isset($_POST['btn_add'])) {
                    $code = $_POST['code'];
                    $discount_type = $_POST['discount_type'];
                    $discount_value = $_POST['discount_value'];
                    $max_discount = $_POST['max_discount'];
                    $min_order_value = $_POST['min_order_value'];
                    $product_id = $_POST['product_id'];
                    $start_date = $_POST['start_date'];
                    $end_date = $_POST['end_date'];
                    $usage_limit = $_POST['usage_limit'];
                    $status = $_POST['status'];
                    insert_coupon($code, $discount_type, $discount_value, $max_discount, $min_order_value, $product_id, $start_date, $end_date, $usage_limit, $status);
                    echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:"Thêm mã giảm giá thành công!",showConfirmButton:false,timer:3000}));</script>';
                }
                $listpro = loadall_pro(); // To select product to apply
                render('add_coupon', ['listpro' => $listpro]);
            } else {
                header("location: index.php?act=login");
            }
            break;

        case 'delete_coupon':
            if (isset($_SESSION['admin'])) {
                if (isset($_GET['id_coupon']) && ($_GET['id_coupon']) > 0) {
                    $id_coupon = $_GET['id_coupon'];
                    delete_coupon($id_coupon);
                    $_SESSION['flash_success'] = 'Xóa mã thành công!';
                }
                header('location: index.php?act=list_coupon');
            } else {
                header("location: index.php?act=login");
            }
            break;

        case 'edit_coupon':
            if (isset($_SESSION['admin'])) {
                if (isset($_GET['id_coupon']) && ($_GET['id_coupon']) > 0) {
                    $id_coupon = $_GET['id_coupon'];
                    $one_coupon = loadone_coupon($id_coupon);
                }
                $listpro = loadall_pro();
                render('update_coupon', ['one_coupon' => $one_coupon, 'listpro' => $listpro]);
            } else {
                header("location: index.php?act=login");
            }
            break;

        case 'update_coupon':
            if (isset($_SESSION['admin'])) {
                if (isset($_POST['btn_update'])) {
                    $id_coupon = $_POST['id_coupon'];
                    $code = $_POST['code'];
                    $discount_type = $_POST['discount_type'];
                    $discount_value = $_POST['discount_value'];
                    $max_discount = $_POST['max_discount'];
                    $min_order_value = $_POST['min_order_value'];
                    $product_id = $_POST['product_id'];
                    $start_date = $_POST['start_date'];
                    $end_date = $_POST['end_date'];
                    $usage_limit = $_POST['usage_limit'];
                    $status = $_POST['status'];
                    update_coupon($id_coupon, $code, $discount_type, $discount_value, $max_discount, $min_order_value, $product_id, $start_date, $end_date, $usage_limit, $status);
                    $_SESSION['flash_success'] = 'Cập nhật mã giảm giá thành công!';
                }
                header('location: index.php?act=list_coupon');
            } else {
                header("location: index.php?act=login");
            }
            break;

        default:
            if (isset($_SESSION['admin'])) {
                $flash_success = $_SESSION['flash_success'] ?? null;
                unset($_SESSION['flash_success']);
                render('dashboard', ['flash_success' => $flash_success]);
            } else {
                header("location: index.php?act=login");
            }
            // render('dashboard');
    }
} else {
    if (isset($_SESSION['admin'])) {
        $flash_success = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_success']);
        render('dashboard', ['flash_success' => $flash_success]);
    } else {
        header("location: index.php?act=login");
    }
    // render('dashboard');
}

