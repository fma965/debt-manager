<?php    
	require_once '../init.php';
	
	if (isset($_POST['submit'])) {
		$name = mysqli_real_escape_string($con,$_POST['name']);
		$notes = mysqli_real_escape_string($con,$_POST['notes']);
		
		$query = "INSERT INTO users (name, notes) VALUES ('$name', '$notes');";
		if (mysqli_query($con, $query)) {
			$status['status'] = "success";
			$status['message'] = "User created successfully";
			$id = mysqli_insert_id($con);
		} else {
			$status['status'] = "error";
			$status['message'] = "Error creating User: " . mysqli_error($con);
		}
		if(isset($_POST['debts'])) {
			$debts = $_POST['debts'];
			foreach ($debts as $debt) {	
				$debtid = mysqli_real_escape_string($con,$debt);
				$query = "INSERT INTO user_debts (user_id, debt_id) VALUES ('$id', '$debt');";
				if (!mysqli_query($con, $query)) {
					$status['status'] = "error";
					$status['message'] = "Error assigning debts to user: " . mysqli_error($con);
					break;
				}
			}
		}	
		$status['status'] = "success";
		$status['message'] = "User created successfully";
		header("Location: /admin/?status=".$status['status']."&message=".$status['message']);
	} else {
		$query = "SELECT * FROM `debts`;";
		if ($result = mysqli_query($con, $query)) {
			$alldebts = mysqli_fetch_all($result, MYSQLI_ASSOC);
		}
		echo $twig->render('admin/newuser.html.twig', ['alldebts' => $alldebts]);
	}
?>	

