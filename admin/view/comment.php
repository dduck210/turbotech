<?php

/** @var array $listcmt */ ?>
<div id="layoutSidenav_content">

    <div class="container-fluid px-4">
        <div class="card-boutique rounded-lg overflow-hidden mb-6 mt-4">
            <div class="px-6 py-4 border-b border-ink-300">
                <h2 class="font-heading text-lg text-ink-900"><i class="fas fa-table mr-2 text-brand-500"></i>
                    Danh sách bình luận</h2>
                <span class="block w-8 h-px bg-brand-500 mt-2"></span>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-ink-600" id="datatablesSimple">
                        <thead>
                            <tr class="border-b-2 border-ink-300 text-ink-600 text-xs font-semibold tracking-widest uppercase">
                                <th class="px-4 py-3">Mã bình luận</th>
                                <th class="px-4 py-3">Người bình luận</th>
                                <th class="px-4 py-3">Sản phẩm</th>
                                <th class="px-4 py-3">Nội dung</th>
                                <th class="px-4 py-3">Ngày bình luận</th>
                                <th class="px-4 py-3 text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="border-t-2 border-ink-300 text-ink-600 text-xs font-semibold tracking-widest uppercase">
                                <th class="px-4 py-3">Mã bình luận</th>
                                <th class="px-4 py-3">Người bình luận</th>
                                <th class="px-4 py-3">Sản phẩm</th>
                                <th class="px-4 py-3">Nội dung</th>
                                <th class="px-4 py-3">Ngày bình luận</th>
                                <th class="px-4 py-3 text-center">Thao tác</th>
                            </tr>
                        </tfoot>
                        <tbody class="divide-y divide-ink-200">
                            <?php
                            foreach ($listcmt as $cmt): extract($cmt); ?>
                                <tr class="hover:bg-ink-100/60 transition-colors">
                                    <td class="px-4 py-3"><?= $id_cmt ?></td>
                                    <td class="px-4 py-3"><?= $user_name ?></td>
                                    <td class="px-4 py-3"><?= $name_pro ?></td>
                                    <td class="px-4 py-3"><?= $content ?></td>
                                    <td class="px-4 py-3"><?= $comment_date ?></td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="index.php?act=removecmt&idcmt=<?= $id_cmt ?>" class="border border-red-300 text-red-600 hover:bg-red-50 rounded-md px-3 py-1.5 inline-block transition-colors" data-confirm="Bạn có chắc chắn muốn xóa?"><i class="fa-solid fa-trash"></i> Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
