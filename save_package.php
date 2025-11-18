<?php
require 'db.php'; 


$room_number = $_POST['room_number'];
$recipient_name = $_POST['recipient_name'];


$sql = "INSERT INTO packages (room_number, recipient_name, delivered_at, status) 
        VALUES (:room, :recipient, NOW(), 'delivered')";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':room' => $room_number,
    ':recipient' => $recipient_name
]);

echo "包裹新增成功！<br>";
echo "<a href='index.php'>返回新增頁面</a>";
?>
