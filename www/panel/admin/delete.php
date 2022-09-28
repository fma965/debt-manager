<?php 
	require_once '../init.php';

	if (isset($_POST['id'])) {
		try {
			switch ($_POST['type']) {
				case "user":
					$db->safeQuery('DELETE FROM user_debts WHERE user_id=?',[$_POST['id']]);
					$db->safeQuery('DELETE FROM users WHERE id=?',[$_POST['id']]);
					break;
				case "debt":
					$db->safeQuery('DELETE FROM user_debts WHERE debt_id=?',[$_POST['id']]);
					$db->safeQuery('DELETE FROM debts WHERE id=?',[$_POST['id']]);
					break;
				case "transaction":
					$db->safeQuery('DELETE FROM transactions WHERE transactionId=?',[$_POST['id']]);
					break;
				default:
					die("Error");
			}
			echo json_encode(['status' => 'success', 'message' => ucfirst($_POST['type']) . ' deleted successfully']);
		} catch (Exception $e) {
			$message = 'Error deleting ' . ucfirst($_POST['type']) . ": " . $e->getMessage();
			echo json_encode(['status' => 'error', 'message' => $message]);
		}	
	}
?>
