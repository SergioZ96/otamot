CREATE TABLE IF NOT EXISTS `Message`(
    `id` int auto_increment not null,
    `creator_id` int not null,
    `message_body` varchar(4000),
    `create_data` timestamp,
    `parent_message_id` int not null,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`creator_id`) REFERENCES `Users` (`id`),
    FOREIGN KEY (`parent_message_id`) REFERENCES `Message` (`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci;