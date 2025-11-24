
<?php

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

 
?>

<div class="container">
    <form action="item.php" method="post">
  
  <input placeholder="搜尋物品名稱" type="text" name="searchtxt" value="<?=htmlspecialchars($searchtxt)?>">
  <input class="btn btn-primary" type="submit" value="搜尋">
</form>
<table class="table table-bordered table-striped">
 <tr>
  <th>物品名稱</th>
  <th>物品總數</th>
  <th>物品庫存</th>
 </tr>
 
 <?php
 while($row = mysqli_fetch_assoc($result)) {?>
 <tr>
  <td><?=$row["name"]?></td>
  <td><?=$row["total"]?></td>
    <td><?=$row["available"]?></td>
    <td>
      <?php if ((int)$row['available'] > 0) { ?>
<a href="borrow.php?item_id=<?= $row['id'] ?>" class="btn btn-primary">借用</a>
      <?php } else { ?>
        <button class="btn btn-secondary"  disabled>庫存不足，無法借用</button>
      <?php } ?>
      </td>

 </tr>

 <?php
  }
 ?>
</table>
        <a href="add_item.php" class="btn btn-primary">新增</a>

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