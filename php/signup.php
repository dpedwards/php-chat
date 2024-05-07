<?php
// Copyright Davain Pablo Edwards core8@gmx.net. Licensed https://creativecommons.org/licenses/by-nc-sa/4.0/deed.en
// Only start the session if one hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once "config.php";

// Use prepared statements to avoid SQL injection
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$password = $_POST['password'];

if (!empty($fname) && !empty($lname) && !empty($email) && !empty($password)) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if email exists using prepared statement
        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "$email - This email already exists!";
        } else {
            if (isset($_FILES['image'])) {
                $img_name = $_FILES['image']['name'];
                $img_type = $_FILES['image']['type'];
                $tmp_name = $_FILES['image']['tmp_name'];
                
                $img_explode = explode('.', $img_name);
                $img_ext = strtolower(end($img_explode));

                $extensions = ["jpeg", "png", "jpg", "gif"];
                if (in_array($img_ext, $extensions)) {
                    $types = ["image/jpeg", "image/jpg", "image/png", "image/gif"];
                    if (in_array($img_type, $types)) {
                        $time = time();
                        $new_img_name = $time . $img_name;
                        if (move_uploaded_file($tmp_name, "images/" . $new_img_name)) {
                            $ran_id = rand(time(), 100000000);
                            $status = "Active now";
                            // Use password_hash for secure password storage
                            $encrypt_pass = password_hash($password, PASSWORD_DEFAULT);

                            // Insert user with a prepared statement
                            $insert_stmt = mysqli_prepare($conn, "INSERT INTO users (unique_id, fname, lname, email, password, img, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                            mysqli_stmt_bind_param($insert_stmt, 'issssss', $ran_id, $fname, $lname, $email, $encrypt_pass, $new_img_name, $status);
                            $insert_result = mysqli_stmt_execute($insert_stmt);

                            if ($insert_result) {
                                $_SESSION['unique_id'] = $ran_id; // Assuming you want to use the newly created unique_id
                                echo "success";
                            } else {
                                echo "Something went wrong. Please try again!";
                            }
                        }
                    } else {
                        echo "Please upload an image file - jpeg, png, jpg";
                    }
                } else {
                    echo "Please upload an image file - jpeg, png, jpg";
                }
            }
        }
    } else {
        echo "$email is not a valid email!";
    }
} else {
    echo "All input fields are required!";
}
?>
