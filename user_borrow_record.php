<?php
require_once "header.php";
require_once 'assets_db.php';
$msg = "";

$title = "報名記錄";
include('check_login.php');
include('header.php');

if (session_status() === PHP_SESSION_NONE) session_start();
$account = $_SESSION["account"] ?? "";

require_once 'db.php';
$sql = "SELECT er.*, e.name as event_name, e.description as event_description FROM event_registration er JOIN event e ON er.event_id = e.id WHERE er.account = ? ORDER BY er.registered_at DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $account);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<div class="container my-5">
	<h2>我的報名記錄</h2>
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
						<th>活動名稱</th>
						<th>報名時間</th>
					</tr>
				</thead>
				<tbody>
					<?php while($row = mysqli_fetch_assoc($result)): ?>
						<tr>
							<td><?=htmlspecialchars($row['event_name'])?></td>
							<td><?=$row['registered_at']?></td>
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