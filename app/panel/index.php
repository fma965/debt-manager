<?php
    require_once 'init.php';

    $details = [];
    $debts = [];
    $remaining = 0; 
    


    if(LoggedIn()) {
        if (isset($_POST['submit'])) {
            if($_POST['action'] == "update") {
                try {
                    $db->safeQuery('UPDATE users SET name= ? WHERE discord_id = ?',[$_POST['name'], $_SESSION['userData']['discord_id']]);
                    $status = DBSuccess("Profile");
                } catch (Exception $e) {
                    $status = DBError("Error updating Profile: " . $e->getMessage());
                }
            }
        }

        try {
            $details = $db->row('SELECT id, name FROM users WHERE discord_id = ? LIMIT 1', [$_SESSION['userData']['discord_id']]);
            if ($details) {
                foreach ($db->safeQuery('SELECT * FROM user_debts INNER JOIN debts ON debts.id=user_debts.debt_id WHERE user_id = ?;', [$details['id']]) as $debt) {
                    $remaining +=$debt['amount'];
                    $debts[] = $debt;
                }  
                $details['total_cost'] = $remaining;
            }
        } catch (Exception $e) {
            $status = DBError(template:"main.html.twig");
        } 
    }

    echo $twig->render('index.html.twig', [
        'userData' => isset($_SESSION['userData']) ? $_SESSION['userData'] : [],
        'debts' => $debts,
        'details' => $details,
        'status' => $status
    ]);
?>