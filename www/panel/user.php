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
    $details = [];
    $id = mysqli_real_escape_string($con,$_GET['id']);
    $discord_id = $_SESSION['userData']['discord_id'];
    $remaining = 0; 

    $query = "SELECT * FROM debts INNER JOIN user_debts ON debts.id = user_debts.debt_id INNER JOIN users ON users.id = user_debts.user_id WHERE users.id = $id;";
    $result = mysqli_query($con, $query);
    $numResults = mysqli_num_rows($result);
    if ($numResults > 0) {
        while ($debt = mysqli_fetch_assoc($result)) {
            if($debt['discord_id'] == $discord_id || $_SESSION['admin']) {						
                $remaining +=$debt['amount'];
                $details = $debt;
                $details['total_cost'] = $remaining;
            }
            $debts[] = $debt;
        }
    }
    echo $twig->render('user.html.twig', ['alldebts' => $alldebts, 'debts' => $debts, 'details' => $details, 'status' => $status]);
    
?>