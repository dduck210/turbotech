<?php
session_start();
// ob_start();

require_once 'pdo.php';
require_once 'function.php';

    $result = curl_get("https://doquanglinh.com/api/index.php");
    $result = json_decode($result, true);
    foreach($result['transactionHistoryList'] as $data)
    {
        $comment        = $data['description'];             // NỘI DUNG CHUYỂN TIỀN
        $tranId         = $data['refNo'];                 // MÃ GIAO DỊCH
        $id             = parse_order_id($comment);         // TÁCH NỘI DUNG CHUYỂN TIỀN
        $amount         = $data['creditAmount'];
        $money          = $amount;
        $time           = $data['transactionDate'];    
        //Xử lý giao dịcha
        if ($id)
        {
            // echo $id ." ";
            $check_code = checkcode($tranId);
            $check_username = checkbill($id);
            if(isset($check_username) && $amount > 0)
            {
                // print_r($check_username) ;
                if(empty($check_code))
                {
                    print_r($check_code);
                    echo $id ." ";
                    $money = str_replace(',', '', $money);
                    $sql = "INSERT INTO history_bank (tranid,amount,comment,time) VALUES (?, ?, ?, ?)";
                    pdo_execute($sql, $tranId, $money, $comment, $time);
                    $sqll = "UPDATE `bill` SET status = 1 , status_pay = 1 WHERE bill_code = ?";
                    pdo_execute($sqll, $id);
                    $_SESSION['check'] = 2;
                }
            }
        }
    }
    echo "<div>Xử lý hoàn tất</div>";
