<?php
session_start();
require_once "includes/connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// گرفتن اطلاعات فعلی کاربر برای نمایش توی فرم
$stmt = $conn->prepare("SELECT avatar, bio FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$avatar = $user['avatar'];
$bio = $user['bio'];
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // پردازش بیو
    $new_bio = trim($_POST['bio'] ?? '');

    // پردازش آپلود عکس
    $avatar_file = $_FILES['avatar'] ?? null;
    $avatar_link = trim($_POST['avatar_link'] ?? '');

    $new_avatar = null;

    // اگر فایل آپلود شده بدون خطاست و حجمش مناسبه
    if ($avatar_file && $avatar_file['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($avatar_file['type'], $allowed_types)) {
            $errors[] = "فرمت تصویر باید jpeg، png، gif یا webp باشد.";
        } elseif ($avatar_file['size'] > 2 * 1024 * 1024) { // 2 مگابایت
            $errors[] = "حجم تصویر نباید بیشتر از ۲ مگابایت باشد.";
        } else {
            $ext = pathinfo($avatar_file['name'], PATHINFO_EXTENSION);
            $new_avatar = 'uploads/' . uniqid('avatar_') . '.' . $ext;
            if (!move_uploaded_file($avatar_file['tmp_name'], $new_avatar)) {
                $errors[] = "آپلود تصویر با خطا مواجه شد.";
                $new_avatar = null;
            }
        }
    }

    // اگر لینک آواتار وارد شده بود و آپلود انجام نشده، لینک رو اولویت قرار بده
    if (!$new_avatar && !empty($avatar_link)) {
        if (filter_var($avatar_link, FILTER_VALIDATE_URL)) {
            $new_avatar = $avatar_link;
        } else {
            $errors[] = "لینک آواتار معتبر نیست.";
        }
    }

    // اگر خطا نبود، آپدیت کن
    if (empty($errors)) {
        if ($new_avatar) {
            $stmt = $conn->prepare("UPDATE users SET bio = ?, avatar = ? WHERE id = ?");
            $stmt->bind_param("ssi", $new_bio, $new_avatar, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET bio = ? WHERE id = ?");
            $stmt->bind_param("si", $new_bio, $user_id);
        }

        if ($stmt->execute()) {
            $success = "پروفایل با موفقیت به‌روزرسانی شد.";
            $bio = $new_bio;
            $avatar = $new_avatar ?? $avatar;
        } else {
            $errors[] = "خطا در به‌روزرسانی پروفایل.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8" />
    <title>ویرایش پروفایل</title>
    <style>
        body { font-family: vazirmatn, sans-serif; direction: rtl; padding: 20px; }
        label { display: block; margin-top: 15px; }
        textarea { width: 100%; height: 100px; }
        input[type="text"], input[type="url"] { width: 100%; padding: 8px; }
        .avatar-preview { margin-top: 15px; max-width: 150px; }
        .errors { background: #f8d7da; color: #842029; padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .success { background: #d1e7dd; color: #0f5132; padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        button { margin-top: 20px; padding: 10px 15px; font-size: 16px; cursor: pointer; }
    </style>
</head>
<body>

<h2>ویرایش پروفایل</h2>

<?php if (!empty($errors)): ?>
    <div class="errors">
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?php echo htmlspecialchars($err); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<form action="" method="post" enctype="multipart/form-data">
    <label for="bio">بیوگرافی:</label>
    <textarea id="bio" name="bio" style="font-family: vazirmatn, sans-serif;"><?php echo htmlspecialchars($bio); ?></textarea>

    <label for="avatar">آپلود عکس آواتار (jpeg, png, gif, webp | max 2MB):</label>
    <input type="file" id="avatar" name="avatar" accept="image/*" />

    <label for="avatar_link">یا لینک مستقیم عکس آواتار:</label>
    <input type="url" id="avatar_link" name="avatar_link" placeholder="https://example.com/avatar.jpg" value="<?php echo htmlspecialchars(is_string($avatar) && filter_var($avatar, FILTER_VALIDATE_URL) ? $avatar : ''); ?>" />

    <?php if (!empty($avatar)): ?>
        <div class="avatar-preview">
            <p>آواتار فعلی:</p>
            <img src="<?php echo htmlspecialchars($avatar); ?>" alt="avatar" style="max-width: 150px; border-radius: 50%;" />
        </div>
    <?php endif; ?>

    <button type="submit" style="font-family: vazirmatn, sans-serif;">ذخیره تغییرات</button>
</form>

<p><a href="profile.php">بازگشت به پروفایل</a></p>

</body>
</html>