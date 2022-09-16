<?php
    require_once 'autoload.php';
    if (!is_numeric($_GET['id']) && !is_numeric($_POST['id'])) {
        die('DEAD');
    }
    
    $status['status'] = isset($_GET['status']) ? $_GET['status'] : "";
    $status['message'] = isset($_GET['message']) ? $_GET['message'] : "";
   
    if (isset($_POST['submit'])) {
        $name = mysqli_real_escape_string($con,$_POST['name']);
        $id = mysqli_real_escape_string($con,$_POST['id']);
        $start_amount = mysqli_real_escape_string($con,$_POST['start_amount']);
        $amount = mysqli_real_escape_string($con,$_POST['amount']);
        $payment = mysqli_real_escape_string($con,$_POST['payment']);
        $notes = mysqli_real_escape_string($con,$_POST['details']);
        $reference = mysqli_real_escape_string($con,$_POST['reference']);
        $query = "UPDATE debts SET debt='$name', amount='$amount', start_amount='$start_amount', payment='$payment', details='$notes', reference='$reference' WHERE id=$id;";
        
        if (mysqli_query($con, $query)) {
            $status['status'] = "success";
            $status['message'] = "Debt updated successfully";
        } else {
            $status['status'] = "error";
            $status['message'] = "Error updating Debt: " . mysqli_error($con);
        }
    } 

    $first = mktime(0,0,0,date('n'),$startdate,date('Y'));
    $last = mktime(23,59,59,(date('n') + 1 ), $finishdate,date('Y'));

    $id = mysqli_real_escape_string($con,$_GET['id']);

    $query = "SELECT * FROM debts WHERE id = $id LIMIT 1;";
    $result = mysqli_query($con, $query);
    $numResults = mysqli_num_rows($result);
    if ($numResults > 0) {
        while ($debt = mysqli_fetch_assoc($result)) {
            
            $query2 = "SELECT * FROM `transactions` WHERE `reference` = '".$debt['reference']."' ORDER BY `created` DESC;";
            $sqltran2 = mysqli_query($con, $query2);
            $transactions = mysqli_fetch_all($sqltran2, MYSQLI_ASSOC);
            foreach ($transactions as $transaction ) {
                if ($transaction['created'] > $first && $transaction['created'] < $last) $totalpaidthismonth += $transaction['amount'];
                $transaction['created'] = formatDate($transaction['created']);
                $transaction['amount'] = number_format($transaction['amount'], 2);
                $transactions[] = $transaction;
            }
            $details['id'] = $debt['id'];
            $details['debt'] = $debt['debt'];
            $details['reference'] = $debt['reference'];
            $details['start_amount'] = number_format($debt['start_amount'], 2);
            $details['amount'] = number_format($debt['amount'], 2);
            $details['payment'] = number_format($debt['payment'], 2);
            $details['details'] = nl2br($debt['details']);
        }
        echo $twig->render('debt.html.twig', ['transactions' => $transactions, 'details' => $details, 'status' => $status]);
    } else {
        $status['status'] = "error";
        $status['message'] = "Invalid ID specified";
        echo $twig->render('invalid.html.twig', ['status' => $status]);
    }
    
?>