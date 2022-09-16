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
	
	// Formats a 'unix timecode' to a date
	function formatDate($date) {
		$date = gmdate("Ymd",$date);
		$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		$year = substr($date, 0, 4);
		$month = $months[intval(substr($date, 4, 2))-1];
		$day = (int)substr($date, -2);
		return sprintf("%d %s %d", $day, $month, $year);
	}
	
	// Function found on stackoverflow, used to get date suffix
	function ordinal_suffix($num){
		$num = $num % 100; // protect against large numbers
		if($num < 11 || $num > 13){
			 switch($num % 10){
				case 1: return $num .'st';
				case 2: return $num .'nd';
				case 3: return $num .'rd';
			}
		}
		return $num .'th';
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
