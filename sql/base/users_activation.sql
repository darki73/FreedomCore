CREATE TABLE IF NOT EXISTS `users_activation` (`id` int(11) NOT NULL, `username` varchar(100) DEFAULT NULL, `site_password` varchar(100) DEFAULT NULL, `game_password` varchar(100) DEFAULT NULL, `email` varchar(100) DEFAULT NULL, `registration_date` datetime DEFAULT NULL, `activation_code` varchar(100) DEFAULT NULL, `activated` int(11) DEFAULT '0') ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `users_activation` ADD PRIMARY KEY (`id`);
ALTER TABLE `users_activation` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;