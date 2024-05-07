<?php
// Copyright Davain Pablo Edwards core8@gmx.net. Licensed https://creativecommons.org/licenses/by-nc-sa/4.0/deed.en
// Only start the session if one hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    
    // Check if logout_id is set and not just relying on the SESSION variable
    $logout_id = isset($_GET['logout_id']) ? mysqli_real_escape_string($conn, $_GET['logout_id']) : null;
    
    if ($logout_id) {
        $status = "Offline now";
        
        // Use prepared statements to safely update the user status
        $stmt = mysqli_prepare($conn, "UPDATE users SET status = ? WHERE unique_id = ?");
        
        if (!$stmt) {
            // Handle error, could not prepare the statement
            exit('An error occurred.');
        }
        
        mysqli_stmt_bind_param($stmt, 'si', $status, $logout_id);
        $result = mysqli_stmt_execute($stmt);
        
        if ($result) {
            // Successfully updated the user status to offline
            session_unset();
            session_destroy();
            header("location: ../login.php");
        } else {
            // Handle error, could not execute the statement or update did not work
            exit('An error occurred.');
        }
    } else {
        header("location: ../users.php");
    }
} else {
    header("location: ../login.php");
}
?>
