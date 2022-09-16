<?php

//Connect to DB etc
require '/app/inc/config.php';

//Testing
//ob_start();
// foreach (getallheaders() as $name => $value) {
//    echo "$name: $value\n";
// }

//If no X-HOOK-SIGNATURE Header is set then kill the script
//if (empty($_SERVER['HTTP_X_HOOK_SIGNATURE'])) {	die("Nope, No Hack Pls!");}

//Get raw POST to ensure signature integrity
$post = file_get_contents('php://input');

//Generate Base64 of SHA512 Digest to validate request integrity
//$recSig = base64_encode(hash("sha512",$secret.$post,true));
//$reqSig = $_SERVER['HTTP_X_HOOK_SIGNATURE']; //Gets the X-HOOK-SIGNATURE Header.
//If calculated signature matches the signature included in the header then do things else do other things
//if ($recSig == $reqSig) {
	
	//Decode JSON from POST
	$trans = json_decode($post, true);

	//var_dump($trans);

	//Checks if Reference matches a known reference in the 'debts' DB table 
	$query = "SELECT * FROM debts WHERE reference = '".strtolower($trans['content']['reference'])."'";
	//echo $query;
	$sqltran = mysqli_query($con, $query)or die(mysqli_error($con));
	while ($debt = mysqli_fetch_array($sqltran)) {	
		//print_r($debt);
		$amount = number_format($trans['content']['amount']['minorUnits']/100, 2);
		$query = "INSERT INTO `transactions` set 
				`transactionId`  = '".$trans['content']['feedItemUid']."',
				`currency`  = '".$trans['content']['amount']['currency']."', 
				`amount`	 = '".$amount."',
				`created` = '".strtotime($trans['content']["transactionTime"])."', 
				`reference` = '".strtolower($trans['content']["reference"])."', 
				`counterPartyName` = '".strtolower($trans['content']["counterPartyName"])."'; ";

		$query .= "UPDATE debts SET lastpaymentdate='".strtotime($trans['content']["transactionTime"])."', lastpaymentamount='".$amount."' WHERE id = '".$debt['id']."';";
		if (!mysqli_multi_query($con, $query)) {
			http_response_code(500); exit;
		}
	}
		
	//Signature does not match kill the script! (potential tampering or hacking)
	//} else {
	//	die("Invalid Signature, Aborting");
	//	exit();
	//}
		
	mysqli_close($con);

	//file_put_contents("debug.txt",ob_get_clean());
?>