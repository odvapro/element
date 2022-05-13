# Дамп таблицы block_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `block_type`;

CREATE TABLE `block_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(50) DEFAULT 'text',
  `name` varchar(50) DEFAULT NULL,
  `node` int(11) DEFAULT NULL,
  `list` varchar(50) DEFAULT NULL,
  `file` text,
  `text` text,
  `date` date DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `matrix` text,
  `int` int(11) DEFAULT NULL,
  `flag` varchar(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `block_type` WRITE;
/*!40000 ALTER TABLE `block_type` DISABLE KEYS */;

INSERT INTO `block_type` (`id`, `value`, `name`, `node`, `file`, `text`, `date`, `datetime`, `matrix`, `int`, `flag`)
VALUES
  (1,'text','text',NULL,NULL,'Lorem ipsum <br/> </div> dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',NULL,NULL,NULL,10,'0'),
  (2,'product-card','cart',NULL,NULL,'','2019-08-24',NULL,NULL,3,'1'),
  (3,'small-card','cart2',NULL,NULL,'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',NULL,NULL,NULL,1,'0'),
  (4,'slider','slider',NULL,NULL,'',NULL,NULL,NULL,4,'1'),
  (6,'form-order','Форма заказа',NULL,NULL,'',NULL,NULL,NULL,NULL,'1');

/*!40000 ALTER TABLE `block_type` ENABLE KEYS */;
UNLOCK TABLES;