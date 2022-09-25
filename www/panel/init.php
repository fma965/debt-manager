<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '/vendor/autoload.php';
require_once '/app/inc/config.php';
require_once '/app/inc/functions.php'; 

$loader = new \Twig\Loader\FilesystemLoader('/app/panel/templates');
$twig = new \Twig\Environment($loader);

$status['status'] = isset($_GET['status']) ? $_GET['status'] : "";
$status['message'] = isset($_GET['message']) ? $_GET['message'] : "";
$twig->addGlobal('status', $status);

session_start();

if(isset($_SESSION['userData'])) {
    if(dirname($_SERVER['SCRIPT_NAME']) == "/admin" && !$_SESSION['admin']) {
        exit("Unauthorized!");
    }
    $twig->addGlobal('logged_in', $_SESSION['logged_in']);
    $twig->addGlobal('admin', $_SESSION['admin']);
} elseif(basename($_SERVER['PHP_SELF']) != "index.php") {
    header("Location: /login.php");
}   
?>