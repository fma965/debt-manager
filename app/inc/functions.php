<?php
	if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
			die('Direct access not allowed');
			exit;
	};

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

	function MysqlError($message = "", $template = "parts/status.html.twig") {
		global $twig, $e;
		$status = ['status' => 'error', 'message' => $message == "" ? "Error: " . $e->getMessage() : $message];
		if($template == "parts/status.html.twig") return $status;
		echo $twig->render($template, ['status' => $status]);
		exit;
	}

	function MysqlSuccess($type = "record") {
		$message = $type . " updated successfully";
		$status = ['status' => 'success', 'message' => $message];
		return $status;
	}

	function LoggedIn() {
		if(isset($_SESSION['logged_in'])) return $_SESSION['logged_in'];
		return false;
	}

	// function discord_notification($json) {
	// 	$ch = curl_init( DISCORD_WEBHOOK );
	// 	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	// 	curl_setopt( $ch, CURLOPT_POST, 1);
	// 	curl_setopt( $ch, CURLOPT_POSTFIELDS, $json);
	// 	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	// 	curl_setopt( $ch, CURLOPT_HEADER, 0);
	// 	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
	// 	return curl_exec( $ch );
	// }
?>
