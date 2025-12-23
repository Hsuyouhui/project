<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "header.php";


// 只有管理員能操作
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    die("無權限進行此操作");
}

require_once "assets_db.php";


if ($_SERVER["REQUEST_METHOD"] === "GET") {

    if (!isset($_GET["id"])) {
        die("缺少物品 id");
    }

    $id = intval($_GET["id"]);

    $sql = "SELECT * FROM items WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $item = $row;
    } else {
        die("找不到物品");
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = intval($_POST["id"]);
    $name = trim($_POST["name"]);
    $total = intval($_POST["total"]);
    $available = intval($_POST["available"]);

    $sql = "UPDATE items SET name=?, total=?, available=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "siii", $name, $total, $available, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: item.php");
        exit;
    } else {
        echo "更新失敗：" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>



<div class="container mt-4">
    <h3>更新物品資料</h3>

    <form action="item_update.php" method="POST">
        <input type="hidden" name="id" value="<?= $item['id'] ?>">

        <div class="mb-3">
            <label class="col-sm-2 col-form-label">物品名稱</label>
            <input type="text" name="name" class="form-control"
                   value="<?= htmlspecialchars($item['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="col-sm-2 col-form-label">物品總數</label>
            <input type="number" name="total" class="form-control"
                   value="<?= $item['total'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="col-sm-2 col-form-label">物品庫存</label>
            <input type="number" name="available" class="form-control"
                   value="<?= $item['available'] ?>" required>
        </div>

        <button type="submit" class="btn btn-success">更新</button>
        <a href="item.php" class="btn btn-secondary">返回</a>
    </form>
</div>

</body>
</html>
