<?php
session_start();

// 清除所有會話資料
session_unset();

// 銷毀會話
session_destroy();

// 重定向到登入頁
header("Location: login.php");
exit; // 確保不會繼續執行後面的程式
?>
