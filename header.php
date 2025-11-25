<?php

// 檢查使用者是否已登入
$is_logged_in = isset($_SESSION['user_id']); // 假設登入後會在 session 中設置 'user_id'

// 儲存用戶名稱（如果已登入）
$user_name = $is_logged_in ? $_SESSION['user_name'] : '';

// 設定頁面標題
$title = $title ?? "我的網站";

// 將當前頁面標示為 active
function nav_active($file) {
    $current = basename($_SERVER['PHP_SELF']);
    return $current === $file ? ' active' : '';
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?=$title?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">資產與包裹管理系統</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link<?=nav_active('index.php')?>" href="index.php">首頁</a>
          </li>
          <li class="nav-item">
            <a class="nav-link<?=nav_active('package.php')?>" href="package.php">包裹資料</a>
          </li>
          <li class="nav-item">
            <a class="nav-link<?=nav_active('item.php')?>" href="item.php">公物借用</a>
          </li>
          <li class="nav-item">
            <a class="nav-link<?=nav_active('admin_borrow_record.php')?>" href="admin_borrow_record.php">借用紀錄</a>
          </li>
     

          <!-- 根據使用者登入狀態顯示不同的連結 -->
          <?php if ($is_logged_in): ?>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">登出 (<?=$user_name?>)</a>
            </li>
          <?php else: ?>
            <li class="nav-item" >
              <a class="nav-link<?=nav_active('login.php')?>" href="login.php">登入</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container mt-4">
