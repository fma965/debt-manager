<?php 
require_once '../init.php';

// Check if POST contains id, if it does checks what $_POST['type'] is set to then deletes the corresponding entry from the database.
if (is_numeric($_POST['id'])) {
	$id = mysqli_real_escape_string($db,$_POST['id']);
	switch ($_POST['type']) {
		case "user":
			$query = "DELETE FROM user_debts WHERE user_id = $id;";
			if (!mysqli_query($db, $query)) {
				header("Location: /admin/?status=error&message=" . $db->error);
			}		
			$query = "DELETE FROM users WHERE id = $id;";
			break;
		case "debt":
			$query = "DELETE FROM user_debts WHERE debt_id = $id;";
			if (!mysqli_query($db, $query)) {
				header("Location: /admin/?status=error&message=" . $db->error);
			}	
			$query = "DELETE FROM debts WHERE id = $id;";
			break;
		default:
			die("Error");
	}
	if (mysqli_query($db, $query)) {
		header("Location: /admin/?status=success&message=".ucfirst($_POST['type'])." deleted successfully");
	} else {
		header("Location: /admin/?status=error&message=" . $db->error);
	}
}

?>