<?php    
	require_once '../init.php';
	
	if (isset($_POST['submit'])) {
		$name = mysqli_real_escape_string($db,$_POST['name']);
		$notes = mysqli_real_escape_string($db,$_POST['notes']);
		
		$query = "INSERT INTO users (name, notes) VALUES ('$name', '$notes');";
		if (mysqli_query($db, $query)) {
			$status['status'] = "success";
			$status['message'] = "User created successfully";
			$id = mysqli_insert_id($db);
		} else {
			$status['status'] = "error";
			$status['message'] = "Error creating User: " . mysqli_error($db);
		}
		if(isset($_POST['debts'])) {
			$debts = $_POST['debts'];
			foreach ($debts as $debt) {	
				$debtid = mysqli_real_escape_string($db,$debt);
				$query = "INSERT INTO user_debts (user_id, debt_id) VALUES ('$id', '$debt');";
				if (!mysqli_query($db, $query)) {
					$status['status'] = "error";
					$status['message'] = "Error assigning debts to user: " . mysqli_error($db);
					break;
				}
			}
		}	
		$status['status'] = "success";
		$status['message'] = "User created successfully";
		header("Location: /admin/?status=".$status['status']."&message=".$status['message']);
	} else {
		$query = "SELECT * FROM `debts`;";
		if ($result = mysqli_query($db, $query)) {
			$alldebts = mysqli_fetch_all($result, MYSQLI_ASSOC);
		}
		echo $twig->render('admin/newuser.html.twig', ['alldebts' => $alldebts]);
	}
?>	

