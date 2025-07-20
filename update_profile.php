<?php
session_start();
require 'includes/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$bio = trim($_POST['bio'] ?? '');
$avatar_link = trim($_POST['avatar_link'] ?? '');
$avatar_file = $_FILES['avatar'] ?? null;

$avatar_path = null;

// اگر فایل عکس آپلود شده باشه:
if ($avatar_file && $avatar_file['error'] === 0) {
    $ext = pathinfo($avatar_file['name'], PATHINFO_EXTENSION);
    $avatar_path = 'uploads/' . uniqid('avatar_') . '.' . $ext;
    move_uploaded_file($avatar_file['tmp_name'], $avatar_path);
}
// اگر لینک وارد شده باشه ولی فایل آپلود نشده باشه:
elseif (!empty($avatar_link)) {
    $avatar_path = $avatar_link;
}

// کوئری آپدیت
if ($avatar_path) {
    $sql = "UPDATE users SET bio = ?, avatar = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $bio, $avatar_path, $user_id);
} else {
    $sql = "UPDATE users SET bio = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $bio, $user_id);
}

$stmt->execute();
header("Location: profile.php");
exit();