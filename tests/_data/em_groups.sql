DROP TABLE IF EXISTS `em_groups`;

CREATE TABLE `em_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `em_groups` WRITE;
/*!40000 ALTER TABLE `em_groups` DISABLE KEYS */;

INSERT INTO `em_groups` (`id`, `name`)
VALUES
  (1,'Administrators');

/*!40000 ALTER TABLE `em_groups` ENABLE KEYS */;
UNLOCK TABLES;
