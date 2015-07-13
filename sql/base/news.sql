CREATE TABLE IF NOT EXISTS `news` (`id` int(11) NOT NULL, `title` varchar(45) DEFAULT NULL, `short_description` varchar(150) DEFAULT NULL, `full_description` varchar(1000) DEFAULT NULL, `posted_by` varchar(45) DEFAULT NULL, `post_date` datetime DEFAULT NULL, `post_miniature` varchar(60) DEFAULT NULL, `comments_key` varchar(32) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `news` ADD PRIMARY KEY (`id`);
ALTER TABLE `news` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

