<?php
    require_once '../init.php';
    
    $totalmonthlyamount = 0;
    $first_day = mktime(0,0,0,date('n'),1,date('Y'));
	$last_day = mktime(23,59,59,(date('n') + 1 ), 31,date('Y'));
    $lastpaymentdate = 0;
    $paid = 0;
    $users = [];

    try {
        // Debt Table
        foreach ($db->safeQuery('SELECT * FROM debts ORDER by ID DESC') as $debt) {
            foreach ($db->safeQuery('SELECT * FROM user_debts INNER JOIN users ON users.id=user_debts.user_id WHERE debt_id = ?',[$debt['id']]) as $user) {
                $debt['users'][] = ["name" => $user['name'], "id" => $user['id']];
            }

            foreach ($db->safeQuery('SELECT * FROM transactions WHERE `reference` = ? ORDER BY `created` DESC',[$debt['reference']]) as $transaction) {
                if ($transaction['created'] > $first_day && $transaction['created'] < $last_day) $paid += $transaction['amount'];
                if ($lastpaymentdate == 0) $lastpaymentdate = $transaction['created'];
            }

            $debt['haspaid'] = HasPaid($paid,$debt['payment']);
            $debt['actual_payment'] = $paid;
            $debts[] = $debt;

            $totalmonthlyamount += $debt['payment'];
        }

        // User Table
        foreach ($db->safeQuery('SELECT * FROM users ORDER by ID DESC') as $user) {
            $users[] = $user;
        }
    } catch (Exception $e) {
        $status = MysqlError(template:"wrapper.html.twig");
    } 

    echo $twig->render('admin/index.html.twig', ['debts' => $debts, 'users' => $users, 'total' => ['paid' => $paid, 'expected' => $totalmonthlyamount]]);
?>