<?php
session_start();
require __DIR__ . '/../../vendor/autoload.php';

use Codemoi\Model\Comment;
use Codemoi\Model\Order;

$idpro = $_REQUEST['idpro'];
$listcmt = Comment::forProduct($idpro);
// Reviews are limited to verified purchasers (an order for this product
// that's actually been delivered) — checked again server-side in the
// submit handler below, not just to decide whether to show the form.
$canReview = isset($_SESSION['user']) && Order::hasDeliveredPurchase((int) $_SESSION['user']['id_user'], (int) $idpro);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" media="screen" href="./src/css/tailwind.css" />
    <link rel="shortcut icon" type="./src/imagex-icon" href="./src/image/menu/logo/logo-url.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-ink-50 font-sans text-ink-900 antialiased">
<?php
        if (isset($_POST['btn_cmt']) && $_POST['btn_cmt']) {
            $content = $_POST['content_cmt'];
            $idpro = $_POST['idpro'];
            $id_user = $_SESSION['user']['id_user'];
            $user_name = $_SESSION['user']['user_name'];
            $full_name = $_SESSION['user']['full_name'];
            $comment_date = date("m/d/Y h:i:sa");
            if (!$canReview) {
                echo '<script>alert("Bạn cần mua và nhận sản phẩm này để đánh giá !")</script>';
            } elseif ($content == null) {
                echo '<script>alert("không được để trống !")</script>';
            } else {
                Comment::create($content, $id_user, $user_name, $full_name, $idpro, $comment_date);
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        }
        ?>
    <div class="product_comments_block space-y-4 p-4">
        <?php foreach ($listcmt as $cmt) : extract($cmt); ?>
            <div class="comment_details same-stuff rounded-2xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl p-4 shadow-sm">
                <div class="flex flex-wrap items-baseline gap-2">
                    <span class="user-id font-heading font-semibold text-ink-900"><?= $full_name ?> (<?= $user_name ?>)</span>
                    <em class="text-xs text-ink-500 not-italic"><?= $comment_date ?></em>
                </div>
                <em class="user-comment mt-1.5 block text-sm text-ink-700 not-italic"><?= $content ?></em>
            </div>
        <?php endforeach ?>
        <!-- Form bình luận-->

        <div class="comment-btn-area mt-3">
        <?php if ($canReview) { ?>
            <form action="<?= $_SERVER['PHP_SELF']; ?>" method="post" data-validate novalidate class="rounded-2xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl p-4 shadow-sm">
                <label for="content_cmt" class="mb-1.5 block text-sm font-medium text-ink-700">Bình luận của bạn</label>
                <textarea id="content_cmt" name="content_cmt" data-rules="required|min:2" class="area-cmt block w-full rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500" cols="60" rows="3" placeholder="Nhập bình luận của bạn"></textarea>
                <input type="hidden" name="idpro" value="<?= $idpro ?>">
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
    <script src="./src/js/plugins.min.js"></script>
    <script src="./src/js/ajax-mail.js"></script>
    <script src="./src/js/main.js"></script>
    <script src="./src/js/form-validate.js"></script>
</body>

</html>