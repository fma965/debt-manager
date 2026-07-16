<?php
    require_once '../init.php';
    
    $totalmonthlyamount = 0;
    $totalpaid = 0; // <-- 1. Add this to track the grand total for the footer
    $first_day = mktime(0,0,0,date('n'),1,date('Y'));
    $last_day = mktime(23,59,59,(date('n') + 1 ), 31,date('Y'));
    $lastpaymentdate = 0;
    $paid = 0;
    $users = [];
    $debts = []; // Good practice to initialize this too!

    try {
        // Debt Table
        foreach ($db->safeQuery('SELECT * FROM debts ORDER by ID ASC') as $debt) {
            // Reset these for each individual debt
            $paid = 0;
            $lastpaymentdate = 0;

            foreach ($db->safeQuery('SELECT * FROM user_debts INNER JOIN users ON users.id=user_debts.user_id WHERE debt_id = ?',[$debt['id']]) as $user) {
                $debt['users'][] = ["name" => $user['name'], "id" => $user['id']];
            }

            foreach ($db->safeQuery('SELECT * FROM transactions WHERE reference = ? ORDER BY created DESC',[$debt['reference']]) as $transaction) {
                if ($transaction['created'] > $first_day && $transaction['created'] < $last_day) {
                    $paid += $transaction['amount'];
                }
                if ($lastpaymentdate == 0) {
                    $lastpaymentdate = $transaction['created'];
                }
            }

            $debt['haspaid'] = HasPaid($paid,$debt['payment']);
            $debt['actual_payment'] = $paid;
            $debt['lastpaymentdate'] = $lastpaymentdate; 

            $debts[] = $debt;
            
            $totalmonthlyamount += $debt['payment'];
            $totalpaid += $paid; // <-- 2. Add this debt's paid amount to the grand total
        }

        // User Table
        foreach ($db->safeQuery('SELECT * FROM users ORDER by ID ASC') as $user) {
            $users[] = $user;
        }
    } catch (Exception $e) {
        $status = DBError(template:"main.html.twig");
    } 

    // 3. Pass $totalpaid here instead of $paid
    echo $twig->render('admin/index.html.twig', [
        'debts' => $debts, 
        'users' => $users, 
        'total' => [
            'paid' => $totalpaid, 
            'expected' => $totalmonthlyamount
        ]
    ]);
?>