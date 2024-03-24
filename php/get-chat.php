<?php
// Only start the session if one hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Check if user is logged in with a unique session ID
if (isset($_SESSION['unique_id'])) {
    // Include database configuration
    include_once "config.php";

    $outgoing_id = $_SESSION['unique_id'];
    // Use prepared statements to prevent SQL Injection
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $output = "";

    // Updated SQL query to use placeholders for variables
    $sql = "SELECT * FROM messages LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
            WHERE (outgoing_msg_id = ? AND incoming_msg_id = ?)
            OR (outgoing_msg_id = ? AND incoming_msg_id = ?) ORDER BY msg_id";

    // Prepare the SQL statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'iiii', $outgoing_id, $incoming_id, $incoming_id, $outgoing_id);

    // Execute the prepared statement
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['outgoing_msg_id'] === $outgoing_id) {
                $output .= '<div class="chat outgoing">
                            <div class="details">
                                <p>' . htmlentities($row['msg'], ENT_QUOTES, 'UTF-8') . '</p>
                            </div>
                            </div>';
            } else {
                $output .= '<div class="chat incoming">
                            <img src="php/images/' . htmlentities($row['img'], ENT_QUOTES, 'UTF-8') . '" alt="">
                            <div class="details">
                                <p>' . htmlentities($row['msg'], ENT_QUOTES, 'UTF-8') . '</p>
                            </div>
                            </div>';
            }
        }
    } else {
        $output .= '<div class="text">No messages are available. Once you send a message, they will appear here.</div>';
    }

    // Output the chat HTML
    echo $output;
} else {
    // Redirect to login page if not logged in
    header("location: ../login.php");
}
?>