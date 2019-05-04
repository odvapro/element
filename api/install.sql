# ************************************************************
# Sequel Pro SQL dump
# Версия 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Адрес: 127.0.0.1 (MySQL 5.5.5-10.1.26-MariaDB-0+deb9u1)
# Схема: element
# Время создания: 2019-05-04 21:04:47 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Дамп таблицы em_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `em_types`;

CREATE TABLE `em_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `table` varchar(200) NOT NULL DEFAULT '',
  `field` varchar(200) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `required` int(5) NOT NULL DEFAULT '0',
  `settings` text,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Дамп таблицы em_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `em_users`;

CREATE TABLE `em_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(200) NOT NULL DEFAULT '',
  `name` varchar(200) NOT NULL DEFAULT '',
  `password` varchar(200) NOT NULL DEFAULT '',
  `email` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `em_users` WRITE;
/*!40000 ALTER TABLE `em_users` DISABLE KEYS */;

INSERT INTO `em_users` (`id`, `login`, `name`, `password`, `email`)
VALUES
	(1,'admin','Untiteld','25e4ee4e9229397b6b17776bfceaf8e7','admin@email.com');

/*!40000 ALTER TABLE `em_users` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы em_views
# ------------------------------------------------------------

DROP TABLE IF EXISTS `em_views`;

CREATE TABLE `em_views` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `table` varchar(200) NOT NULL DEFAULT '',
  `filter` text,
  `sort` text,
  `default` int(11) DEFAULT NULL,
  `settings` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
