CREATE TABLE IF NOT EXISTS `api_keys` (`id` int(11) NOT NULL,`username` varchar(45) DEFAULT NULL,`api_key` varchar(60) DEFAULT NULL,`active` int(11) DEFAULT '1') ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `api_keys` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username_UNIQUE` (`username`);
ALTER TABLE `api_keys` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;