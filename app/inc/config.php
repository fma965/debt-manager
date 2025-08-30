<?php
    if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
            die('Direct access not allowed');
            exit;
    };

    // Website Configuration
    define('HOST',                                  getenv('APP_HOST'));

    // Database Credentials
    define('DB_TYPE',                               getenv('DB_TYPE'));
    define('DB_HOST',                               getenv('DB_HOST'));
    define('DB_USER',                               getenv('DB_USER'));
    define('DB_PASS',                               getenv('DB_PASS'));
    define('DB_NAME',                               getenv('DB_NAME'));

    // Starling Credentials
    define('STARLING_WEBHOOK_SECRET',               getenv('STARLING_WEBHOOK_SECRET')); 

    // Discord Credentials
    define('DISCORD_CLIENT_ID',                     getenv('DISCORD_CLIENT_ID'));
    define('DISCORD_CLIENT_SECRET',                 getenv('DISCORD_CLIENT_SECRET'));
    define('DISCORD_ADMIN',                         getenv('DISCORD_ADMIN'));
    define('DISCORD_WEBHOOK',                       getenv('DISCORD_WEBHOOK'));

    // Debug
    if(getenv('DEBUG')) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    // Establish DB connection
    require "DBClass.php";
    try {
        $db = new DBClass(DB_TYPE, DB_HOST, DB_USER, DB_PASS, DB_NAME);
    } catch (Exception $e) {
        die($e->getMessage());
    }
?>