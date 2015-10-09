CREATE TABLE IF NOT EXISTS `forum_topics` (`id` int(11) NOT NULL, `forum_id` int(11) DEFAULT NULL, `posted_by` varchar(45) DEFAULT NULL, `topic` varchar(200) DEFAULT NULL, `views` int(11) DEFAULT '0', `post_time` int(11) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `forum_topics` ADD PRIMARY KEY (`id`);
ALTER TABLE `forum_topics` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;