<?php
class Auth {
    public static function isLoggedIn(){
        if (isset($_SESSION["is_logged_in"])) {
        return true;
        }else{
            return false;
        }
    }
    public static function authAttempt($conn,$username,$password){
    include "includes/connection.php";
    $sql = "SELECT * from users WHERE username = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password,$user['password'])) {
            return true;
        }
        
    }else {
        return false;
    }
    return false;
    }
    public static function login(){
        $_SESSION["is_logged_in"] = true;
    }
    public static function logout(){
        $_SESSION["is_logged_in"] = false;
        session_unset();
        session_destroy();
    }
    public static function requireAuth(){
        if (!isset($_SESSION["is_logged_in"])) {
        die("you are not acess to this page !!");
        }
    }
}
