<?php
session_start();
require 'db.php';

if (!isset($_POST['package_id'])) {
    die("Missing package ID");
}

$id = intval($_POST['package_id']);

// 只更新領取時間，不動 status
$sql = "UPDATE packages
        SET pickup_at = NOW()
        WHERE id = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);

// 回前端並顯示成功提示
header("Location: package.php?picked=1");
exit;

