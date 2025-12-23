<?php
// return.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION["user_id"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php"); 
    exit; 
}

require_once 'assets_db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $record_id = $_POST['record_id'] ?? null;
    $item_id   = $_POST['item_id'] ?? null;

    if (!$record_id || !$item_id) {
        $msg = "錯誤：參數不足";
        header("Location: admin_borrow_record.php?msg=" . urlencode($msg)); 
        exit;
    }

    mysqli_begin_transaction($conn);

    try {
        // 更新紀錄 
        $sql_record = "UPDATE borrow_records 
                       SET return_time = NOW(), status = '已歸還' 
                       WHERE record_id = ?";
        
        $stmt1 = mysqli_prepare($conn, $sql_record);
        mysqli_stmt_bind_param($stmt1, "i", $record_id);
        
        if (!mysqli_stmt_execute($stmt1)) {
            throw new Exception("更新借用紀錄失敗");
        }

        // 更新物品
    $sql_item = "UPDATE items SET available = available + 1 WHERE id = ?";
        
        $stmt2 = mysqli_prepare($conn, $sql_item);
        mysqli_stmt_bind_param($stmt2, "i", $item_id);
        
        if (!mysqli_stmt_execute($stmt2)) {
            throw new Exception("更新物品狀態失敗");
        }

        // 4. 提交
        mysqli_commit($conn);
        $msg = "歸還成功！";

        mysqli_stmt_close($stmt1);
        mysqli_stmt_close($stmt2);

    } catch (Exception $e) {
        mysqli_rollback($conn);
        $msg = "歸還失敗：" . $e->getMessage();
    }

    header("Location: admin_borrow_record.php?msg=" . urlencode($msg));
    exit;

} else {
    header("Location: index.php");
    exit;
}
?>