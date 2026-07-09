<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Codemoi\Core\Database;
use Codemoi\Model\Payment;

session_start();
// ob_start();

$result = Payment::curlGet("https://doquanglinh.com/api/index.php");
$result = json_decode($result, true);
foreach ($result['transactionHistoryList'] as $data) {
    $comment = $data['description'];             // NỘI DUNG CHUYỂN TIỀN
    $tranId = $data['refNo'];                    // MÃ GIAO DỊCH
    $id = Payment::parseOrderId($comment);       // TÁCH NỘI DUNG CHUYỂN TIỀN
    $amount = $data['creditAmount'];
    $money = $amount;
    $time = $data['transactionDate'];
    // Xử lý giao dịch
    if ($id) {
        // echo $id ." ";
        $check_code = Payment::findByTran($tranId);
        $check_username = Payment::findBillByCode($id);
        if (isset($check_username) && $amount > 0) {
            // print_r($check_username) ;
            if (empty($check_code)) {
                print_r($check_code);
                echo $id . " ";
                $money = str_replace(',', '', $money);
                $sql = "INSERT INTO history_bank (tranid,amount,comment,time) VALUES (?, ?, ?, ?)";
                Database::execute($sql, $tranId, $money, $comment, $time);
                $sqll = "UPDATE `bill` SET status = 1 , status_pay = 1 WHERE bill_code = ?";
                Database::execute($sqll, $id);
                $_SESSION['check'] = 2;
            }
        }
    }
}
echo "<div>Xử lý hoàn tất</div>";
