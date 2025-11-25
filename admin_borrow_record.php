<?php
require_once "header.php";
require_once 'assets_db.php';
$msg = "";

$sql = "SELECT i.name , user_name , email , borrow_time , expected_return from borrow_records b join items i on b.item_id = i.id " ;
$stmt = mysqli_prepare($conn, $sql);
// The query has no placeholders, so do not bind parameters; just execute the prepared statement.
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<div class="container my-5">
	<h2>借出記錄</h2>
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
					</tr>
				</thead>
				<tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?=htmlspecialchars($row['name'])?></td>
                            <td><?=htmlspecialchars($row['user_name'])?></td>
                            <td><?=htmlspecialchars($row['email'])?></td>
                            <td><?=htmlspecialchars($row['borrow_time'])?></td>
                            <td><?=htmlspecialchars($row['expected_return'])?></td>
                        </tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<div class="alert alert-info" role="alert">
			您還沒有任何報名記錄。
		</div>
	<?php endif; ?>
</div>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
include('footer.php');
?>