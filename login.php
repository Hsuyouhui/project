<?php
session_start();

include 'header.php'; 

$users = [
  "root"  => ["password" => "password", "user_name" => "管理員", "role" => "admin"],
  "user1" => ["password" => "pw1", "user_name" => "小明",   "role" => "user"],
];

if ($_POST) {
  $account = $_POST["account"] ?? "";
  $password = $_POST["password"] ?? "";

  if (isset($users[$account]) && $users[$account]["password"] === $password) {

    $_SESSION["user_id"] = $account;
    $_SESSION["user_name"] = $users[$account]["user_name"]; // 修正這裡！
    $_SESSION["role"] = $users[$account]["role"];

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
