<?php include_once "header.php" ?>
<?php /** @var array $listques */ ?>


<div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-slate-800">Quản Lý Hỏi đáp</h1>
</div>

<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden mb-6">
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr
                        class="bg-slate-50 border-y border-slate-100 text-slate-600 text-sm font-semibold tracking-wide uppercase">
                        <th class="px-4 py-4">Mã hỏi đáp</th>
                        <th class="px-4 py-4">Họ và tên</th>
                        <th class="px-4 py-4">Email</th>
                        <th class="px-4 py-4">Số điện thoại</th>
                        <th class="px-4 py-4">Nội dung</th>
                        <th class="px-4 py-4 text-center">Thao tác</th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    <?php
                                foreach ($listques as $ques) : extract($ques); ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-4 text-slate-500 font-medium">#<?= $id_ques ?></td>
                        <td class="px-4 py-4 font-medium text-slate-800"><?= $name ?></td>
                        <td class="px-4 py-4"><?= $email ?></td>
                        <td class="px-4 py-4"><?= $phone ?></td>
                        <td class="px-4 py-4"><?= $contennt ?></td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex items-center justify-center">
                                <a href="./index.php?act=delete_ques&id_ques=<?= $ques['id_ques'] ?>"
                                    class="p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-all active:scale-90"
                                    data-confirm="Bạn có chắc chắn muốn xóa không?" title="Xóa"><i
                                        class="fa-solid fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>


    <script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/vi.json"
            },
            "dom": '<"flex flex-col md:flex-row justify-between items-center mb-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-4"ip>',
        });

        setTimeout(function() {
            $('.dataTables_length select').addClass(
                'px-3 py-1 border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500 mx-2'
                );
            $('.dataTables_filter input').addClass(
                'px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500 ml-2'
                );
        }, 100);
    });
    </script>

    <?php include_once "footer.php" ?>