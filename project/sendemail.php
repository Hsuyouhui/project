<?php
// 檔案位置：WEB/wk1/send_reminder.php

// 1. 引入資料庫連線
require_once 'assets_db.php';

// 2. 引入 Composer 套件 (往上一層找 vendor)
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// 3. 載入 .env (因為檔案在 wk1 同層，所以用 __DIR__)
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// 設定時區
date_default_timezone_set("Asia/Taipei");
$today = date("Y-m-d");

?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>發送逾期通知</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow border-0">
        <div class="card-header bg-danger text-white">
            <h4 class="m-0"><i class="fas fa-paper-plane"></i> 逾期通知發送報告</h4>
        </div>
        <div class="card-body">

<?php
// ★★★ SQL 查詢重點 ★★★
// 條件：狀態是 '借出' 且 預計歸還日 < 今天
$sql = "SELECT b.record_id, b.user_name, b.email, b.expected_return, i.name AS item_name
        FROM borrow_records b
        JOIN items i ON b.item_id = i.id
        WHERE b.status = '借出' AND b.expected_return < ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $today);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// 檢查有沒有逾期的人
$count = mysqli_num_rows($result);

if ($count > 0) {
    echo "<div class='alert alert-warning'>
            <i class='fas fa-exclamation-triangle'></i> 
            系統掃描到 <strong>$count</strong> 筆逾期紀錄，正在執行發送作業...
          </div>";
          
    echo "<ul class='list-group mb-4'>";

    while ($row = mysqli_fetch_assoc($result)) {
        // 呼叫下方的寄信函數
        $isSent = sendOverdueEmail($row['email'], $row['user_name'], $row['item_name'], $row['expected_return']);
        
        if ($isSent) {
            echo "<li class='list-group-item list-group-item-action d-flex justify-content-between align-items-center'>
                    <div>
                        <i class='fas fa-check-circle text-success me-2'></i>
                        <strong>{$row['user_name']}</strong> 
                        <span class='text-muted small'>({$row['item_name']})</span>
                    </div>
                    <span class='badge bg-success rounded-pill'>已發送</span>
                  </li>";
        } else {
            echo "<li class='list-group-item list-group-item-action list-group-item-danger d-flex justify-content-between align-items-center'>
                     <div>
                        <i class='fas fa-times-circle text-danger me-2'></i>
                        <strong>{$row['user_name']}</strong>
                        <span class='small'>({$row['email']})</span>
                    </div>
                    <span class='badge bg-danger rounded-pill'>失敗</span>
                  </li>";
        }
        
        flush(); // 嘗試即時輸出內容到瀏覽器
        sleep(1); // 避免寄太快被擋
    }
    echo "</ul>";
} else {
    echo "<div class='text-center py-5'>
            <div class='mb-3 text-success' style='font-size: 4rem;'><i class='fas fa-smile-beam'></i></div>
            <h3>太棒了！</h3>
            <p class='text-muted'>目前沒有任何逾期未還的物品。</p>
          </div>";
}

// --- 寄信函數 (封裝) ---
function sendOverdueEmail($to, $name, $itemName, $dueDate) {
    $mail = new PHPMailer(true);
    try {
        // SMTP 設定
        $mail->isSMTP();
        $mail->Host       = 'smtp.office365.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['M365_USER'];
        $mail->Password   = $_ENV['M365_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // 收件人
        $mail->setFrom($_ENV['M365_USER'], '資產借用系統');
        $mail->addAddress($to, $name);

        // 內容
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = '【逾期催繳】您借用的公物已過期';
        
        // 漂亮的 HTML 信件範本
        $mail->Body    = "
            <div style='background-color: #f8f9fa; padding: 20px; font-family: sans-serif;'>
                <div style='max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>
                    <h2 style='color: #dc3545; border-bottom: 2px solid #dc3545; padding-bottom: 10px;'>逾期歸還通知</h2>
                    <p>親愛的 <strong>$name</strong> 您好：</p>
                    <p>系統提醒您，您借用的以下物品已經逾期，請盡速歸還。</p>
                    
                    <table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>
                        <tr style='background: #f1f1f1;'>
                            <td style='padding: 10px; border: 1px solid #ddd;'>物品名稱</td>
                            <td style='padding: 10px; border: 1px solid #ddd;'><strong>$itemName</strong></td>
                        </tr>
                        <tr>
                            <td style='padding: 10px; border: 1px solid #ddd;'>應還日期</td>
                            <td style='padding: 10px; border: 1px solid #ddd; color: #dc3545;'><strong>$dueDate</strong></td>
                        </tr>
                    </table>

                    <p>請將物品歸還至系辦公室。</p>
                    <p style='color: #999; font-size: 12px; margin-top: 30px;'>* 此信件由系統自動發送，請勿回覆。</p>
                </div>
            </div>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>

        <div class="text-center mt-4">
            <a href="admin_borrow_record.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> 返回管理列表
            </a>
        </div>

        </div>
    </div>
</div>

</body>
</html>
<?php mysqli_close($conn); ?>