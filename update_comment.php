<?php
session_start();
require_once "includes/connection.php";

// بررسی لاگین بودن کاربر
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// گرفتن داده‌ها از فرم
$user_id = $_SESSION['user_id'];
$comment = trim($_POST['comment-input'] ?? '');
$post_id = (int) ($_POST['post_id'] ?? 0);

if ($comment && $post_id > 0) {
    $stmt = $conn->prepare("INSERT INTO comments (user_id, post_id, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $post_id, $comment);
    $stmt->execute();
}

// بعد از ثبت، ریدایرکت کنه به همون صفحه قبلی (home.php)
header("Location: home.php");
exit;