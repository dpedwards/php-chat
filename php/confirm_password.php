<?php
session_start();
include_once "config.php";

// Retrieve form data
$fname = $_POST['fname'] ?? '';
$lname = $_POST['lname'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Server-side validation for passwords
if ($password !== $confirm_password) {
    echo "Passwords do not match!";
    exit;
}

// If no errors occur
echo "success";
