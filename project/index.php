

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>首頁 - 包裹與公物管理系統</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    

    <style>
        body {
            background-color: #f8f9fa;
        }
        .carousel-item {
            height: 200px;
            background-color: #6c757d;
            border-radius: 12px;
        }
        .carousel-caption {
             background: none;     /* 拿掉深灰框 */
             padding: 0;           /* 不留多餘空白 */
            }

        .rule-box {
            background: #ffffff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<?php
session_start();

// 設定頁面標題
$title = "首頁 - 包裹與公物管理系統";

// include header.php
include 'header.php';
?>

<div class="container my-5">

    <h2 class="text-center mb-4">宿舍包裹與公物管理系統</h2>

    <!-- 旋轉木馬 -->
    <div id="carouselExample" class="carousel slide mb-4" data-bs-ride="carousel" data-bs-interval="4000">

        <div class="carousel-inner">

            <div class="carousel-item active">
                <div class="carousel-caption d-none d-md-block">
                    <h5>📦 包裹搜尋</h5>
                    <p>即時查看包裹送達與領取狀態，避免遺漏重要包裹。</p>
                
                    </a>
                </div>
            </div>

            <div class="carousel-item">
                <div class="carousel-caption d-none d-md-block">
                    <h5>🛠️ 公物借用</h5>
                    <p>清楚紀錄借用與歸還時間，維持宿舍公物良好使用。</p>
                    
                </div>
            </div>

            <div class="carousel-item">
                <div class="carousel-caption d-none d-md-block">
                    <h5>✨ 系統使用提醒</h5>
                    <p>請依規定操作系統，共同維護宿舍管理秩序。</p>
                </div>
            </div>

        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

    </div>

    <!-- 使用規則 -->
    <div class="rule-box">
        <h5 class="mb-3">📌 使用規則</h5>
        <ul class="mb-0">
            <li>包裹請於收到通知後儘速領取，避免占用存放空間。</li>
            <li>領取包裹時請確認姓名與房號是否正確。</li>
            <li>公物借用須於規定時間內歸還，若有損壞請主動回報。</li>
            <li>請勿私下轉借公物，以維護所有住戶權益。</li>
        </ul>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>


<?php include "footer.php"; ?>