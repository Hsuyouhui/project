


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
echo "<a href='package.php'>返回新增頁面</a>";
header("Location: package.php?success=1");
exit;

?>
<?php
require_once 'assets_db.php';

if ($_POST) {
    $room_number = $_POST['room_number'] ?? '';
    $recipient_name = $_POST['recipient_name'] ?? '';

    $sql = "INSERT INTO packages (room_number, recipient_name, status) VALUES (?, ?, '已送達')";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $room_number, $recipient_name);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: package.php?success=1");
        exit;
    } else {
        echo "新增失敗：" . mysqli_error($conn);
    }
}
?>
