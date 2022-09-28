<?php 
    require_once 'init.php';

    if (!isset($_REQUEST['id']) || isset($_REQUEST['id']) && !is_numeric($_REQUEST['id'])) {
        header("Location: /");
        exit; 
    }

    if (isset($_POST['submit'])) {       
        try {
            $db->safeQuery('DELETE FROM user_debts WHERE user_id=?',[$_POST['id']]);
            
            if(isset($_POST['debts'])) {
                foreach ($_POST['debts'] as $debt) {	
                    if($debt != 0) $db->safeQuery('INSERT INTO user_debts (user_id, debt_id) VALUES (?, ?)',[$_POST['id'], $debt]);
                }
            }

            $db->safeQuery('UPDATE users SET name=?, notes=? WHERE id=?',[$_POST['name'], $_POST['notes'], $_POST['id']]);
            $status = MysqlSuccess("User");
        } catch (Exception $e) {
            $status = MysqlError("Error updating Debt: " . $e->getMessage());
        }
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