<?php 
	require_once '../../init.php';
	
	if (isset($_POST['type'])) {
		try {
			$id = 0;
			switch ($_POST['type']) {
				case "user":          
					$db->safeQuery('DELETE FROM user_debts WHERE user_id=?',[$_POST['id']]);

                    if(isset($_POST['debts'])) {
                        foreach ($_POST['debts'] as $debt) {	
                            if($debt != 0) $db->safeQuery('INSERT INTO user_debts (user_id, debt_id) VALUES (?, ?)',[$_POST['id'], $debt]);
                        }
                    }

                    $db->safeQuery('UPDATE users SET name=?, notes=?, discord_id=? WHERE id=?',[$_POST['name'], $_POST['notes'], $_POST['discord_id'], $_POST['id']]);
					break;
				case "debt":
					$db->safeQuery('DELETE FROM user_debts WHERE debt_id=?',[$_POST['id']]);

					if(isset($_POST['contacts'])) {
                        foreach ($_POST['contacts'] as $contact) {	
                            if($contact != 0) $db->safeQuery('INSERT INTO user_debts (debt_id, user_id) VALUES (?, ?)',[$_POST['id'], $contact]);
                        }
                    }

					$db->safeQuery('UPDATE debts SET debt=?, amount=?, start_amount=?, payment=?, details=?, reference=? WHERE id=?',[
                        $_POST['name'], $_POST['amount'], $_POST['start_amount'], $_POST['payment'], $_POST['details'], $_POST['reference'], $_POST['id']
                    ]);

                    break;
				case "transaction":
					// TBD
				default:
					die("Error");
			}
			echo json_encode(['id' => $id, 'status' => 'success', 'message' => ucfirst($_POST['type']) . ' updated successfully']);
		} catch (Exception $e) {
			$message = 'Error updating ' . ucfirst($_POST['type']) . ": " . $e->getMessage();
			echo json_encode(['status' => 'error', 'message' => $message]);
		}	
	} else {
		http_response_code(500);
		exit;
	}
?>
