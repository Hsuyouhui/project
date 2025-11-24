<?php
require_once "header.php";
require_once 'assets_db.php';
$msg = "";

// 取得 item_id
$item_id = isset($_GET['item_id']) ? (int)$_GET['item_id'] : 0;

// 表單送出
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = isset($_POST['item_id']) ? (int)$_POST['item_id'] : 0;
    $user_name = trim($_POST['user_name'] ?? '');
    $expected_return = trim($_POST['expected_return'] ?? '');
    $email = trim($_POST['email'] ?? '');


    // INSERT borrow_records
    $sql = "INSERT INTO borrow_records (item_id, user_name, expected_return, email, borrow_time)
            VALUES (?, ?, ?, ?, NOW())";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "isss", $item_id, $user_name, $expected_return, $email);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
          $new_available_sql="UPDATE items SET available = available - 1 where id =? AND available > 0";
          $stmt2 = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt2, $new_available_sql)) {
    mysqli_stmt_bind_param($stmt2, "i", $item_id);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);
} 
else {
    echo "更新庫存失敗: " . mysqli_error($conn);
}
            echo  '<script>location.href="item.php";</script>';

            exit;
        } else {
            $msg = "無法新增資料: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $msg = "SQL prepare failed: " . mysqli_error($conn);
    }
}
?>

<div class="container">
    <form action="borrow.php?item_id=<?= htmlspecialchars($item_id) ?>" method="post">
        <input type="hidden" name="item_id" value="<?= htmlspecialchars($item_id) ?>">

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">借用人姓名</label>
            <div class="col-sm-6 col-md-5">
                <input type="text" class="form-control form-control-sm" name="user_name" required>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">預計歸還日期</label>
            <div class="col-sm-4">
                <input class="form-control" type="date" name="expected_return" required>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-6">
                <input class="form-control" type="email" name="email" required>
            </div>
        </div>

        <input class="btn btn-primary" type="submit" value="送出">
        <?= $msg ?>
    </form>
</div>

<?php
mysqli_close($conn);
require_once "footer.php";
?>
