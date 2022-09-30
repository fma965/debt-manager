<?php
    require_once '/vendor/autoload.php';
    $loader = new \Twig\Loader\FilesystemLoader('/app/panel/templates');
    $twig = new \Twig\Environment($loader);

    require_once '/app/inc/config.php';
    require_once '/app/inc/functions.php'; 

    $status['status'] = isset($_REQUEST['status']) ? $_REQUEST['status'] : "";
    $status['message'] = isset($_REQUEST['message']) ? $_REQUEST['message'] : "";
    $twig->addGlobal('status', $status);

    session_start();

    $script_name = basename($_SERVER['PHP_SELF']);
    if(LoggedIn()) {
        extract($_SESSION['userData']);
        $avatar_url = "https://cdn.discordapp.com/avatars/$discord_id/$avatar.jpg";
        
        $twig->addGlobal('logged_in', $_SESSION['logged_in']);
        $twig->addGlobal('admin', $_SESSION['admin']);
        $twig->addGlobal('discord', ["name" => $name, "avatar" => $avatar_url, "id" => $discord_id]);

        if(!$_SESSION['admin'] && (str_starts_with(dirname($_SERVER['SCRIPT_NAME']),"/admin") || $script_name == "user.php")) {
            echo $twig->render('unauthorized.html.twig');
            exit;
        }
    } elseif(str_starts_with(dirname($_SERVER['SCRIPT_NAME']),"/admin")) {
        echo $twig->render('unauthorized.html.twig');
        exit;
    } elseif(!in_array($script_name, ["index.php", "login.php", "logout.php"])) {
        echo $twig->render('unauthorized.html.twig');
        exit;
    }
?>