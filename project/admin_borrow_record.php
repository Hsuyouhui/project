<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 如果未登入，導向 login
if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}
require_once "header.php";
require_once 'assets_db.php';
$msg = "";

$sql = "SELECT record_id, i.id AS item_id, i.name , user_name , email , borrow_time , expected_return ,return_time, b.status 
        FROM borrow_records b 
        JOIN items i ON b.item_id = i.id 
        ORDER BY b.borrow_time DESC";$stmt = mysqli_prepare($conn, $sql);
// The query has no placeholders, so do not bind parameters; just execute the prepared statement.
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<div class="container my-5">
	<h2>借出記錄</h2>
	<a href="sendemail.php" class="btn btn-danger shadow-sm" 
       onclick="return confirm('確定要發送 Email 給所有「逾期未還」的使用者嗎？');">
        <i class="fas fa-envelope"></i> 一鍵發送逾期通知
    </a>
	<br><br>
	<?php
	$msg = $_GET["msg"] ?? "";
	if ($msg): ?>
		<div class="alert alert-info" role="alert">
			<?=htmlspecialchars($msg)?>
		</div>
	<?php endif; ?>
	<?php if (mysqli_num_rows($result) > 0): ?>
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>物品名稱</th>
						<th>借用人姓名</th>
                        <th>借用人信箱</th>
                        <th>借用時間</th>
                        <th>預計歸還時間</th>
						<th>狀態</th>


					</tr>
				</thead>
				<tbody>
 <?php 
                    // ★ 1. 取得今天日期
                    $today = date("Y-m-d");

                    while($row = mysqli_fetch_assoc($result)): 
                        // ★ 2. 判斷是否逾期 (狀態是借出 且 預計日期小於今天)
                        $is_overdue = ($row['status'] == '借出' && $row['expected_return'] < $today);
                    ?>
                        <tr class="<?php if ($is_overdue) { echo 'table-danger'; } ?>">
                            <td><?=htmlspecialchars($row['name'])?></td>
                            <td><?=htmlspecialchars($row['user_name'])?></td>
                            <td><?=htmlspecialchars($row['email'])?></td>
                            <td><?=htmlspecialchars($row['borrow_time'])?></td>
                            
                            <td class="<?= $is_overdue ? 'text-danger fw-bold' : '' ?>">
                                <?=htmlspecialchars($row['expected_return'])?>
                                <?php if($is_overdue): ?>
                                    <br><small>(已過期!)</small>
                                <?php endif; ?>
                            </td>
                            
                            
                            <td>
                                <?php if ($row['status'] == '借出'): ?>
                                    <?php if ($is_overdue): ?>
                                        <span class="badge bg-danger">已逾期</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">借出中</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-success">已歸還</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if ($row['status'] == '借出'): ?>
                                     <form action="return.php" method="POST" onsubmit="return confirm('確認該物品已歸還？');">
                                        <input type="hidden" name="record_id" value="<?= $row['record_id'] ?>">
                                        <input type="hidden" name="item_id" value="<?= $row['item_id'] ?>">
                                        
                                        <button type="submit" class="btn btn-sm <?= $is_overdue ? 'btn-danger' : 'btn-primary' ?>">
                                            <?= $is_overdue ? '強制歸還' : '確認歸還' ?>
                                        </button>
                                    </form>
                      
                                <?php else: ?>
                                    <?php if (!empty($row['return_time'])): ?>
                                        <small class="text-muted"><?= $row['return_time'] ?></small>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-secondary">目前沒有紀錄。</div>
    <?php endif; ?>
</div>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
include('footer.php');
?>