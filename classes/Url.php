<?php
class Url
{
    public static function redirect($path){
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            $protocol = 'https';
        }else{
            $protocol = 'http';

        }
        header("Location: $protocol://" . $_SERVER['HTTP_HOST'] . '/Final-Project/' . $path);
        exit();
    }
}
