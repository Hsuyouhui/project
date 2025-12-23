<?php
require_once "header.php"; 
require_once 'assets_db.php';
$msg = "";

// 檢查是否登入
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('請先登入！'); location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$item_id = isset($_GET['item_id']) ? (int)$_GET['item_id'] : 0;

// ★★★ 設定日期限制變數 ★★★
$today = date("Y-m-d"); // 今天
$max_date = date('Y-m-d', strtotime('+2 weeks')); // 兩週後 (也可以改 '+14 days')

// 表單送出處理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = isset($_POST['item_id']) ? (int)$_POST['item_id'] : 0;
    $user_name = trim($_POST['user_name'] ?? '');
    $expected_return = trim($_POST['expected_return'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $check_sql = "SELECT record_id FROM borrow_records 
                  WHERE user_id = ? AND status = '借出' AND expected_return < ?";
$check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "is", $user_id, $today);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    
    // 如果找到任何一筆逾期資料 (筆數 > 0)
    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        // 直接擋下，不執行後面的借用
        $msg = "❌ 借用失敗：您目前有「逾期未還」的物品，請先歸還後再借用！";
        mysqli_stmt_close($check_stmt);
    } 
    else {
        mysqli_stmt_close($check_stmt);
        // ==========================================
        //       沒有逾期，繼續執行原本的檢查
        // ==========================================

        if ($expected_return < $today) {
            $msg = "錯誤：預計歸還日期不能早於今天！";
        } 
        elseif ($expected_return > $max_date) {
            $msg = "錯誤：借用期限最長為兩週，請選擇 {$max_date} 之前的日期。";
        }
        else {
            // --- 寫入資料庫 (跟原本一樣) ---
            $sql = "INSERT INTO borrow_records (user_id, item_id, user_name, expected_return, email, borrow_time, status)
                    VALUES (?, ?, ?, ?, ?, NOW(), '借出')";

            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "iisss", $user_id, $item_id, $user_name, $expected_return, $email);
                $result = mysqli_stmt_execute($stmt);

                if ($result) {
                    // 扣庫存
                    $new_available_sql = "UPDATE items SET available = available - 1 WHERE id = ? AND available > 0";
                    $stmt2 = mysqli_stmt_init($conn);
                    if (mysqli_stmt_prepare($stmt2, $new_available_sql)) {
                        mysqli_stmt_bind_param($stmt2, "i", $item_id);
                        mysqli_stmt_execute($stmt2);
                        mysqli_stmt_close($stmt2);
                    } 
                    
                    echo '<script>alert("借用成功！"); location.href="user_borrow_record.php";</script>';
                    exit;
                } else {
                    $msg = "無法新增資料: " . mysqli_stmt_error($stmt);
                }
                mysqli_stmt_close($stmt);
            } else {
                $msg = "SQL prepare failed: " . mysqli_error($conn);
            }
        }
    }
}
?>

<div class="container mt-4">
    <form action="borrow.php?item_id=<?= htmlspecialchars($item_id) ?>" method="post">
        <input type="hidden" name="item_id" value="<?= htmlspecialchars($item_id) ?>">

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">借用人姓名</label>
            <div class="col-sm-6 col-md-5">
                <input type="text" class="form-control" name="user_name" 
                       value="<?= isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : '' ?>" required>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">預計歸還日期</label>
            <div class="col-sm-4">
                <input class="form-control" type="date" name="expected_return" 
                       min="<?= $today ?>" 
                       max="<?= $max_date ?>" 
                       required>
                <div class="form-text">
                    借用期限最長為 2 週 (最晚歸還日：<?= $max_date ?>)
                </div>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-6">
                <input class="form-control" type="email" name="email" required>
            </div>
        </div>

        <input class="btn btn-primary" type="submit" value="確認借出">
        <a href="item.php" class="btn btn-secondary">取消</a>
        
        <?php if($msg): ?>
            <div class="alert alert-danger mt-3"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
    </form>
</div>

<?php
mysqli_close($conn);
require_once "footer.php";
?>