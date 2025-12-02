<?php
session_start();

include 'header.php'; 
require_once 'assets_db.php';

if ($_POST) {
  $account = $_POST["account"] ?? "";
  $password = $_POST["password"] ?? "";

  // 透過 DB 查詢帳號
  $sql = "SELECT user_id, user_name, password, role FROM users WHERE account = ? LIMIT 1";
  $stmt = mysqli_prepare($conn, $sql);
  if (!$stmt) {
    $msg = "資料庫錯誤：" . mysqli_error($conn);
  } else {
    mysqli_stmt_bind_param($stmt, "s", $account);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $db_user_id, $db_user_name, $db_password, $db_role);
    if (mysqli_stmt_fetch($stmt)) {
      $authenticated = false;
      // 支援已經以 password_hash() 儲存的密碼
      if (password_verify($password, $db_password)) {
        $authenticated = true;
      } else {
        // 若 DB 中是明文（舊資料），嘗試直接比對；若比對成功，立即用 password_hash 重新加密並更新 DB
        if ($password === $db_password) {
          $authenticated = true;
          $newHash = password_hash($password, PASSWORD_DEFAULT);
          $updateSql = "UPDATE users SET password = ? WHERE user_id = ?";
          $updateStmt = mysqli_prepare($conn, $updateSql);
          if ($updateStmt) {
            mysqli_stmt_bind_param($updateStmt, "si", $newHash, $db_user_id);
            mysqli_stmt_execute($updateStmt);
            mysqli_stmt_close($updateStmt);
          }
        }
      }

      if ($authenticated) {
        $_SESSION["user_id"] = $db_user_id;
        $_SESSION["user_name"] = $db_user_name;
        $_SESSION["role"] = $db_role;

        // 回到登入前所在頁面
        $redirect = $_SESSION['redirect_to'] ?? 'index.php';
        unset($_SESSION['redirect_to']);

        header("Location: $redirect");
        exit;
      } else {
        header("Location: login.php?msg=帳號或密碼錯誤");
        exit;
      }
    } else {
      header("Location: login.php?msg=帳號或密碼錯誤");
      exit;
    }
    mysqli_stmt_close($stmt);
  }

} else {
  $msg = $_GET["msg"] ?? "";
?>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card shadow">
        <div class="card-body">
          <h4 class="card-title mb-4">登入</h4>
          <form method="post" action="">
            <div class="mb-3">
              <label for="account" class="form-label">帳號</label>
              <input type="text" class="form-control" id="account" name="account" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">密碼</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">登入</button>
            <a href="register.php">註冊帳號密碼</a>

          </form>

          <?php if (!empty($msg)): ?>
            <div class="alert alert-danger mt-3"><?=htmlspecialchars($msg)?></div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</div>
<?php 
}
include 'footer.php'; 
?>
