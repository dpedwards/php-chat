<?php 
// Only start the session if one hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include_once "php/config.php";

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['unique_id'])) {
  header("location: login.php");
  exit; // Ensure no further code is executed after a redirect
}
?>
<?php include_once "header.php"; ?>
<body>
<div class="wrapper">
  <section class="chat-area">
    <header>
      <?php 
        // Use prepared statements to avoid SQL injection
        if (isset($_GET['user_id'])) {
          $user_id = $_GET['user_id'];
          $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE unique_id = ?");
          mysqli_stmt_bind_param($stmt, 's', $user_id);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);

          if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
          } else {
            header("location: users.php");
            exit;
          }
        } else {
          // Redirect if user_id is not set in the query string
          header("location: users.php");
          exit;
        }
      ?>
      <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
      <img src="php/images/<?php echo $row['img']; ?>" alt="">
      <div class="details">
        <span><?php echo htmlspecialchars($row['fname']) . " " . htmlspecialchars($row['lname']); ?></span>
        <p><?php echo htmlspecialchars($row['status']); ?></p>
      </div>
    </header>
    <div class="chat-box">
    </div>
    <form action="#" class="typing-area">
      <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>" hidden>
      <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
      <button><i class="fab fa-telegram-plane"></i></button>
    </form>
  </section>
</div>

<script src="javascript/chat.js"></script>
</body>
</html>
