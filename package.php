
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
<meta charset="UTF-8">
<title>新增包裹</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background-color: #f8f9fa;
}
.card {
    border-radius: 12px;
}
.table th {
    background: #f1f3f5;
}
</style>
</head>
<body>

<div class="container my-5">

    <!-- 頁面標題 -->
    <h2 class="mb-4">包裹管理</h2>

    <!-- 成功訊息（如果剛新增完） -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-info text-center">包裹新增成功！</div>
    <?php endif; ?>


    <!-- 搜尋功能 -->
    <h5 class="mb-3 mt-4">搜尋包裹</h5>

    <form action="search_package.php" method="GET" class="row g-3 mb-4">
        <div class="col-md-8">
            <input type="text" name="keyword" class="form-control" placeholder="輸入房號或姓名" required>
        </div>
        <div class="col-md-4">
            <button class="btn btn-primary w-100">搜尋</button>
        </div>
    </form>



    <!-- 新增包裹 -->
    <div class="card p-4">
        <h5 class="mb-3">新增包裹</h5>

        <form action="save_package.php" method="POST" class="row g-3">

            <div class="col-md-6">
                <label class="form-label">房號：</label>
                <input type="text" class="form-control" name="room_number" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">收件人姓名：</label>
                <input type="text" class="form-control" name="recipient_name" required>
            </div>

            <div class="col-12">
                <button class="btn btn-success w-100">送出</button>
            </div>

        </form>
    </div>

</div>

</body>
</html>

