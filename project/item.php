
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 如果未登入，導向 login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
require_once "header.php";


    
try {
  require_once 'assets_db.php';
  $searchtxt = mysqli_real_escape_string($conn, $_POST["searchtxt"] ?? "");
  $sql = "select * from items";
  if ($searchtxt) {
    $sql .= " where (name like '%$searchtxt%')";
  }

  $result = mysqli_query($conn, $sql);
  if (!$result) {
    echo "<div class='container mt-3'><div class='alert alert-danger'>Database query failed: " . htmlspecialchars(mysqli_error($conn)) . "</div></div>";
  } else {

 // --- 區塊 1：顯示錯誤/成功訊息 (Alert) ---
if (isset($_SESSION['flash_msg'])) {
    $m = $_SESSION['flash_msg'];
    echo '<div class="container mt-3">
            <div class="alert alert-' . $m['type'] . ' alert-dismissible fade show" role="alert">
                ' . htmlspecialchars($m['text']) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          </div>';
    unset($_SESSION['flash_msg']); // 顯示完後清除
}
?>

<div class="container">
    <form action="item.php" method="post">
  
  <input placeholder="搜尋物品名稱" type="text" name="searchtxt" value="<?=htmlspecialchars($searchtxt)?>">
  <input class="btn btn-success" type="submit" value="搜尋">
</form>
<table class="table table-bordered table-striped">
 <tr>
  <th>物品圖片</th>
  <th>物品名稱</th>
  <th>物品總數</th>
  <th>物品庫存</th>
 </tr>
 
 <?php
 while($row = mysqli_fetch_assoc($result)) {?>
 <tr>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<td class="text-center" style="width: 150px;">
                <div class="mb-2">
                    <?php if (!empty($row['image_path']) && file_exists($row['image_path'])): ?>
                        <img src="<?= htmlspecialchars($row['image_path']) ?>" 
                             style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                    <?php else: ?>
                        <div style="width: 100px; height: 100px; background: #f0f0f0; display: inline-flex; align-items: center; justify-content: center; color: #999;">
                            無圖片
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin"): ?>
                    
                    <form action="upload_act.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        
                        <input type="file" name="fileToUpload" id="file_<?= $row['id'] ?>" 
                               style="display: none;" accept="image/*" onchange="this.form.submit()">
                        
                        <label for="file_<?= $row['id'] ?>" class="btn btn-outline-secondary btn-sm" style="cursor: pointer;">
                            <i class="fas fa-camera"></i> 更換
                        </label>
                    </form>

                <?php endif; ?>
            </td>
  <td><?=$row["name"]?></td>
  <td><?=$row["total"]?></td>
    <td><?=$row["available"]?></td>
  <td>
  <div class="btn-group" role="group">
    <?php
    if ((int)$row['available'] > 0) {
        echo '<a href="borrow.php?item_id=' . $row['id'] . '" class="btn btn-primary btn-sm">借用</a>';
    } else {
        echo '<button class="btn btn-secondary btn-sm" disabled>庫存不足</button>';
    }

    if ($_SESSION["role"] === "admin") {
        echo '<a href="item_delete.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'確定刪除？\')">刪除</a>';
        echo '<a href="item_update.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm">更新</a>';
    }
    ?>
  </div>
</td>

      

 </tr>

 <?php
  }
 ?>
</table>
    <?php
    // 只有管理員看到新增物品按鈕
    if ($_SESSION["role"] === "admin") {
        echo '<a href="add_item.php" class="btn btn-success mb-3">新增物品</a>';
    }
    ?>
</div>
<?php
  }
  mysqli_close($conn);
}
//catch exception
catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}
require_once "footer.php";
?>