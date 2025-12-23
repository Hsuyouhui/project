<?php
$host = 'localhost';
$db   = 'from'; 
$user = 'root'; 
$pass = ''; 
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("資料庫連線失敗：" . $e->getMessage());
}
?>
<script>
function toggleAbout(show) {
    const about = document.getElementById('about-section');
    if(show){
        about.style.display = 'block';
        setTimeout(() => {
            about.scrollIntoView({behavior:'smooth'});
        }, 100);
    } else {
        about.style.display = 'none';
        window.scrollTo({top:0, behavior:'smooth'});
    }
}
</script>
