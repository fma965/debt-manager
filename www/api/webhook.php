<?php
	require '/app/inc/config.php';
	
	if(!isset($_SERVER['HTTP_X_HOOK_SIGNATURE'])) {
		http_response_code(500);
		exit;
	}
	
	/** Common hook functions - grab the info, check it, etc. */
	$signature   = base64_decode($_SERVER['HTTP_X_HOOK_SIGNATURE']);
	$webhookData = file_get_contents("php://input");

	/** Sort out the public key */
	$key = STARLING_WEBHOOK_SECRET;
	$formattedKey = "-----BEGIN PUBLIC KEY-----\n" . wordwrap($key, 64, "\n", true) . "\n-----END PUBLIC KEY-----";

	/** Verify the data */
	$verify = openssl_verify($webhookData, $signature, $formattedKey, "sha512WithRSAEncryption");
	
	if ($verify) {	
		/** Extract the data */
		$trans = json_decode($webhookData, true);
		if($trans['content']['direction'] == "IN") {
			$transaction_id = $trans['content']['feedItemUid'];
			$currency = $trans['content']['amount']['currency'];
			$amount = number_format($trans['content']['amount']['minorUnits']/100, 2, ".", "");
			$reference = trim(strtolower($trans['content']['reference']));
			$created = strtotime($trans['content']["transactionTime"]);
			$counterPartyName = $trans['content']["counterPartyName"];
			
			/** Get Debt ID from Database using Reference */
			try {
				$debt = $db->row('SELECT id, amount FROM debts WHERE reference = ?', [$reference]);
				if ($debt) {
					$debt_amount = $debt['amount'] - $amount;
					/** Update Debt's `lastpaymentdate`, `lastpaymentamount` and `amount` */
					$db->safeQuery('UPDATE debts SET lastpaymentdate=?, lastpaymentamount=?, amount=? WHERE id=?',[$created, $amount, $debt_amount, $debt['id']]);
				}

				/** Insert transaction in to database */

				$db->safeQuery('INSERT INTO transactions (transactionId, currency, amount, created, reference, counterPartyName) VALUES (?, ?, ?, ?, ?, ?)',[
					$transaction_id, $currency, $amount, $created, $reference, $counterPartyName
				]);
			} catch (Exception $e) {
				http_response_code(500);
				exit;
			}
		}
	} else {
		http_response_code(500);
		exit;
	}	

	mysqli_close($db);
?>