<?php 
    require_once '../init.php';
	
	if (isset($_POST['submit'])) {
		$name = mysqli_real_escape_string($con,$_POST['name']);
		$start_amount = mysqli_real_escape_string($con,$_POST['start_amount']);
		$payment = mysqli_real_escape_string($con,$_POST['payment']);
		$details = mysqli_real_escape_string($con,$_POST['details']);
		$reference = strtolower(mysqli_real_escape_string($con,$_POST['reference']));

		$query = "INSERT INTO debts (debt, reference, start_amount, amount, payment, details) VALUES ('$name', '$reference', '$start_amount', '$start_amount', '$payment', '$details');";
		if (mysqli_query($con, $query)) {
			$status['status'] = "success";
			$status['message'] = "Debt created successfully";
			$id = mysqli_insert_id($con);
		} else {
			$status['status'] = "error";
			$status['message'] = "Error creating Debt: " . mysqli_error($con);
		}
	} 
	echo $twig->render('admin/newdebt.html.twig');
?>