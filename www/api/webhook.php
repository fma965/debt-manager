<?php
	//Connect to DB etc
	require '/app/inc/config.php';

	//ob_start(); // Testing

	/** Common hook functions - grab the info, check it, etc. */
	$signature   = base64_decode($_SERVER['HTTP_X_HOOK_SIGNATURE']);
	$webhookData = file_get_contents("php://input");

	/** Sort out the public key */
	$key = STARLING_WEBHOOK_SECRET;
	$formattedKey = "-----BEGIN PUBLIC KEY-----\n" . wordwrap($key, 64, "\n", true) . "\n-----END PUBLIC KEY-----";

	/** Verify the data */
	$verify = openssl_verify($webhookData, $signature, $formattedKey, "sha512WithRSAEncryption");

	if ($verify) {	
		$trans = json_decode($webhookData, true);
		if($trans['content']['direction'] == "IN") {
			$amount = number_format($trans['content']['amount']['minorUnits']/100, 2, ".", "");
			
			$query = "SELECT * FROM debts WHERE reference = '".strtolower($trans['content']['reference'])."'";
			$sqltran = mysqli_query($con, $query);
			while ($debt = mysqli_fetch_array($sqltran)) {	
				$query = "UPDATE debts SET lastpaymentdate='".strtotime($trans['content']["transactionTime"])."', lastpaymentamount='".$amount."' WHERE id = '".$debt['id']."';";
				if (!mysqli_query($con, $query)) {
					http_response_code(500); exit;
				}
			}

			$query = "INSERT INTO `transactions` set 
					`transactionId`  = '".$trans['content']['feedItemUid']."',
					`currency`  = '".$trans['content']['amount']['currency']."', 
					`amount`	 = '".$amount."',
					`created` = '".strtotime($trans['content']["transactionTime"])."', 
					`reference` = '".strtolower($trans['content']["reference"])."', 
					`counterPartyName` = '".strtolower($trans['content']["counterPartyName"])."'; ";;
			if (!mysqli_query($con, $query)) {
				http_response_code(500); exit;
			}
		}
			
	} else {
		http_response_code(500); exit;
	}	
	mysqli_close($con);
	
	//file_put_contents("debug.txt",ob_get_clean());  // Testing
?>