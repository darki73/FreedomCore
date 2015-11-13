CREATE TABLE IF NOT EXISTS `api_android_armory` (`id` int(11) NOT NULL, `username` varchar(45) DEFAULT NULL, `password` varchar(100) DEFAULT NULL, `armory_key` varchar(120) DEFAULT NULL, `authorized` int(11) DEFAULT '0' ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `api_android_armory` ADD PRIMARY KEY (`id`);
ALTER TABLE `api_android_armory` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;