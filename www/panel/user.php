<?php 
    require_once 'init.php';
    if (!isset($_REQUEST['id']) || isset($_REQUEST['id']) && !is_numeric($_REQUEST['id'])) {
        header("Location: /");
    }

    if (isset($_POST['submit'])) {
        $name = mysqli_real_escape_string($con,$_POST['name']);
        $id = mysqli_real_escape_string($con,$_POST['id']);
        $notes = mysqli_real_escape_string($con,$_POST['notes']);
        $query = "DELETE FROM user_debts WHERE user_id=$id";
        if (!mysqli_query($con, $query)) echo '<div id="status" class="alert alert-danger" role="alert">Error updating debts: ' . mysqli_error($con);
        
        if(isset($_POST['debts'])) {
            $debts = $_POST['debts'];
            foreach ($debts as $debt) {	
                if($debt != 0) {
                    $debt = mysqli_real_escape_string($con,$debt);
                    $query = "INSERT INTO user_debts (user_id, debt_id) VALUES ('$id', '$debt');";
                    if (!mysqli_query($con, $query)) echo '<div id="status" class="alert alert-danger" role="alert">Error updating debts: ' . mysqli_error($con);
                }
            }
        }
    
        $query = "UPDATE users SET name='$name', notes='$notes' WHERE id=$id;";
        if (mysqli_query($con, $query)) {
            $status['status'] = "success";
            $status['message'] = "User updated successfully";
        } else {
            $status['status'] = "error";
            $status['message'] = "Error updating User: " . mysqli_error($con);
        }
    }
    $debts = [];
    $alldebts = [];
    $id = mysqli_real_escape_string($con,$_GET['id']);
    $remaining = 0; 

    $query = "SELECT * FROM users WHERE id = $id LIMIT 1;";
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
        echo $twig->render('user.html.twig', ['alldebts' => $alldebts, 'debts' => $debts, 'details' => $details, 'status' => $status]);
    } else {
        $status['status'] = "error";
        $status['message'] = "Invalid ID specified";
        echo $twig->render('invalid.html.twig');
    }
?>