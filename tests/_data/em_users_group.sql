# Дамп таблицы em_groups_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `em_groups_users`;

CREATE TABLE `em_groups_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `em_groups_users` WRITE;
/*!40000 ALTER TABLE `em_groups_users` DISABLE KEYS */;

INSERT INTO `em_groups_users` (`id`, `group_id`, `user_id`)
VALUES
	(1, 1, 1),
	(2, 2, 1),
	(3, 2, 2);
/*!40000 ALTER TABLE `em_groups_users` ENABLE KEYS */;
UNLOCK TABLES;
