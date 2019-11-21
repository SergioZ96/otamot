CREATE TABLE IF NOT EXISTS `Message_Recipient`(
    `id` int auto_increment not null,
    `recipient_id` int not null,
    `recipient_group_id` int not null,
    `message_id` int not null,
    `is_read` int,
    PRIMARY KEY(`id`),
    FOREIGN KEY(`recipient_id`) REFERENCES `Users` (`id`),
    FOREIGN KEY(`recipient_group_id`) REFERENCES `User_Group` (`group_id`),
    FOREIGN KEY(`message_id`) REFERENCES `Message` (`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci;