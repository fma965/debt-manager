<?php 
	require_once '../../init.php';
	
	if (isset($_POST['type'])) {
		try {
			$id = 0;
			switch ($_POST['type']) {
				case "user":
					$db->safeQuery('INSERT INTO users (name, notes) VALUES (?, ?)',[$_POST['name'], $_POST['notes']]);
		
					$id = mysqli_insert_id($db);
					
					if(isset($_POST['debts'])) {
						foreach ($_POST['debts'] as $debt) {	
							$db->safeQuery('INSERT INTO user_debts (user_id, debt_id) VALUES (?, ?)',[$id, $debt]);
						}
					}	
					break;
				case "debt":
					$db->safeQuery('INSERT INTO debts (debt, reference, start_amount, amount, payment, details) VALUES (?, ?, ?, ?, ?, ?)',[
						$_POST['name'], $_POST['reference'], $_POST['start_amount'], $_POST['start_amount'], $_POST['payment'], $_POST['details']
					]);
					$id = mysqli_insert_id($db);
					break;
				case "transaction":
					$date = strtotime($_POST['created']);
					
					$db->safeQuery('INSERT INTO transactions (transactionId, currency, amount, created, reference, counterPartyName) VALUES (?, ?, ?, ?, ?, ?)',[
						$_POST['transactionId'], 'GBP', $_POST['amount'], $date, $_POST['reference'], $_POST['counterPartyName']
					]);
						
					$db->safeQuery('UPDATE debts SET amount=amount - ?, lastpaymentdate=?, lastpaymentamount=? WHERE reference=?',[$_POST['amount'], $date, $_POST['amount'], $_POST['reference']]);
					break;
				default:
					die("Error");
			}
			echo json_encode(['id' => $id, 'status' => 'success', 'message' => ucfirst($_POST['type']) . ' created successfully']);
		} catch (Exception $e) {
			$message = 'Error creating ' . ucfirst($_POST['type']) . ": " . $e->getMessage();
			echo json_encode(['status' => 'error', 'message' => $message]);
		}	
	} else {
		http_response_code(500);
		exit;
	}
?>
