<?php
// Enable strict reporting
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Database credentials
$hostname = "localhost";
$username = "root";
$password = "pa55word";
$dbname = "simple_chat_db";

try {
    // Use mysqli class for a more object-oriented approach
    $conn = new mysqli($hostname, $username, $password, $dbname);

    // Check for connection errors
    if ($conn->connect_error) {
        throw new Exception("Database connection error: " . $conn->connect_error);
    }
} catch (Exception $e) {
    // Log the error and show a user-friendly message
    error_log($e->getMessage());
    echo "An error occurred while connecting to the database. Please check the database connection in php/config.php and try again.";
    exit; // Stop further execution of the script
}
// Copyright Davain Pablo Edwards core8@gmx.net. Licensed https://creativecommons.org/licenses/by-nc-sa/4.0/deed.en
?>
