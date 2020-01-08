CREATE TABLE IF NOT EXISTS `Users` (
    `id` int auto_increment not null,
    `username` varchar(40) not null unique,
    `password` varchar(200) not null unique,
    `first` varchar(20) not null,
    `last` varchar(20) not null,
    `email` varchar(40) not null unique,
    PRIMARY KEY (`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci;