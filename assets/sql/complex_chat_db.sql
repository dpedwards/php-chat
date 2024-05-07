-- Copyright Davain Pablo Edwards core8@gmx.net. Licensed https://creativecommons.org/licenses/by-nc-sa/4.0/deed.en 
-- Create `users` table
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` int(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create `conversations` table
CREATE TABLE `conversations` (
  `conv_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`conv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create `messages` table
CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `incoming_msg_id` int(11) NOT NULL,
  `outgoing_msg_id` int(11) NOT NULL,
  `msg` varchar(1000) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('sent', 'delivered', 'read') NOT NULL DEFAULT 'sent',
  `conv_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`msg_id`),
  FOREIGN KEY (`incoming_msg_id`) REFERENCES `users`(`user_id`),
  FOREIGN KEY (`outgoing_msg_id`) REFERENCES `users`(`user_id`),
  FOREIGN KEY (`conv_id`) REFERENCES `conversations`(`conv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create `contacts` table
CREATE TABLE `contacts` (
  `contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `contact_user_id` int(11) NOT NULL,
  `status` enum('pending', 'accepted', 'blocked') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`contact_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`),
  FOREIGN KEY (`contact_user_id`) REFERENCES `users`(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create `conversation_participants` table
CREATE TABLE `conversation_participants` (
  `conv_participant_id` int(11) NOT NULL AUTO_INCREMENT,
  `conv_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`conv_participant_id`),
  FOREIGN KEY (`conv_id`) REFERENCES `conversations`(`conv_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create `user_profiles` table
CREATE TABLE `user_profiles` (
  `profile_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `img` varchar(255) NOT NULL, -- Added column for profile image
  PRIMARY KEY (`profile_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create `user_settings` table
CREATE TABLE `user_settings` (
  `settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `theme` varchar(255) DEFAULT 'light',
  `notifications` tinyint(1) DEFAULT 1,
  `privacy` varchar(255) DEFAULT 'friends_only',
  PRIMARY KEY (`settings_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create `attachments` table
CREATE TABLE `attachments` (
  `attachment_id` int(11) NOT NULL AUTO_INCREMENT,
  `msg_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  PRIMARY KEY (`attachment_id`),
  FOREIGN KEY (`msg_id`) REFERENCES `messages`(`msg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
