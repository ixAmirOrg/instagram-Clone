<?php
session_start();
require_once "includes/init.php";
require_once "classes/Auth.php";
Auth::logout();

// require "includes/init.php";
// echo "user logged out successfully";

Url::redirect("login.php");