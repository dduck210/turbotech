<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

/**
 * Ported from `Codemoi\Controller\PageController` (legacy
 * `src/Controller/PageController.php`).
 */
class PageController extends Controller
{
    public function introduce()
    {
        return view('pages.introduce', [
            'metaTitle' => 'Giới thiệu - Turbotech',
            'metaDescription' => 'Tìm hiểu về Turbotech - đơn vị cung cấp laptop gaming và PC hiệu năng cao chính hãng, uy tín.',
        ]);
    }

    public function contact()
    {
        return view('pages.contact', [
            'metaTitle' => 'Liên hệ - Turbotech',
            'metaDescription' => 'Liên hệ với Turbotech để được tư vấn laptop gaming, PC và hỗ trợ sau bán hàng.',
        ]);
    }

    public function submitContact(Request $request)
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