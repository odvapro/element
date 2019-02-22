# ************************************************************
# Sequel Pro SQL dump
# Версия 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Адрес: 127.0.0.1 (MySQL 5.5.5-10.1.26-MariaDB-0+deb9u1)
# Схема: element
# Время создания: 2019-02-13 13:26:22 +0000
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
  `name` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `em_types` WRITE;
/*!40000 ALTER TABLE `em_types` DISABLE KEYS */;

INSERT INTO `em_types` (`id`, `table`, `field`, `type`, `required`, `settings`, `name`)
VALUES
  (1,'testTable','name','em_check',0,NULL,''),
  (2,'testTable','avat','em_file',0,NULL,''),
  (4,'newTest','name','em_string',0,NULL,''),
  (5,'testTable','col','em_tags',0,NULL,'');

/*!40000 ALTER TABLE `em_types` ENABLE KEYS */;
UNLOCK TABLES;


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
  (1,'admin','Михаил','25e4ee4e9229397b6b17776bfceaf8e7','axel0726@gmail.com'),
  (2,'dsfdsfsdf','asd','bda9643ac6601722a28f238714274da4','asdfds'),
  (3,'dsfdsfsdf','asd','bda9643ac6601722a28f238714274da4','asdfds'),
  (4,'gaga','asd','safg','asdfds'),
  (5,'gaga','asd','safg','asdfds'),
  (6,'gaga','asd','safg','asdfds'),
  (7,'gaga','asd','safg','asdfds'),
  (8,'gaga','asd','safg','asdfds'),
  (9,'gaga','asd','safg','asdfds'),
  (10,'gaga','asd','safg','asdfds'),
  (11,'gaga','asd','safg','asdfds'),
  (12,'gaga','asd','safg','asdfds'),
  (13,'gaga','asd','safg','asdfds'),
  (14,'gaga','asd','safg','asdfds'),
  (15,'gaga','asd','safg','asdfds'),
  (16,'gaga','asd','safg','asdfds');

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

LOCK TABLES `em_views` WRITE;
/*!40000 ALTER TABLE `em_views` DISABLE KEYS */;

INSERT INTO `em_views` (`id`, `name`, `table`, `filter`, `sort`, `default`, `settings`)
VALUES
  (1,'Отображение 1','newTest','[]','[]',1,'{\"columns\":{\"name\":{\"width\":200}}}'),
  (2,'Отображение 2','newTest','[]','[]',0,NULL),
  (3,'Отображение 3','testTable','[]','[]',0,NULL),
  (4,'Отображение 4','testTable','[]','[]',1,'{\"columns\":{\"name\":{\"width\":200}}}');

/*!40000 ALTER TABLE `em_views` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы newTest
# ------------------------------------------------------------

DROP TABLE IF EXISTS `newTest`;

CREATE TABLE `newTest` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(222) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `newTest` WRITE;
/*!40000 ALTER TABLE `newTest` DISABLE KEYS */;

INSERT INTO `newTest` (`id`, `name`)
VALUES
  (1,'GH');

/*!40000 ALTER TABLE `newTest` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы testTable
# ------------------------------------------------------------

DROP TABLE IF EXISTS `testTable`;

CREATE TABLE `testTable` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `email` varchar(222) DEFAULT NULL,
  `col` text,
  `avat` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `testTable` WRITE;
/*!40000 ALTER TABLE `testTable` DISABLE KEYS */;

INSERT INTO `testTable` (`id`, `name`, `email`, `col`, `avat`)
VALUES
  (1,'1','q','что то','[{\"upName\":\"1013981_329784250488770_1748150030_n.png\",\"type\":\"image\",\"sizes\":{\"small\":\"\\/element\\/public\\/upload\\/20180806\\/small_el5b68ae455cbcf.jpg\",\"thumb\":\"\\/element\\/public\\/upload\\/20180806\\/thumb_el5b68ae455cbcf.jpg\"},\"path\":\"\\/images\\/image.png\"}]'),
  (2,'0','rew','где то','[{\"upName\":\"1013981_329784250488770_1748150030_n.png\",\"type\":\"image\",\"sizes\":{\"small\":\"\\/element\\/public\\/upload\\/20180806\\/small_el5b68ae455cbcf.jpg\",\"thumb\":\"\\/element\\/public\\/upload\\/20180806\\/thumb_el5b68ae455cbcf.jpg\"},\"path\":\"\\/images\\/image.png\"}]'),
  (3,'1','4','когда то','[{\"upName\":\"1013981_329784250488770_1748150030_n.png\",\"type\":\"image\",\"sizes\":{\"small\":\"\\/element\\/public\\/upload\\/20180806\\/small_el5b68ae455cbcf.jpg\",\"thumb\":\"\\/element\\/public\\/upload\\/20180806\\/thumb_el5b68ae455cbcf.jpg\"},\"path\":\"\\/images\\/image.png\"}]');

/*!40000 ALTER TABLE `testTable` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
