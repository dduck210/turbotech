<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;

/**
 * Ported from `Codemoi\Controller\Admin\CommentController` (legacy
 * `src/Controller/Admin/CommentController.php`).
 */
class CommentController extends Controller
{
    public function index()
    {
        return view('admin.comment.index', [
            'comments' => Comment::with('product')->orderByDesc('id_cmt')->paginate(15),
        ]);
    }

    public function destroy(int $id)
    {
        Comment::where('id_cmt', $id)->delete();

        return redirect()->route('admin.comments.index')->with('flash_success', 'Xoá bình luận thành công!');
    }
}
