<?php
    require_once 'init.php';

    if (!isset($_REQUEST['id']) || isset($_REQUEST['id']) && !is_numeric($_REQUEST['id'])) {
        $_SESSION['admin'] ? header("Location: /admin/new.php?type=debt") : header("Location: /");
        exit; 
    }

    $authorized = $_SESSION['admin'];
    $alltransactions = [];
    $details = [];
    $contacts = [];
    $allcontacts = [];
    $totalpaidthismonth = 0;
    $first = mktime(0,0,0, date('n'), 1, date('Y'));
    $last = mktime(23,59,59, (date('n') + 1 ), 31, date('Y'));

    try {
        if($_SESSION['admin']) {
            $details = $db->row('SELECT * FROM debts WHERE id = ?', [$_GET['id']]);
        } else {
            foreach ($db->safeQuery('SELECT users.discord_id, debts.* FROM debts INNER JOIN user_debts ON debts.id = user_debts.debt_id INNER JOIN users ON users.id = user_debts.user_id WHERE debts.id = ?', [$_GET['id']]) as $details) {
                if($details['discord_id'] == $_SESSION['userData']['discord_id']) $authorized = true;
            }
        }

        if(isset($details['reference'])) {
            foreach ($db->safeQuery('SELECT * FROM user_debts INNER JOIN users ON users.id=user_debts.user_id WHERE debt_id = ?',[$details['id']]) as $contact) {
                $contacts[] = $contact;
            }

            foreach ($db->safeQuery('SELECT * FROM users') as $contact) {
                $allcontacts[] = $contact;
            }

            foreach ($db->safeQuery('SELECT * FROM `transactions` WHERE `reference` = ? ORDER BY `created` DESC;', [$details['reference']]) as $transaction) {
                if ($transaction['created'] > $first && $transaction['created'] < $last) $totalpaidthismonth += $transaction['amount'];
                $alltransactions[] = $transaction;
            }
        }

    } catch (Exception $e) {
        $status = MysqlError(template:"main.html.twig");
    } 

    if($authorized) {
        echo $twig->render('debt.html.twig', [
            'allcontacts' => $allcontacts,
            'contacts' => $contacts,
            'transactions' => $alltransactions,
            'details' => $details,
            'status' => $status
        ]);
    } else {
        echo $twig->render('unauthorized.html.twig');
    } 
?>