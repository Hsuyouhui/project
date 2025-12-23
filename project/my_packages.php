<?php
session_start();
require 'db.php'; // 你的 PDO 連線設定

// 假設住戶登入後，房號存於 session
if (!isset($_SESSION['room_number'])) {
    die("請先登入");
}
$room_number = $_SESSION['room_number'];

// 取得該住戶的包裹
$sql = "SELECT * FROM packages WHERE room_number = :room ORDER BY delivered_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':room' => $room_number]);
$packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>我的包裹</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2>我的包裹</h2>
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>房號</th>
                <th>收件人</th>
                <th>送達時間</th>
                <th>狀態</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($packages as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['room_number']) ?></td>
                <td><?= htmlspecialchars($p['recipient_name']) ?></td>
                <td><?= htmlspecialchars($p['delivered_at']) ?></td>
                <td>
                    <?php if ($p['status'] === 'delivered'): ?>
                        <span class="badge bg-warning">已送達</span>
                    <?php else: ?>
                        <span class="badge bg-success">已領取</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($p['status'] === 'delivered'): ?>
                        <a href="update_status.php?id=<?= $p['id'] ?>"
                           class="btn btn-success btn-sm"
                           onclick="return confirm('確認已領取此包裹？');">
                           已領取
                        </a>
                    <?php else: ?>
                        <?= htmlspecialchars($p['picked_up_at']) ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
