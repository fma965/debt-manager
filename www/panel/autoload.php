<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '/vendor/autoload.php';
require '/app/inc/config.php';
require '/app/inc/functions.php'; 

$loader = new \Twig\Loader\FilesystemLoader('/app/panel/templates');
$twig = new \Twig\Environment($loader);

$status['status'] = isset($_GET['status']) ? $_GET['status'] : "";
$status['message'] = isset($_GET['message']) ? $_GET['message'] : "";
?>