<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

/**
 * Ported from `Codemoi\Controller\QuestionController` (legacy
 * `src/Controller/QuestionController.php`).
 */
class QuestionController extends Controller
{
    public function index()
    {
        return view('pages.question', [
            'metaTitle' => 'Hỏi đáp - Turbotech',
            'metaDescription' => 'Gửi câu hỏi cho đội ngũ Turbotech để được tư vấn về sản phẩm laptop gaming và PC.',
        ]);
    }

    public function submit(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string'],
            'contennt' => ['required', 'string'],
        ]);

        Question::create($data);

        return back()->with('flash_success', 'Gửi câu hỏi thành công !');
    }
}