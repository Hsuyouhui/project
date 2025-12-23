<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$is_logged_in = !empty($_SESSION['user_id']);
$user_name = $is_logged_in ? ($_SESSION['user_name'] ?? '') : '';
// 判斷是否為管理員：可依照你在登入時設定的 session 欄位調整
$is_admin = !empty($_SESSION['role']) && $_SESSION['role'] === 'admin';
// 或者如果你使用布林值： $is_admin = !empty($_SESSION['is_admin']);

$title = $title ?? "我的網站";

function nav_active($file) {
    return basename($_SERVER['PHP_SELF']) === $file ? ' active' : '';
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <a class="nav-link<?= nav_active('index.php') ?>" href="index.php">首頁</a>
          </li>
          <li class="nav-item">
            <a class="nav-link<?= nav_active('package.php') ?>" href="package.php">包裹資料</a>
          </li>
          <li class="nav-item">
            <a class="nav-link<?= nav_active('search_package.php') ?>" href="search_package.php">包裹查詢</a>
          </li>
          <li class="nav-item">
            <a class="nav-link<?= nav_active('item.php') ?>" href="item.php">公物借用</a>
          </li>

          <?php if ($is_logged_in): ?>
            <!-- 根據身分顯示不同的借用紀錄連結 -->
            <li class="nav-item">
              <?php if ($is_admin): ?>
                <a class="nav-link<?= nav_active('admin_borrow_record.php') ?>" href="admin_borrow_record.php">借用紀錄（管理員）</a>
              <?php else: ?>
                <a class="nav-link<?= nav_active('user_borrow_record.php') ?>" href="user_borrow_record.php">借用紀錄（我的）</a>
              <?php endif; ?>
            </li>

            <!-- 登出與顯示使用者名稱 -->
            <li class="nav-item">
              <a class="nav-link" href="logout.php">登出 (<?= htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8') ?>)</a>
            </li>
          <?php else: ?>
            <!-- 未登入顯示登入 -->
            <li class="nav-item">
              <a class="nav-link<?= nav_active('login.php') ?>" href="login.php">登入</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-4">