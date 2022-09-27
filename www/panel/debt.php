<?php
    require_once 'init.php';
    if (!isset($_REQUEST['id']) || isset($_REQUEST['id']) && !is_numeric($_REQUEST['id'])) {
        header("Location: /");
    }
   
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

    $first = mktime(0,0,0,date('n'),1,date('Y'));
    $last = mktime(23,59,59,(date('n') + 1 ), 31,date('Y'));
    
    $alltransactions = [];
    $details = [];

    $id = mysqli_real_escape_string($con,$_GET['id']);
    $discord_id = $_SESSION['userData']['discord_id'];
    $query = "SELECT debts.*, users.discord_id FROM debts INNER JOIN user_debts ON debts.id = user_debts.debt_id INNER JOIN users ON users.id = user_debts.user_id WHERE debts.id = $id";
    $result = mysqli_query($con, $query);
    $numResults = mysqli_num_rows($result);
    if ($numResults > 0) {
        while ($debt = mysqli_fetch_assoc($result)) {
            if($debt['discord_id'] == $discord_id || $_SESSION['admin']) {
                $totalpaidthismonth = 0;
                $query2 = "SELECT * FROM `transactions` WHERE `reference` = '".$debt['reference']."' ORDER BY `created` DESC;";
                $sqltran2 = mysqli_query($con, $query2);
                $transactions = mysqli_fetch_all($sqltran2, MYSQLI_ASSOC);
                foreach ($transactions as $transaction ) {
                    if ($transaction['created'] > $first && $transaction['created'] < $last) $totalpaidthismonth += $transaction['amount'];
                    $alltransactions[] = $transaction;
                }
                $details = $debt;
            }
        }
        
    }
    echo $twig->render('debt.html.twig', ['transactions' => $alltransactions, 'details' => $details, 'status' => $status]);#
?>