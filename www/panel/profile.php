<?php
    require_once 'init.php';
    extract($_SESSION['userData']);

    if (isset($_POST['submit'])) {  
        $query = "UPDATE contacts SET name='$name', notes='$notes' WHERE id=$id;";
        if (mysqli_query($con, $query)) {
            $status['status'] = "success";
            $status['message'] = "Profile updated successfully";
        } else {
            $status['status'] = "error";
            $status['message'] = "Error updating profile: " . mysqli_error($con);
        }
    }

    $debts = [];
    $alldebts = [];
    $remaining = 0; 

    $query = "SELECT * FROM contacts WHERE discord_id = $discord_id LIMIT 1;";
    $result = mysqli_query($con, $query);
    $numResults = mysqli_num_rows($result);
    if ($numResults > 0) {
        while ($contact = mysqli_fetch_assoc($result)) {
            $query = "SELECT * FROM `debts`;";
            $sqltran = mysqli_query($con, $query);
            if ($result = mysqli_query($con, $query)) {
                $alldebts = mysqli_fetch_all($sqltran, MYSQLI_ASSOC);
            }

            $query = "SELECT * FROM contact_debts INNER JOIN debts ON debts.id=contact_debts.debt_id WHERE contact_id = ".$contact['id'].";";
            $result = mysqli_query($con, $query);
            if (mysqli_num_rows($result) !== 0) {
                $debts = mysqli_fetch_all($result, MYSQLI_ASSOC);
                foreach ($debts as $debt ) {				
                    $remaining +=$debt['amount'];
                }
            }
            $details = $contact;
            $details['total_cost'] = $remaining;
        }
    } else {
        $status['status'] = "error";
        $status['message'] = "Invalid ID specified";
        echo $twig->render('invalid.html.twig');
    }


    echo $twig->render('profile.html.twig', ['userData' => $_SESSION['userData'], 'alldebts' => $alldebts, 'debts' => $debts, 'details' => $details, 'status' => $status]);
?>
