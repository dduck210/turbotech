<?php

/** @var array $listcmt */ ?>
<div id="layoutSidenav_content">

    <div class="container-fluid px-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6 mt-4">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 font-semibold text-slate-800">
                <i class="fas fa-table me-1"></i>
                Danh sách bình luận
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600" id="datatablesSimple">
                        <thead class="bg-slate-50 text-slate-700 uppercase text-xs font-semibold">
                            <tr>
                                <th class="px-4 py-3">Mã bình luận</th>
                                <th class="px-4 py-3">Người bình luận</th>
                                <th class="px-4 py-3">Sản phẩm</th>
                                <th class="px-4 py-3">Nội dung</th>
                                <th class="px-4 py-3">Ngày bình luận</th>
                                <th class="px-4 py-3 text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tfoot class="bg-slate-50 text-slate-700 uppercase text-xs font-semibold">
                            <tr>
                                <th class="px-4 py-3">Mã bình luận</th>
                                <th class="px-4 py-3">Người bình luận</th>
                                <th class="px-4 py-3">Sản phẩm</th>
                                <th class="px-4 py-3">Nội dung</th>
                                <th class="px-4 py-3">Ngày bình luận</th>
                                <th class="px-4 py-3 text-center">Thao tác</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            foreach ($listcmt as $cmt): extract($cmt); ?>
                                <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3"><?= $id_cmt ?></td>
                                    <td class="px-4 py-3"><?= $user_name ?></td>
                                    <td class="px-4 py-3"><?= $name_pro ?></td>
                                    <td class="px-4 py-3"><?= $content ?></td>
                                    <td class="px-4 py-3"><?= $comment_date ?></td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="index.php?act=removecmt&idcmt=<?= $id_cmt ?>" class="bg-red-500 hover:bg-red-600 text-white rounded-lg px-3 py-1.5 inline-block" data-confirm="Bạn có chắc chắn muốn xóa?"><i class="fa-solid fa-trash"></i> Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>