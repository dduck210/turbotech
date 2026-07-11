<?php

namespace Codemoi\Controller;

use Codemoi\Core\Controller;
use Codemoi\Model\Question;

/**
 * Static-ish content pages. Ported from `index.php` cases `'introduce'`
 * and `'contact'` (`index.php:430-444`).
 */
class PageController extends Controller
{
    public function introduce(): void
    {
        $this->view('introduce');
    }

    /** Contact form reuses the question-submission model, matching the
     * old `'contact'` case which called `question(...)` (`index.php:440`).
     */
    public function contact(): void
    {
        if (isset($_POST['btn_contact']) && $_POST['btn_contact']) {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $contennt = $_POST['contennt'] ?? '';

            Question::create($name, $email, $phone, $contennt);
            echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:"Gửi câu hỏi thành công !",showConfirmButton:false,timer:3000}));</script>';
        }

        $this->view('contact');
    }
}
