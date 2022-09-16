<?php
    require_once 'autoload.php';

    $totalmonthlyamount = 0;
    
    $first_day = mktime(0,0,0,date('n'),$startdate,date('Y'));
	$last_day = mktime(23,59,59,(date('n') + 1 ), $finishdate,date('Y'));

    $query = "SELECT * FROM debts ORDER by ID DESC;";
    $debtresult = mysqli_query($con, $query)or die(mysqli_error($con));
    while ($debt = mysqli_fetch_assoc($debtresult)) {
        $lastpaymentdate = 0;
        $paid = 0;
        $id = mysqli_real_escape_string($con,$debt['id']);	
        
        $query = "SELECT * FROM contact_debts INNER JOIN contacts ON contacts.id=contact_debts.contact_id WHERE debt_id = $id;";
        $contactresult = mysqli_query($con, $query)or die(mysqli_error($con));
        while ($contact = mysqli_fetch_array($contactresult)) {
            $debt['contacts'][] = ["name" => $contact['name'], "id" => $contact['id']];
        }
        $query = "SELECT * FROM transactions WHERE `reference` = '".$debt['reference']."' ORDER BY `created` DESC;";
        $transactionres = mysqli_query($con, $query)or die(mysqli_error($con));
        while ($transaction = mysqli_fetch_array($transactionres)) {
            if ($transaction['created'] > $first_day && $transaction['created'] < $last_day) $paid += $transaction['amount'];
            if ($lastpaymentdate == 0) $lastpaymentdate = $transaction['created'];
        }
        $paid = number_format($paid,2);
        $monthlyamount = number_format($debt['payment'],2);
        $debt['start_amount'] = number_format($debt['start_amount'],2);
        $debt['amount'] = number_format($debt['amount'],2);
        $debt['payment'] = number_format($debt['payment'],2);
        $debt['lastpaymentdate'] = formatDate($lastpaymentdate);
        $debt['payment'] = $paid;

        if($paid != $monthlyamount) {
            $debt['payment'] .= " / £" . $monthlyamount;
        }
        $debt['haspaid'] = HasPaid($paid,$monthlyamount);

        $totalmonthlyamount += $monthlyamount;

        $debts[] = $debt;
    }

    $total = ['paid' => number_format($paid,2), 'expected' => number_format($totalmonthlyamount,2)];

    echo $twig->render('index.html.twig', ['debts' => $debts, 'total' => $total, 'status' => $status]);
?>