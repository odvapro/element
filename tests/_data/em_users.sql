# Дамп таблицы em_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `em_users`;

CREATE TABLE `em_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(200) NOT NULL DEFAULT '',
  `name` varchar(200) NOT NULL DEFAULT '',
  `password` varchar(200) NOT NULL DEFAULT '',
  `email` varchar(200) NOT NULL DEFAULT '',
  `language` varchar(200) NOT NULL DEFAULT 'en',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `em_users` WRITE;
/*!40000 ALTER TABLE `em_users` DISABLE KEYS */;

INSERT INTO `em_users` (`id`, `login`, `name`, `password`, `email`, `language`)
VALUES
  (1, 'admin', 'Untiteled', '25e4ee4e9229397b6b17776bfceaf8e7', 'axel0726@gmail.com', 'en'),
  (2, 'user1', 'Morris Wilson', '25e4ee4e9229397b6b17776bfceaf8e7', 'axel0726@gmail.com', 'en'),
  (3, 'user2', 'Courtney Mckinney', '25e4ee4e9229397b6b17776bfceaf8e7', 'axel0726@gmail.com', 'en');


/*!40000 ALTER TABLE `em_users` ENABLE KEYS */;
UNLOCK TABLES;
