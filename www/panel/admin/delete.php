<?php 
	require_once '../init.php';

	if (is_numeric($_POST['id'])) {
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
				default:
					die("Error");
			}
			header("Location: /admin/?status=success&message=".ucfirst($_POST['type'])." deleted successfully");
		} catch (Exception $e) {
			header("Location: /admin/?status=error&message=Error deleting " . ucfirst($_POST['type']) . ": " . $e->getMessage());
		}	
	}
?>