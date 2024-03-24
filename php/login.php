<?php 
// Only start the session if one hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Directly using $_POST['email'] and $_POST['password'] with prepared statements is safe
$email = $_POST['email'];
$password = $_POST['password'];

if (!empty($email) && !empty($password)) {
    // Use a prepared statement to select the user
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $user_pass = md5($password); // Consider using password_hash() and password_verify() for new projects
        $enc_pass = $row['password'];
        
        // Use password_verify() if you switch to using password_hash() for storing passwords
        if ($user_pass === $enc_pass) {
            $status = "Active now";
            // Again, use a prepared statement for updates
            $stmt2 = mysqli_prepare($conn, "UPDATE users SET status = ? WHERE unique_id = ?");
            mysqli_stmt_bind_param($stmt2, 'si', $status, $row['unique_id']);
            $result2 = mysqli_stmt_execute($stmt2);
            
            if ($result2) {
                $_SESSION['unique_id'] = $row['unique_id'];
                echo "success";
            } else {
                echo "Something went wrong. Please try again!";
            }
        } else {
            echo "Email or Password is Incorrect!";
        }
    } else {
        echo "This email does not exist!";
    }
} else {
    echo "All input fields are required!";
}
?>
