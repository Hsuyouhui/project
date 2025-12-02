<?php
session_start();
require 'db.php';

if (!isset($_GET['id'])) {
    die("Missing package ID");
}

// 取得包裹資訊，確認是自己房號
$id = $_GET['id'];
$room_number = $_SESSION['room_number'];

$sql = "SELECT * FROM packages WHERE id = :id AND room_number = :room";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id, ':room' => $room_number]);
$package = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$package) {
    die("找不到該包裹或無權限更新");
}

// 更新狀態
$sql = "UPDATE packages
        SET status = 'picked_up', picked_up_at = NOW()
        WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);

header("Location: my_packages.php");
exit;
