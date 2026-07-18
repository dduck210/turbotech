<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * Ported from `Codemoi\Controller\Admin\QuestionController` (legacy
 * `src/Controller/Admin/QuestionController.php`). Submissions aren't tied
 * to a logged-in account, so a reply is emailed to the submitted address
 * (via Laravel's Mail facade instead of the legacy vendored PHPMailer)
 * rather than shown anywhere in the client UI.
 */
class QuestionController extends Controller
{
    public function index()
    {
        return view('admin.question.index', ['questions' => Question::orderByDesc('id_ques')->paginate(15)]);
    }

    public function destroy(int $id)
    {
        Question::where('id_ques', $id)->delete();

        return redirect()->route('admin.questions.index')->with('flash_success', 'Xoá câu hỏi thành công!');
    }

    public function showReply(int $id)
    {
        return view('admin.question.reply', ['question' => Question::findOrFail($id)]);
    }

    public function sendReply(Request $request, int $id)
    {
        $question = Question::findOrFail($id);
        $data = $request->validate(['reply' => ['required', 'string', 'min:2']]);

        $question->update(['reply' => $data['reply'], 'replied_at' => now()]);

        Mail::html(
            '<p>Xin chào '.e($question->name).',</p>'.
            '<p>Câu hỏi của bạn: <em>'.nl2br(e($question->contennt)).'</em></p>'.
            '<p>Trả lời từ Turbotech:<br>'.nl2br(e($data['reply'])).'</p>',
            function ($message) use ($question) {
                $message->to($question->email)->subject('Turbotech đã trả lời câu hỏi của bạn');
            }
        );

        return redirect()->route('admin.questions.index')->with('flash_success', 'Đã gửi trả lời qua email!');
    }
}
