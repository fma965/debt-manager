<?php
	if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
		die('Direct access not allowed');
		exit();
	};

	// Website Configuration
	define('HOST', 						'');

	// Database Credentials
	define('DB_HOST', 					'');
	define('DB_USER', 					'');
	define('DB_PASS', 					'');
	define('DB_NAME', 					''); 

	// Starling Credentials
	define('STARLING_WEBHOOK_SECRET',	'');	
    
    // Discord Credentials
	define('DISCORD_CLIENT_ID',    		'');
	define('DISCORD_CLIENT_SECRET',		'');
	define('DISCORD_ADMIN', 	   		'');
	define('DISCORD_WEBHOOK', 			'');
	
	// Establish DB connection
	$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		exit();
	}
?>