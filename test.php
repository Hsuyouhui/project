<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>對話框</title>
    <link rel="stylesheet" href="css/styles.css" />
    <style>
    </style>
  </head>
  <body>
    <div class="container">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        報名
      </button>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">會員資料</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
                <div class="form-floating mb-3">
                  <input class="form-control" type="text" id="name" name="name" placeholder="我的姓名" required>
                  <label for="name">姓名:</label>
                </div>
                
                <div class="form-floating mb-3">
                  <input class="form-control" type="date" id="dob" name="dob" placeholder="" required>
                  <label for="dob">出生年月日:</label>
                </div>
                
                <div class="form-floating mb-3">
                  <input class="form-control" type="email" id="email" name="email" placeholder="電子郵件" required>
                  <label for="email">Email:</label>
                </div>
                
                <div class="form-floating mb-3">
                  <input class="form-control" type="tel" id="phone" name="phone" placeholder="電話號碼" required>
                  <label for="phone">電話:</label>
                </div>
                
                <div class="form-floating mb-3">
                  <input class="form-control" type="url" id="bio" name="bio" placeholder="請輸入自我介紹網址" required>
                  <label for="bio">自我介紹網址:</label>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
            <button type="button" class="btn btn-primary">提交</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>