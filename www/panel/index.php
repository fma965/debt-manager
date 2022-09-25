<?php
    require_once 'init.php';
    if(!isset($_SESSION['userData'])) {
        echo $twig->render('index.html.twig');
    } else {
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

        echo $twig->render('index.html.twig', ['debts' => $debts, 'total' => $total]);
    }
?>