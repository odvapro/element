# Дамп таблицы em_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `em_tokens`;

CREATE TABLE `em_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL,
  `value` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `em_tokens` WRITE;
/*!40000 ALTER TABLE `em_tokens` DISABLE KEYS */;

INSERT INTO `em_tokens` (`id`, `group_id`, `value`)
VALUES
	(1, 1, '4935eefddd20e1c2da613b3e64b65651'),
	(2, 2, 'b7580864176a7061daab7cd82ed2aee7');
/*!40000 ALTER TABLE `em_tokens` ENABLE KEYS */;
UNLOCK TABLES;