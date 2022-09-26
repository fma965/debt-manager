<?php

    require_once 'init.php';
    if(isset($_SESSION['userData'])) {
        extract($_SESSION['userData']);

        if (isset($_POST['submit'])) {  
            $name = mysqli_real_escape_string($con,$_POST['name']);
            $query = "UPDATE users SET name='$name' WHERE discord_id = $discord_id;";
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

        $query = "SELECT * FROM users WHERE discord_id = $discord_id LIMIT 1;";
        $result = mysqli_query($con, $query);
        $numResults = mysqli_num_rows($result);
        if ($numResults > 0) {
            while ($user = mysqli_fetch_assoc($result)) {
                $query = "SELECT * FROM `debts`;";
                $sqltran = mysqli_query($con, $query);
                if ($result = mysqli_query($con, $query)) {
                    $alldebts = mysqli_fetch_all($sqltran, MYSQLI_ASSOC);
                }

                $query = "SELECT * FROM user_debts INNER JOIN debts ON debts.id=user_debts.debt_id WHERE user_id = ".$user['id'].";";
                $result = mysqli_query($con, $query);
                if (mysqli_num_rows($result) !== 0) {
                    $debts = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    foreach ($debts as $debt ) {				
                        $remaining +=$debt['amount'];
                    }
                }
                $details = $user;
                $details['total_cost'] = $remaining;
            }
        } else {
            $status['status'] = "error";
            $status['message'] = "No matching user found";
            echo $twig->render('wrapper.html.twig', ['status' => $status]);
        }
        echo $twig->render('index.html.twig', ['userData' => $_SESSION['userData'], 'alldebts' => $alldebts, 'debts' => $debts, 'details' => $details, 'status' => $status]);
    } else {
        echo $twig->render('index.html.twig');
    }


?>
