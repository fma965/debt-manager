<?php
	if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
		die('Direct access not allowed');
		exit();
	};

	// Database Credentials
	$db_host		= "";
	$db_user		= "";
	$db_pass		= "";
	$db_database	= ""; 
	
	// Starling Configuration
	define("STARLING_WEBHOOK_SECRET","");
	
	// Other Configuration Options
	$startdate = 1;
	$finishdate = 31;
	
	$discord_webhook = "";
    
    // Discord OAuth
	$discord_redirect_url = "";
	$discord_admin_id = "";
	$discord_client_id = "";
	$discord_client_secret = "";
	
	// Establish DB connection
	$con = mysqli_connect($db_host, $db_user, $db_pass, $db_database);
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	error_reporting(E_ALL^E_NOTICE);
?>
