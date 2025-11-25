<?php

require_once "header.php";?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>新增包裹</title>
</head>
<body>
    <h1>新增包裹</h1>

    <form action="save_package.php" method="POST">
        <label>房號：</label>
        <input type="text" name="room_number" required><br><br>

        <label>收件人姓名：</label>
        <input type="text" name="recipient_name" required><br><br>

        <button type="submit">送出</button>
    </form>

</body>
</html><?php

require_once "footer.php";
?>