<?php    
	require_once '../init.php';
	
	if (isset($_POST['submit'])) {
		$name = mysqli_real_escape_string($con,$_POST['name']);
		$notes = mysqli_real_escape_string($con,$_POST['notes']);
		
		$query = "INSERT INTO contacts (name, notes) VALUES ('$name', '$notes');";
		if (mysqli_query($con, $query)) {
			$status['status'] = "success";
			$status['message'] = "Contact created successfully";
			$id = mysqli_insert_id($con);
		} else {
			$status['status'] = "error";
			$status['message'] = "Error creating Contact: " . mysqli_error($con);
		}
		if(isset($_POST['debts'])) {
			$debts = $_POST['debts'];
			foreach ($debts as $debt) {	
				$debtid = mysqli_real_escape_string($con,$debt);
				$query = "INSERT INTO contact_debts (contact_id, debt_id) VALUES ('$id', '$debt');";
				if (!mysqli_query($con, $query)) {
					$status['status'] = "error";
					$status['message'] = "Error assigning debts to contact: " . mysqli_error($con);
					break;
				}
			}
		}	
		$status['status'] = "success";
		$status['message'] = "Contact created successfully";
	}

$query = "SELECT * FROM `debts`;";
if ($result = mysqli_query($con, $query)) {
	$alldebts = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
echo $twig->render('admin/newcontact.html.twig', ['alldebts' => $alldebts]);
?>	

