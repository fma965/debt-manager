<?php
    require_once '../init.php';
    

    $totalmonthlyamount = 0;
    
    $first_day = mktime(0,0,0,date('n'),1,date('Y'));
	$last_day = mktime(23,59,59,(date('n') + 1 ), 31,date('Y'));

    $query = "SELECT * FROM debts ORDER by ID DESC;";
    $debtresult = mysqli_query($db, $query)or die(mysqli_error($db));
    while ($debt = mysqli_fetch_assoc($debtresult)) {
        $lastpaymentdate = 0;
        $paid = 0;
        $id = mysqli_real_escape_string($db,$debt['id']);	
        
        $query = "SELECT * FROM user_debts INNER JOIN users ON users.id=user_debts.user_id WHERE debt_id = $id;";
        
        $userresult = mysqli_query($db, $query)or die(mysqli_error($db));
        while ($user = mysqli_fetch_array($userresult)) {
            $debt['users'][] = ["name" => $user['name'], "id" => $user['id']];
        }
        $query = "SELECT * FROM transactions WHERE `reference` = '".$debt['reference']."' ORDER BY `created` DESC;";
        $transactionres = mysqli_query($db, $query)or die(mysqli_error($db));
        while ($transaction = mysqli_fetch_array($transactionres)) {
            if ($transaction['created'] > $first_day && $transaction['created'] < $last_day) $paid += $transaction['amount'];
            if ($lastpaymentdate == 0) $lastpaymentdate = $transaction['created'];
        }
        $monthlyamount = number_format($debt['payment'],2);
        $debt['payment'] = $paid;

        if($paid != $monthlyamount) {
            $debt['payment'] .= " / £" . $monthlyamount;
        }
        $debt['haspaid'] = HasPaid($paid,$monthlyamount);

        $totalmonthlyamount += $monthlyamount;

        $debts[] = $debt;
    }

    $total = ['paid' => $paid, 'expected' => $totalmonthlyamount];

    $query = "SELECT * FROM users ORDER by ID DESC;";
    $userresult = mysqli_query($db, $query)or die(mysqli_error($db));
    while ($user = mysqli_fetch_assoc($userresult)) {
        $users[] = $user;
    }

    echo $twig->render('admin/index.html.twig', ['debts' => $debts, 'users' => $users, 'total' => $total]);
?>