<?php
require_once "header.php";

try {
    require_once 'assets_db.php';

    // 預先宣告變數避免 IDE 警告
    $msg = "";
    $result = false;
    $checkStmt = null;
    $stmt = null;

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        // POST 變數
        $account   = trim($_POST["account"] ?? "");
        $password  = trim($_POST["password"] ?? "");
        $password2 = trim($_POST["password2"] ?? "");
        $user_name = trim($_POST["user_name"] ?? "");
        $email     = trim($_POST["email"] ?? "");

        // 密碼確認
        if ($password !== $password2) {
            $msg = "兩次密碼不一致！";
        } else {

            // Step 1. 檢查帳號是否已存在
            $checkSql = "SELECT 1 FROM users WHERE account = ? LIMIT 1";
            $checkStmt = mysqli_prepare($conn, $checkSql);

            if (!$checkStmt) {
                $msg = "資料庫錯誤 (查詢階段)： " . mysqli_error($conn);
            } else {
                mysqli_stmt_bind_param($checkStmt, "s", $account);
                mysqli_stmt_execute($checkStmt);
                mysqli_stmt_store_result($checkStmt);

                if (mysqli_stmt_num_rows($checkStmt) > 0) {
                    $msg = "此帳號已存在，請使用其他帳號。";
                } else {

                    // Step 2. 新增帳號
                    $sql = "INSERT INTO users (account, password, user_name, email) 
                            VALUES (?, ?, ?, ?)";

                    $stmt = mysqli_prepare($conn, $sql);

                    if (!$stmt) {
                        $msg = "資料庫錯誤 (新增階段)： " . mysqli_error($conn);
                    } else {

                        // 密碼雜湊
                        $pwd_hash = password_hash($password, PASSWORD_DEFAULT);

                        mysqli_stmt_bind_param($stmt, "ssss", $account, $pwd_hash, $user_name, $email);
                        $result = mysqli_stmt_execute($stmt);

                        if (!$result) {
                            $msg = "新增帳號失敗：" . mysqli_stmt_error($stmt);
                        }
                    }
                }
            }

            // 成功 → 導去登入頁
            if ($result) {
                header("Location: login.php?msg=註冊成功，請登入");
                exit;
            }
        }
    }
?>
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title mb-4">註冊</h4>

                <form method="post" action="">
                    <div class="mb-3">
                        <label for="account" class="form-label">帳號</label>
                        <input type="text" class="form-control" id="account" name="account" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">設定密碼</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="password2" class="form-label">確認密碼</label>
                        <input type="password" class="form-control" id="password2" name="password2" required>
                    </div>

                    <div class="mb-3">
                        <label for="user_name" class="form-label">姓名</label>
                        <input type="text" class="form-control" id="user_name" name="user_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">信箱</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">註冊</button>
                </form>

                <?php if (!empty($msg)): ?>
                    <div class="alert alert-danger mt-3">
                        <?= htmlspecialchars($msg) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
    // 關閉 stmt
    if ($checkStmt) mysqli_stmt_close($checkStmt);
    if ($stmt) mysqli_stmt_close($stmt);

    mysqli_close($conn);

} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}

require_once "footer.php";
?>
