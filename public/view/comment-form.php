<?php
session_start();
require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/Core/helpers.php';

registerErrorHandler();

use Codemoi\Model\Comment;
use Codemoi\Model\Order;

$idpro = $_REQUEST['idpro'];
$listcmt = Comment::forProduct($idpro);
// Reviews are limited to verified purchasers (an order for this product
// that's actually been delivered) — checked again server-side in the
// submit handler below, not just to decide whether to show the form.
$canReview = isset($_SESSION['user']) && Order::hasDeliveredPurchase((int) $_SESSION['user']['id_user'], (int) $idpro);
$flash_success = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" media="screen" href="../assets/css/tailwind.css" />
    <link rel="shortcut icon" type="../assets/imagex-icon" href="../assets/images/menu/logo/logo-url.jpg" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-ink-50 font-sans text-ink-900 antialiased">
    <?php if ($flash_success): ?>
    <script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:<?= json_encode($flash_success) ?>,showConfirmButton:false,timer:3000}));</script>
    <?php endif; ?>
    <?php
    if (isset($_POST['btn_cmt']) && $_POST['btn_cmt']) {
        $content = $_POST['content_cmt'];
        $idpro = $_POST['idpro'];
        $id_user = $_SESSION['user']['id_user'];
        $user_name = $_SESSION['user']['user_name'];
        $full_name = $_SESSION['user']['full_name'];
        $comment_date = date("m/d/Y h:i:sa");
        if (!$canReview) {
            echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Bạn cần mua và nhận sản phẩm này để đánh giá !",showConfirmButton:false,timer:3000}));</script>';
        } elseif ($content == null) {
            echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Không được để trống !",showConfirmButton:false,timer:3000}));</script>';
        } else {
            Comment::create($content, $id_user, $user_name, $full_name, $idpro, $comment_date);
            $_SESSION['flash_success'] = 'Cảm ơn bạn đã đánh giá!';
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }
    ?>
    <div class="product_comments_block space-y-4 p-4">
        <?php foreach ($listcmt as $cmt) : extract($cmt); ?>
            <div class="comment_details same-stuff rounded-2xl border border-ink-200 bg-white p-4 shadow-sm">
                <div class="flex flex-wrap items-baseline gap-2">
                    <span class="user-id font-heading font-semibold text-ink-900"><?= e($full_name) ?> (<?= e($user_name) ?>)</span>
                    <em class="text-xs text-ink-500 not-italic"><?= e($comment_date) ?></em>
                </div>
                <em class="user-comment mt-1.5 block text-sm text-ink-700 not-italic"><?= e($content) ?></em>
            </div>
        <?php endforeach ?>
        <!-- Form bình luận-->

        <div class="comment-btn-area mt-3">
            <?php if ($canReview) { ?>
                <form action="<?= e($_SERVER['PHP_SELF']) ?>" method="post" data-validate novalidate class="rounded-2xl border border-ink-200 bg-white p-4 shadow-sm">
                    <?= \Codemoi\Core\Csrf::field() ?>
                    <label for="content_cmt" class="mb-1.5 block text-sm font-medium text-ink-700">Bình luận của bạn</label>
                    <textarea id="content_cmt" name="content_cmt" data-rules="required|min:2" class="area-cmt block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500" cols="60" rows="3" placeholder="Nhập bình luận của bạn"></textarea>
                    <input type="hidden" name="idpro" value="<?= e($idpro) ?>">
                    <div class="mt-3">
                        <input type="submit" name="btn_cmt" value="Gửi" class="ip-cmt inline-flex cursor-pointer items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                    </div>
                </form>
            <?php } elseif (!isset($_SESSION['user'])) { ?>
                <div class="rounded-lg border border-brand-100 bg-brand-50 p-3 text-sm text-brand-700">
                    <p class="alert alert-primary fs-6">Vui lòng đăng nhập để bình luận !</p>
                </div>
            <?php } else { ?>
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-3 text-sm text-amber-800">
                    <p>Bạn cần mua và nhận sản phẩm này để có thể đánh giá.</p>
                </div>
            <?php } ?>
        </div>

        <!-- End bình luận -->
    </div>
    <script src="../assets/js/plugins.min.js"></script>
    <script src="../assets/js/ajax-mail.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/form-validate.js"></script>
</body>

</html>