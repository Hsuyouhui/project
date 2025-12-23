<?php
session_start(); // 啟動 session

// 登入檢查
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 如果登入，使用者或管理員都可繼續
require 'db.php';

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

$sql = "SELECT * FROM packages 
        WHERE room_number LIKE :keyword
        OR recipient_name LIKE :keyword";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':keyword' => "%$keyword%"
]);

$results = $stmt->fetchAll();
require_once 'header.php';
?>


<?php
require 'db.php';


$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

$sql = "SELECT * FROM packages 
        WHERE room_number LIKE :keyword
        OR recipient_name LIKE :keyword";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':keyword' => "%$keyword%"
]);

$results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
<meta charset="UTF-8">
<title>包裹搜尋結果</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

</head>
<body class="bg-light">

<div class="container my-5">

    <!-- 標題 -->
    <h2 class="mb-4">包裹搜尋結果</h2>

    <!-- 返回按鈕 -->
    <a href="package.php" class="btn btn-secondary mb-3">返回新增頁面</a>

    <!-- 搜尋條 (也可以直接在 DataTable 的內建搜尋框使用) -->
    <form action="search_package.php" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="keyword" class="form-control" placeholder="輸入房號或姓名" value="<?= htmlspecialchars($keyword) ?>">
            <button class="btn btn-primary" type="submit">搜尋</button>
        </div>
    </form>

    <!-- 表格 -->
    <table id="packageTable" class="table table-bordered table-striped display">
        <thead>
            <tr>
                <th>ID</th>
                <th>房號</th>
                <th>收件人</th>
                <th>到件時間</th>
                <th>狀態</th>
                <th>是否領取</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($results as $row): ?>
            <tr>
                <td><?= $row["id"] ?></td>
                <td><?= $row["room_number"] ?></td>
                <td><?= $row["recipient_name"] ?></td>
                <td><?= $row["delivered_at"] ?></td>
                <td><?= $row["status"] ?></td>
                <td><?= $row["pickup_at"] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>


<script>
$(document).ready(function() {
    $('#packageTable').DataTable({
        "paging": true,      
        "searching": true,   
        "ordering": true    
    });
});
</script>

</body>
</html>
