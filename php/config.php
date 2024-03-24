<?php
  // Enable strict reporting
  declare(strict_types=1);

  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  $hostname = "localhost";
  $username = "root";
  $password = "pa55word";
  $dbname = "chat_db";

  // Use try-catch block for exception handling
  try {
    $conn = mysqli_connect($hostname, $username, $password, $dbname);

    if (!$conn) {
      // Instead of echoing, consider using a more robust error handling mechanism
      throw new Exception("Database connection error: " . mysqli_connect_error());
    }
  } catch (Exception $e) {
    // Handle the error, such as logging it and displaying a user-friendly message
    error_log($e->getMessage());
    echo "An error occurred while connecting to the database. Please try again later.";
  }
?>
