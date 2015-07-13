CREATE TABLE IF NOT EXISTS `prices` (`id` int(11) NOT NULL, `type` int(11) DEFAULT NULL COMMENT '1 - Service\n2 - Gear\n3 - Mount\n4 - Wallet Top UP', `short_code` varchar(45) DEFAULT NULL, `price` float DEFAULT NULL COMMENT 'ONLY IN USD!') ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
INSERT INTO `prices` (`id`, `type`, `short_code`, `price`) VALUES (1, 1, 'pct', 20), (2, 1, 'pfc', 25), (3, 1, 'prc', 20), (4, 1, 'pcc', 15), (5, 1, 'pnc', 8);
ALTER TABLE `prices` ADD PRIMARY KEY (`id`);
ALTER TABLE `prices` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;

