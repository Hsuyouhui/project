<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "header.php";

try {
  require_once 'assets_db.php';
  $msg="";
  if ($_POST) {
     $name = trim($_POST['name'] ?? '');
    $total = isset($_POST['total']) ? (int)$_POST['total'] : 0;
    $available = isset($_POST['available']) ? (int)$_POST['available'] : 0;

    $sql = "INSERT INTO items (name, total, available) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "sii", $name, $total  ,$available);
    $result = mysqli_stmt_execute($stmt);
    if ($result) {
      header('location:item.php');
    }
    else {
      $msg = "無法新增資料";
    }
  }
?>




<div class="container">
<form action="add_item.php" method="post">
  <div class="mb-3 row">
    <label for="name" class="col-sm-2 col-form-label">物品名稱</label>
    <div class="col-sm-6 col-md-5">
      <input type="text" class="form-control form-control-sm" name="name" id="name" placeholder="物品名稱" required value="<?=htmlspecialchars($_POST['name'] ?? '')?>">
    </div>
  </div>
  <div class="mb-3 row">
    <label for="total" class="col-sm-2 col-form-label">物品總數</label>
    <div class="col-sm-2 col-md-2">
      <input type="number" min="0" class="form-control form-control-sm" name="total" id="total" required value="<?=htmlspecialchars($_POST['total'] ?? '')?>">
    </div>
  </div>
  <div class="mb-3 row">
    <label for="available" class="col-sm-2 col-form-label">物品庫存</label>
    <div class="col-sm-2 col-md-2">
      <input type="number" min="0" class="form-control form-control-sm" name="available" id="available" required value="<?=htmlspecialchars($_POST['available'] ?? '')?>">
    </div>
  </div>
  <input class="btn btn-primary" type="submit" value="送出">
  <?=$msg?>
</form>
</div>

<?php
  mysqli_close($conn);
}
//catch exception
catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}
require_once "footer.php";
?>
