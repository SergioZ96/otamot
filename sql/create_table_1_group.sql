CREATE TABLE IF NOT EXISTS `Group`(
    `id` int auto_increment not null,
    `name` varchar(20),
    `create_date` datetime,
    PRIMARY KEY (`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci;