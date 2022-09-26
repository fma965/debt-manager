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
    $twig->addGlobal('logged_in', $_SESSION['logged_in']);
    $twig->addGlobal('admin', $_SESSION['admin']);
    
    if((dirname($_SERVER['SCRIPT_NAME']) == "/admin" || $_SERVER['SCRIPT_NAME'] == "/user.php") && !$_SESSION['admin']) {
        echo $twig->render('unauthorized.html.twig');
        exit();
    }
} elseif(dirname($_SERVER['SCRIPT_NAME']) == "/admin" || (dirname($_SERVER['SCRIPT_NAME']) == "/" && basename($_SERVER['PHP_SELF']) != "index.php")) {
    header("Location: /");
} 
?>