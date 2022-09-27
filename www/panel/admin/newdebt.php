<?php 
    require_once '../init.php';
	
	if (isset($_POST['submit'])) {
		try {
			$db->safeQuery('INSERT INTO debts (debt, reference, start_amount, amount, payment, details) VALUES (?, ?, ?, ?, ?, ?)',[
				$_POST['name'], $_POST['reference'], $_POST['start_amount'], $_POST['start_amount'], $_POST['payment'], $_POST['details']
			]);
			header("Location: /admin/?status=success&message=Debt created successfully");
		} catch (Exception $e) {
			header("Location: /admin/?status=error&message=Error creating Debt: " . $e->getMessage());
		}
	} else {
		echo $twig->render('admin/newdebt.html.twig');
	}
?>