<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "instagram_clone";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("❌ اتصال به دیتابیس با خطا مواجه شد: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// echo "✅ اتصال به دیتابیس با موفقیت انجام شد";
?>
