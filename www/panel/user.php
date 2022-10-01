<?php 
    require_once 'init.php';

    if (!isset($_REQUEST['id']) || isset($_REQUEST['id']) && !is_numeric($_REQUEST['id'])) {
        $_SESSION['admin'] ? header("Location: /admin/new.php?type=user") : header("Location: /");
        exit; 
    }

    $debts = [];
    $alldebts = [];
    $remaining = 0; 

    try {
        $details = $db->row('SELECT * FROM users WHERE id = ? LIMIT 1;', [$_GET['id']]);
        if ($details) {
            foreach ($db->safeQuery('SELECT * FROM debts') as $debt) {
                $alldebts[] = $debt;
            }

            foreach ($db->safeQuery('SELECT * FROM user_debts INNER JOIN debts ON debts.id=user_debts.debt_id WHERE user_id = ?',[$details['id']]) as $debt) {
                $remaining +=$debt['amount'];
                $debts[] = $debt;
            }
        
            $details['total_cost'] = $remaining;
        }
    } catch (Exception $e) {
        $status = MysqlError(template:"main.html.twig");
    }
    echo $twig->render('user.html.twig', ['alldebts' => $alldebts, 'debts' => $debts, 'details' => $details, 'status' => $status]); 
?>