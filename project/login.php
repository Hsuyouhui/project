<?php
session_start(); 
require_once "header.php";
$msg = $_GET["msg"] ?? "";
require_once "assets_db.php";  // 不能輸出 HTML

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $account = $_POST["account"] ?? "";
    $input_password = $_POST["password"] ?? "";

    // 建立 prepared statement
    $sql = "SELECT user_id, user_name, password, role FROM users WHERE account = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        header("Location: login.php?msg=系統錯誤");
        exit;
    }

    mysqli_stmt_bind_param($stmt, "s", $account);
    mysqli_stmt_execute($stmt);

    // 綁定查詢結果
    $user_id = $user_name = $db_password = $role = null;
    mysqli_stmt_bind_result($stmt, $user_id, $user_name, $db_password, $role);

    if (mysqli_stmt_fetch($stmt)) {

        // 用 password_verify() 驗證
        if (password_verify($input_password, $db_password)) {

            $_SESSION["user_id"] = $user_id;
            $_SESSION["user_name"] = $user_name;
            $_SESSION["role"] = $role;

            mysqli_stmt_close($stmt);
            header("Location: index.php");
            exit;
        } else {
            mysqli_stmt_close($stmt);
            header("Location: login.php?msg=帳密錯誤");
            exit;
        }
    } else {
        mysqli_stmt_close($stmt);
        header("Location: login.php?msg=帳密錯誤");
        exit;
    }
}
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
<?php include 'footer.php'; ?>
