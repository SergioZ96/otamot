CREATE TABLE IF NOT EXISTS `User_Group`(
    `id` int auto_increment not null,
    `user_id` int not null,
    `group_id` int not null,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `Users` (id),
    FOREIGN KEY (`group_id`) REFERENCES `Group` (id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;