CREATE TABLE IF NOT EXISTS `item_comments` (`id` int(11) NOT NULL, `item_id` int(11) DEFAULT NULL, `discussion_key` varchar(45) DEFAULT NULL, `comment_by` varchar(45) DEFAULT NULL, `comment_text` varchar(1000) DEFAULT NULL, `comment_date` datetime DEFAULT NULL, `reply_to` int(11) DEFAULT NULL, `language_code` varchar(5) DEFAULT NULL, `votes_up` int(11) DEFAULT NULL, `votes_down` int(11) DEFAULT NULL, `replied_to` int(11) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `item_comments` ADD PRIMARY KEY (`id`);
ALTER TABLE `item_comments` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

