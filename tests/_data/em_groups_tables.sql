# Дамп таблицы em_groups_tables
# ------------------------------------------------------------

DROP TABLE IF EXISTS `em_groups_tables`;

CREATE TABLE `em_groups_tables` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL,
  `table_name` varchar(200) DEFAULT NULL,
  `access` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `em_groups_tables` WRITE;
/*!40000 ALTER TABLE `em_groups_tables` DISABLE KEYS */;

INSERT INTO `em_groups_tables` (`id`, `group_id`, `table_name`, `access`)
VALUES
	(1, 2, 'test_table', 1),
	(2, 2, 'callbacks', 3);
/*!40000 ALTER TABLE `em_groups_tables` ENABLE KEYS */;
UNLOCK TABLES;
