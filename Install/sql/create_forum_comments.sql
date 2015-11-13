CREATE TABLE IF NOT EXISTS `forum_comments` (`id` int(11) NOT NULL, `forum_id` int(11) DEFAULT NULL, `topic_id` int(11) DEFAULT NULL, `post_id` int(11) DEFAULT NULL, `posted_by` varchar(45) DEFAULT NULL, `post_time` int(11) DEFAULT NULL, `post_message` varchar(2000) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `forum_comments` ADD PRIMARY KEY (`id`);
ALTER TABLE `forum_comments` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
