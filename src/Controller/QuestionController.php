<?php

namespace Codemoi\Controller;

use Codemoi\Core\Controller;
use Codemoi\Core\Seo;
use Codemoi\Model\Question;

/**
 * Hỏi đáp (contact/question form). Ported from `index.php` case
 * `'question'` (`index.php:375-385`).
 */
class QuestionController extends Controller
{
    public function index(): void
    {
        Seo::setTitle('Hỏi đáp - Turbotech');
        Seo::setDescription('Gửi câu hỏi cho đội ngũ Turbotech để được tư vấn về sản phẩm laptop gaming và PC.');

        if (isset($_POST['btn_question']) && $_POST['btn_question']) {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $contennt = $_POST['contennt'] ?? '';

            Question::create($name, $email, $phone, $contennt);
            echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:"Gửi câu hỏi thành công !",showConfirmButton:false,timer:3000}));</script>';
        }

        $this->view('qa/question');
    }
}
