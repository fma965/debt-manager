<?php
	if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
			die('Direct access not allowed');
			exit();
	};

	// Simple function to check multiple conditions and return a bit of HTML.
	function HasPaid($paid,$cost) {
		if($paid > $cost) {
			return 3;
		}elseif($paid == $cost) {
			return 2;
		}elseif($paid < $cost && $paid > 0 ){
			return 1;
		}else{
			return 0;
		}
	}

	function discord_notification($json,$discord_webhook) {
		$ch = curl_init( $discord_webhook );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		curl_setopt( $ch, CURLOPT_POST, 1);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt( $ch, CURLOPT_HEADER, 0);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
		return curl_exec( $ch );
	}
?>
