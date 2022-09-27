<?php
    require '/app/inc/config.php';
    
    if(!isset($_GET['code'])) {
        header("Location: https://discord.com/api/oauth2/authorize?client_id=".DISCORD_CLIENT_ID."&redirect_uri=" . urlencode(HOST . "/login.php"). "&response_type=code&scope=identify");
        exit();
    }

    $discord_code = $_GET['code'];

    $payload = [
        'code'=>$discord_code,
        'client_id'=>DISCORD_CLIENT_ID,
        'client_secret'=>DISCORD_CLIENT_SECRET,
        'grant_type'=>'authorization_code',
        'redirect_uri'=> HOST.'/login.php',
        'scope'=>'identify',
    ];

    $payload_string = http_build_query($payload);
    $discord_token_url = "https://discordapp.com/api/oauth2/token";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $discord_token_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    $result = json_decode($result,true);

    $access_token = $result['access_token'];

    if(!$access_token) {
        exit("Invalid Access Token");
    }

    $discord_users_url = "https://discordapp.com/api/users/@me";
    $header = array("Authorization: Bearer $access_token", "Content-Type: application/x-www-form-urlencoded");

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_URL, $discord_users_url);
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    $result = json_decode($result, true);

    if(!$result) {
        exit("Something went wrong!");
    }

    session_start();

    $_SESSION['logged_in'] = true;
    $_SESSION['admin'] = $result['id'] == DISCORD_ADMIN;
    $_SESSION['userData'] = [
        'name'=>$result['username'],
        'discord_id'=>$result['id'],
        'avatar'=>$result['avatar']
    ];

    $query = "SELECT * FROM users WHERE discord_id='".$result['id']."';";
    $sqlResult = mysqli_query($con, $query);
    if (mysqli_num_rows($sqlResult) > 0) {
        //Discord ID exists already
  	} else {
        $query = "INSERT INTO users (name, discord_id) VALUES ('".$result['username']."', '".$result['id']."');";
        $sqlResult = mysqli_query($con, $query);
    }
    header("Location: /");
    exit();
?>