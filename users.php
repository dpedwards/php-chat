<?php 
// Copyright Davain Pablo Edwards core8@gmx.net. Licensed https://creativecommons.org/licenses/by-nc-sa/4.0/deed.en
session_start();
include_once "php/config.php";
if (!isset($_SESSION['unique_id'])) {
    header("location: login.php");
    exit;
}
?>
<?php include_once "header.php"; ?>
<body>
    <div class="wrapper">
        <section class="users">
            <header>
                <div class="content">
                    <?php
                    // Get the logged-in user's details
                    $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
                    if (mysqli_num_rows($sql) > 0) {
                        $row = mysqli_fetch_assoc($sql);
                    }
                    $imgPath = "php/images/" . $row['img'];
                    ?>
                    <img src="<?php echo $imgPath; ?>" alt="">
                    <div class="details">
                        <span><?php echo $row['fname'] . " " . $row['lname']; ?></span>
                        <p><?php echo $row['status']; ?></p>
                    </div>
                </div>
                <a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>" class="logout">Logout</a>
            </header>
            <div class="search">
              <form method="GET" action="">
                  <input type="text" name="search" placeholder="Enter name to search..." value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                  <button type="submit"><i class="fas fa-search"></i></button>
              </form>
            </div>
            <div class="users-list">
                <?php
                $currentUserId = $_SESSION['unique_id'];
                
                // Search users by name if search input is provided
                $searchInput = $_GET['search'] ?? '';
                if (!empty($searchInput)) {
                    $searchInput = '%' . mysqli_real_escape_string($conn, $searchInput) . '%';
                    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE unique_id != ? AND (fname LIKE ? OR lname LIKE ?)");
                    mysqli_stmt_bind_param($stmt, "iss", $currentUserId, $searchInput, $searchInput);
                    mysqli_stmt_execute($stmt);
                    $sql = mysqli_stmt_get_result($stmt);
                } else {
                    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE unique_id != ?");
                    mysqli_stmt_bind_param($stmt, "i", $currentUserId);
                    mysqli_stmt_execute($stmt);
                    $sql = mysqli_stmt_get_result($stmt);
                }

                if (mysqli_num_rows($sql) == 0) {
                    echo '<p>No user found.</p>';
                }

                while ($userRow = mysqli_fetch_assoc($sql)) {
                    $userImgPath = "php/images/" . $userRow['img'];
                    if (!file_exists($userImgPath)) {
                        $userImgPath = "php/images/default.png"; // Assign a default image
                    }
                    echo '<a href="chat.php?user_id=' . $userRow['unique_id'] . '">
                            <div class="content">
                                <img src="' . $userImgPath . '" alt="">
                                <div class="details">
                                    <span>' . htmlspecialchars($userRow['fname'] . " " . $userRow['lname'], ENT_QUOTES, 'UTF-8') . '</span>
                                    <p>' . htmlspecialchars($userRow['status'], ENT_QUOTES, 'UTF-8') . '</p>
                                </div>
                            </div>
                            <div class="status-dot ' . ($userRow['status'] === 'Offline now' ? 'offline' : '') . '"><i class="fas fa-circle"></i></div>
                          </a>';
                }
                ?>
            </div>
        </section>
    </div>

    <script src="javascript/users.js"></script>

</body>
</html>
