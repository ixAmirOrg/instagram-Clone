<?php
session_start();
require_once "includes/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        echo "رمزها یکسان نیستند!";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, username, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $fname, $lname, $email, $username, $hashed);
        if ($stmt->execute()) {
            echo "ثبت‌نام موفقیت‌آمیز بود. حالا وارد شوید.";
            header("Location: login.php");
            exit();
        } else {
            echo "خطا در ثبت‌نام: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram</title> 
    <link rel="stylesheet" href="./sass/vender/bootstrap.css">
    <link rel="stylesheet" href="./sass/vender/bootstrap.min.css">
    
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="./sass/main.css">
</head>
<body>
    <div class="container">
        <div class="sign_up">
            <div class="content">
                <div class="log-on border_insc">
                    <div class="logo">
                        <img src="./images/logo.png" alt="Instagram logo">
                        <p>Sign up to see photos and videos from your friends.</p>
                        <button class="btn log_fac">
                            <a href="#">
                                <img src="./images/facebook_white.png" alt="facebook icon">
                                Log in with Facebook
                            </a>
                        </button>
                        <div class="seperator">
                            <span class="ligne"></span>
                            <span class="ou">OR</span>
                            <span class="ligne"></span>
                        </div>

                    </div>
                    <form method="POST" id="signupForm">
                        <div>
                            <input type="text" name="first_name" id="name" placeholder="First Name" required>
                        </div>
                        <div>
                            <input type="text" name="last_name" id="name" placeholder="Last Name" required>
                        </div>
                        <div>
                            <input type="email" name="email" id="emai" placeholder="Email Address" required>
                        </div>
                        <div>
                            <input type="text" name="username" id="username" placeholder="Username" required>
                        </div>
                        <div>
                            <input type="password" name="password" id="password" placeholder="Password" required>
                        </div>
                        <div>
                            <input type="password" name="confirm_password" id="password" placeholder="Confirm Password" required>
                        </div>
                        <div class="info">
                            <p>
                                People who use our service may have uploaded your contact information to Instagram. 
                                <a href="#">Learn more</a>
                            </p>
                            <p>
                                By signing up, you agree to our 
                                <a href="#">Terms, Privacy Policy and Cookies Policy.</a> 
                            </p>
                        </div>
                        <button class="log_btn" type="submit">
                                Sign Up
                        </button>
                    </form>

                </div>
                <div class="sing-in border_insc">
                    <p>
                        Have an account? 
                        <a href="./login.php">Log in</a>
                    </p>
                </div>
                <div class="download">
                    <p>Get the app.</p>
                    <div>
                        <img src="./images/google_play_icon.png" alt="download app from google play">
                        <img src="./images/microsoft-icon.png" alt="download app from microsoft">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
    <script>
        document.getElementById("signupForm").addEventListener("submit", function(event) {
        const password = document.querySelector("input[name='password']").value;
        const confirm = document.querySelector("input[name='password_confirmation']").value;

        if (password !== confirm) {
            event.preventDefault();
            alert("رمز عبور و تکرار آن با هم مطابقت ندارند.");
        }
    });
    </script>
   
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script> -->
</body>
</html>