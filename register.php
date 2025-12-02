<?php
require_once "header.php";

try {
    require_once 'assets_db.php';
    $msg = "";
    $result = false; // 初始化預設，避免未定義變數錯誤

    if ($_POST) {

        // POST 變數
        $account   = $_POST["account"];
        $password  = $_POST["password"];
        $password2 = $_POST["password2"];
        $user_name = $_POST["user_name"];
        $email     = $_POST["email"];

        // 密碼確認
        if ($password !== $password2) {
            $msg = "兩次密碼不一致！";
        } else {

            // SQL 修正：使用 users 資料表、密碼雜湊，並加入檢查是否已存在帳號
            $checkSql = "SELECT 1 FROM users WHERE account = ? LIMIT 1";
            $checkStmt = mysqli_prepare($conn, $checkSql);
            if (!$checkStmt) {
                $msg = "資料庫錯誤 (查詢)：" . mysqli_error($conn);
            } else {
                mysqli_stmt_bind_param($checkStmt, "s", $account);
                mysqli_stmt_execute($checkStmt);
                mysqli_stmt_store_result($checkStmt);
                if (mysqli_stmt_num_rows($checkStmt) > 0) {
                    $msg = "此帳號已存在，請選擇其他帳號";
                    mysqli_stmt_close($checkStmt);
                } else {
                    mysqli_stmt_close($checkStmt);
                    $sql = "INSERT INTO users (account, password, user_name) VALUES (?, ?, ?)";

                    $stmt = mysqli_prepare($conn, $sql);
                    if (!$stmt) {
                        $msg = "資料庫錯誤 (新增)：" . mysqli_error($conn);
                    } else {
                        // 密碼雜湊
                        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
                        mysqli_stmt_bind_param($stmt, "sss", $account, $password_hashed, $user_name);
                        $result = mysqli_stmt_execute($stmt);
                        if (!$result) {
                            $msg = "資料庫執行錯誤 (新增)：" . mysqli_stmt_error($stmt);
                        }
                        mysqli_stmt_close($stmt);
                    }
                }
            }

            if ($result) {
                header('Location: login.php');
                exit;
            } else {
                if (empty($msg)) {
                    $msg = "無法新增資料";
                }
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

                <?php if ($msg): ?>
                    <div class="alert alert-danger mt-3">
                        <?= htmlspecialchars($msg) ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php
    mysqli_close($conn);
} catch (Exception $e) {
    echo 'Message: ' . $e->getMessage();
}

require_once "footer.php";
?>