<?php 
require_once '../init.php';

// Check if POST contains id, if it does checks what $_POST['type'] is set to then deletes the corresponding entry from the database.
if (is_numeric($_POST['id'])) {
	$id = mysqli_real_escape_string($con,$_POST['id']);
	switch ($_POST['type']) {
		case "contact":
			$query = "DELETE FROM contact_debts WHERE contact_id = $id;";
			if (!mysqli_query($con, $query)) {
				header("Location: /?status=error&message=" . $con->error);
			}		
			$query = "DELETE FROM contacts WHERE id = $id;";
			break;
		case "debt":
			$query = "DELETE FROM debts WHERE id = $id;";
			break;
		default:
			die("Error");
	}
	
	if (mysqli_query($con, $query)) {
		header("Location: /?status=success&message=Action completed successfully");
	} else {
		header("Location: /?status=error&message=" . $con->error);
	}
}
?>