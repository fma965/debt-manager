<?php
	if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
		die('Direct access not allowed');
		exit;
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
	
	// Debug
	//ini_set('display_errors', 1);
	//ini_set('display_startup_errors', 1);
	//error_reporting(E_ALL);

	// Establish DB connection
	require "DBClass.php";
	$db = new DBClass(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ($db->connect_error) {
		die("Connection failed: " . $db->connect_error);
	}
?>