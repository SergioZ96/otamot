CREATE TABLE IF NOT EXISTS `Password_Reset`(
    `id` int auto_increment not null,
    `email` varchar(255),
    `selector` char(16),
    `token` char(64),
    `expires` bigint(20),
    PRIMARY KEY (`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci;