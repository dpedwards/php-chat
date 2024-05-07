<?php 
// Copyright Davain Pablo Edwards core8@gmx.net. Licensed https://creativecommons.org/licenses/by-nc-sa/4.0/deed.en
// Only start the session if one hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";

    $outgoing_id = $_SESSION['unique_id'];
    // It's safe to use $_POST directly with prepared statements since they escape parameters by design.
    $incoming_id = $_POST['incoming_id'];
    $message = $_POST['message'];

    if (!empty($message)) {
        // Prepare the SQL statement to prevent SQL injection
        $stmt = mysqli_prepare($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES (?, ?, ?)");

        if ($stmt === false) {
            // Handle errors related to preparation failure, e.g., syntax errors in the SQL
            error_log('Statement preparation failed: ' . mysqli_error($conn));
            exit('An error occurred.');
        }

        // "iii" denotes the types of the variables being bound: i(integer), d(double), s(string), b(blob)
        mysqli_stmt_bind_param($stmt, 'iis', $incoming_id, $outgoing_id, $message);

        if (!mysqli_stmt_execute($stmt)) {
            // Log the error to a file or other error-logging mechanism
            error_log('Execution failed: ' . mysqli_stmt_error($stmt));
            exit('An error occurred.');
        }

        // Optionally, you can check if the insert was successful by checking affected rows
        if (mysqli_stmt_affected_rows($stmt) === 0) {
            error_log('No rows inserted. Something went wrong.');
            // Handle the case when no data is inserted
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }
} else {
    header("location: ../login.php");
}
?>
