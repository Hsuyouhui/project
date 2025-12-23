<?php
// user_borrow_record.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. 修正權限判斷：只要有登入即可，不需要限制必須是 admin
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "header.php";
require_once 'assets_db.php';

$user_id = $_SESSION["user_id"]; // 取得當前登入者的 ID
$msg = $_GET["msg"] ?? "";

// SQL查詢：只選取該使用者的紀錄
$sql = "SELECT record_id, i.id AS item_id, i.name, user_name, email, borrow_time, expected_return, return_time, b.status 
        FROM borrow_records b 
        JOIN items i ON b.item_id = i.id 
        WHERE b.user_id = ? 
        ORDER BY b.borrow_time DESC";

$stmt = mysqli_prepare($conn, $sql);

// 2. 修正 SQL 綁定：這裡有 ? 號，所以必須綁定參數
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $user_id); // "i" 代表整數 (integer)
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    // 錯誤處理
    die("查詢失敗: " . mysqli_error($conn));
}
?>

<div class="container my-5">
    <h2>我的借出記錄</h2> <?php if ($msg): ?>
        <div class="alert alert-info" role="alert">
            <?=htmlspecialchars($msg)?>
        </div>
    <?php endif; ?>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>物品名稱</th>
                        <th>借用時間</th>
                        <th>預計歸還時間</th>
                        <th>歸還時間</th>
                        <th>狀態</th>
                    </tr>  
                </thead>
                
                <tbody>
                    
                    <?php 
                    $today = date("Y-m-d");
                        while($row = mysqli_fetch_assoc($result)):
                        $is_overdue = ($row['status'] == '借出' && $row['expected_return'] < $today); ?>
                        
                        <tr>
                            <td><?=htmlspecialchars($row['name'])?></td>
                            <td><?=htmlspecialchars($row['borrow_time'])?></td>
                            <td><?=htmlspecialchars($row['expected_return'])?></td>
                            <td><?=htmlspecialchars($row['return_time'] ?? '')?></td>
                            <td>
                                <?php 
                                    // 3. 狀態徽章顯示優化
                                    if($row['status'] == '已歸還') {
                                        echo '<span class="badge bg-success">已歸還</span>';
                                    } elseif ($is_overdue) {
                                        // 逾期顯示紅色
                                        echo '<span class="badge bg-danger">已逾期</span>';
                                    } elseif($row['status'] == '借出') {
                                        // 正常借出顯示黃色
                                        echo '<span class="badge bg-warning text-dark">借用中</span>';
                                    } else {
                                        echo htmlspecialchars($row['status']);
                                    }
                                ?>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-secondary text-center">目前沒有借用紀錄。</div>
    <?php endif; ?>
</div>

<?php
if (isset($stmt)) mysqli_stmt_close($stmt);
mysqli_close($conn);
include('footer.php');
?>