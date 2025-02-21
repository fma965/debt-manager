<?php    
	require_once '../init.php';

	if (!isset($_GET['type'])) {
		echo $twig->render('unauthorized.html.twig');
		exit;
	}

	switch ($_GET['type']) {
		case "debt":
			echo $twig->render('admin/newdebt.html.twig');
			break;
		case "user":
			foreach ($db->safeQuery('SELECT * FROM debts') as $debt) {
				$alldebts[] = $debt;
			}
			echo $twig->render('admin/newuser.html.twig', ['alldebts' => $alldebts]);
			break;
		default:
			echo $twig->render('unauthorized.html.twig');
			break;
	}

?>	

