<?php
// Copyright Davain Pablo Edwards core8@gmx.net. Licensed https://creativecommons.org/licenses/by-nc-sa/4.0/deed.en
session_start();
include_once "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Use prepared statements to prevent SQL injection
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $enc_pass = $row['password'];

            // Verify the password using password_verify
            if (password_verify($password, $enc_pass)) {
                $status = "Active now";
                $stmt = $conn->prepare("UPDATE users SET status = ? WHERE unique_id = ?");
                $stmt->bind_param("si", $status, $row['unique_id']);
                $update_success = $stmt->execute();

                if ($update_success) {
                    $_SESSION['unique_id'] = $row['unique_id'];
                    echo "success";
                } else {
                    echo "Something went wrong. Please try again!";
                }
            } else {
                echo "Email or Password is Incorrect!";
            }
        } else {
            echo "$email - This email does not exist!";
        }
    } else {
        echo "All input fields are required!";
    }
}
?>
