<?php
session_start();


if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header('Location: item.php'); 
    exit; 
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
