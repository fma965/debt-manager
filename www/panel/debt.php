<?php
    require_once 'init.php';

    if (!isset($_REQUEST['id']) || isset($_REQUEST['id']) && !is_numeric($_REQUEST['id'])) {
        header("Location: /");
    }
   
    if (isset($_POST['submit'])) {
        try {
            $db->safeQuery('UPDATE debts SET debt=?, amount=?, start_amount=?, payment=?, details=?, reference=? WHERE id=?',[
                $_POST['name'], $_POST['amount'], $_POST['start_amount'], $_POST['payment'], $_POST['details'], $_POST['reference'], $_POST['id']
            ]);
            $status = MysqlSuccess("Debt");
        } catch (Exception $e) {
            $status = MysqlError("Error updating Debt: " . $e->getMessage());
        }
    } 

    $authorized = false;
    $alltransactions = [];
    $details = [];
    $totalpaidthismonth = 0;
    $first = mktime(0,0,0, date('n'), 1, date('Y'));
    $last = mktime(23,59,59, (date('n') + 1 ), 31, date('Y'));

    try {
        foreach ($db->safeQuery('SELECT users.discord_id, debts.* FROM debts INNER JOIN user_debts ON debts.id = user_debts.debt_id INNER JOIN users ON users.id = user_debts.user_id WHERE debts.id = ?', [$_GET['id']]) as $row) {
            if($row['discord_id'] == $_SESSION['userData']['discord_id'] || $_SESSION['admin']) $authorized = true;
        }
        if(isset($row)) {
            foreach ($db->safeQuery('SELECT * FROM `transactions` WHERE `reference` = ? ORDER BY `created` DESC;', [$row['reference']]) as $transaction) {
                if ($transaction['created'] > $first && $transaction['created'] < $last) $totalpaidthismonth += $transaction['amount'];
                $alltransactions[] = $transaction;
            }
            $details = $row;
        }
    } catch (Exception $e) {
        $status = MysqlError(template:"wrapper.html.twig");
    } 

    if($authorized) {
        echo $twig->render('debt.html.twig', [
            'transactions' => $alltransactions,
            'details' => $details,
            'status' => $status
        ]);
    } else {
        echo $twig->render('unauthorized.html.twig');
    } 
?>