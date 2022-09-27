<?php    
	require_once '../init.php';
	
	if (isset($_POST['submit'])) {
		try {
			$db->safeQuery('INSERT INTO users (name, notes) VALUES (?, ?)',[
				$_POST['name'], $_POST['notes']
			]);

			$id = mysqli_insert_id($db);
			
			if(isset($_POST['debts'])) {
				foreach ($_POST['debts'] as $debt) {	
					$db->safeQuery('INSERT INTO user_debts (user_id, debt_id) VALUES (?, ?)',[$id, $debt]);
				}
			}	
			header("Location: /admin/?status=success&message=User created successfully");
		} catch (Exception $e) {
			header("Location: /admin/?status=error&message=Error creating User: " . $e->getMessage());
		}
	} else {
		foreach ($db->safeQuery('SELECT * FROM debts') as $debt) {
			$alldebts[] = $debt;
		}
		echo $twig->render('admin/newuser.html.twig', ['alldebts' => $alldebts]);
	}
?>	

