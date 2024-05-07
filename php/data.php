<?php
// Copyright Davain Pablo Edwards core8@gmx.net. Licensed https://creativecommons.org/licenses/by-nc-sa/4.0/deed.en
while ($row = mysqli_fetch_assoc($query)) {
    // Retrieve the last message exchanged between the users
    $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = {$row['unique_id']}
            OR outgoing_msg_id = {$row['unique_id']}) AND (outgoing_msg_id = {$outgoing_id} 
            OR incoming_msg_id = {$outgoing_id}) ORDER BY msg_id DESC LIMIT 1";
    $query2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($query2);
    (mysqli_num_rows($query2) > 0) ? $result = $row2['msg'] : $result = "No message available";
    (strlen($result) > 28) ? $msg = substr($result, 0, 28) . '...' : $msg = $result;
    if (isset($row2['outgoing_msg_id'])) {
        ($outgoing_id == $row2['outgoing_msg_id']) ? $you = "You: " : $you = "";
    } else {
        $you = "";
    }
    ($row['status'] == "Offline now") ? $offline = "offline" : $offline = "";
    ($outgoing_id == $row['unique_id']) ? $hid_me = "hide" : $hid_me = "";

    // Sanitize the image path and remove any extra characters
    $imgFile = htmlspecialchars($row['img'], ENT_QUOTES, 'UTF-8');
    $imgFile = trim($imgFile, '&quot;'); // Remove the extra '&quot;'
    $imgPath = "php/images/" . $imgFile; // Added forward slash for proper path

    $output .= '<a href="chat.php?user_id=' . $row['unique_id'] . '">
                <div class="content">
                <img src="' . $imgPath . '" alt="">
                <div class="details">
                    <span>' . htmlspecialchars($row['fname'] . " " . $row['lname'], ENT_QUOTES, 'UTF-8') . '</span>
                    <p>' . $you . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</p>
                </div>
                </div>
                <div class="status-dot ' . $offline . '"><i class="fas fa-circle"></i></div>
            </a>';
}

        
?>
