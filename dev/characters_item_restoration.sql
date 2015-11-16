CREATE TABLE `item_restoration` (
  `guid` int(10) NOT NULL COMMENT 'Transaction ID',
  `character_guid` int(10) NOT NULL COMMENT 'Character GUID',
  `item_id` int(11) NOT NULL COMMENT 'Item ID',
  `item_price` float NOT NULL COMMENT 'Price of Item',
  `sell_time` int(11) NOT NULL COMMENT 'Time of Trasnaction'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `item_restoration` ADD PRIMARY KEY (`guid`);
ALTER TABLE `item_restoration` MODIFY `guid` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Transaction ID';