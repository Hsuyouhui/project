<?php
session_start(); // 1. 必須先啟動 session 才能讀取登入資訊

// 2.【關鍵檢查】權限驗證
// 如果沒有 user_id (未登入) 或者 role 不是 admin (非管理員)
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    // 強制踢回列表頁或登入頁
    header('Location: item.php'); 
    exit; // 3. 務必加上 exit，確保程式立刻停止，不會執行下方的刪除指令
}

try {
  // 改用 isset 檢查具體的 id 參數，比檢查整個 $_GET 更嚴謹
  if (isset($_GET["id"])) {
    require_once 'assets_db.php';

    // delete data
    $id = $_GET["id"]; 

    $sql = "DELETE FROM items WHERE id=?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id); 
        mysqli_stmt_execute($stmt);
    }
    
    mysqli_close($conn);
  }

  header('Location: item.php');
  exit; 
}
catch(Exception $e) {
  echo 'Message: ' . $e->getMessage();
}
?>
