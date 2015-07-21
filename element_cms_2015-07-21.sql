# ************************************************************
# Sequel Pro SQL dump
# Версия 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Адрес: 127.0.0.1 (MySQL 5.6.19)
# Схема: element_cms
# Время создания: 2015-07-21 18:42:24 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Дамп таблицы em_names
# ------------------------------------------------------------

DROP TABLE IF EXISTS `em_names`;

CREATE TABLE `em_names` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `table` varchar(200) NOT NULL DEFAULT '',
  `field` varchar(200) DEFAULT '',
  `type` int(5) NOT NULL,
  `name` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `em_names` WRITE;
/*!40000 ALTER TABLE `em_names` DISABLE KEYS */;

INSERT INTO `em_names` (`id`, `table`, `field`, `type`, `name`)
VALUES
	(1,'test_table','',0,'ТЕСТОВАЯ ТАБЛИЦА'),
	(2,'something','',0,'Еще одна  таблица');

/*!40000 ALTER TABLE `em_names` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы em_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `em_types`;

CREATE TABLE `em_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `table` varchar(200) NOT NULL DEFAULT '',
  `field` varchar(200) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `required` int(5) NOT NULL DEFAULT '0',
  `multiple` int(5) NOT NULL DEFAULT '0',
  `settings` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `em_types` WRITE;
/*!40000 ALTER TABLE `em_types` DISABLE KEYS */;

INSERT INTO `em_types` (`id`, `table`, `field`, `type`, `required`, `multiple`, `settings`)
VALUES
	(3,'test_table','date','em_date',0,1,NULL),
	(4,'test_table','datetime','em_datetime',0,0,NULL),
	(5,'test_table','file','em_file',0,1,'{\"savePath\":\"element\\/public\\/upload\\/\"}'),
	(6,'test_table','name','em_string',0,1,NULL),
	(7,'test_table','text','em_text',0,0,'{\"visualEditor\":\"1\"}'),
	(8,'test_table','element','em_node',0,0,'{\"nodeTable\":\"test_table\",\"nodeField\":\"text\"}'),
	(10,'test_table','checkbox','em_bool',0,0,NULL);

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
	(1,'admin','Михаил','21232f297a57a5a743894a0e4a801fc3','axel0726@gmail.com');

/*!40000 ALTER TABLE `em_users` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы fuck_table
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fuck_table`;

CREATE TABLE `fuck_table` (
  `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `lolo` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Дамп таблицы something
# ------------------------------------------------------------

DROP TABLE IF EXISTS `something`;

CREATE TABLE `something` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Дамп таблицы test_table
# ------------------------------------------------------------

DROP TABLE IF EXISTS `test_table`;

CREATE TABLE `test_table` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `text` text,
  `date` date DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `file` text,
  `element` text,
  `checkbox` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `test_table` WRITE;
/*!40000 ALTER TABLE `test_table` DISABLE KEYS */;

INSERT INTO `test_table` (`id`, `name`, `text`, `date`, `datetime`, `file`, `element`, `checkbox`)
VALUES
	(1,'testName','test','2010-11-12','2010-11-12 12:30:00',NULL,NULL,1),
	(2,'testName','dsds','2010-11-12','2010-11-12 12:30:00',NULL,NULL,NULL),
	(6,'testName','tests','2010-11-12','2010-11-12 12:30:00',NULL,NULL,NULL),
	(8,'djdfkjslfj','dsfsdfsdfsd','2010-11-22','2010-11-12 00:00:00',NULL,NULL,NULL),
	(19,NULL,NULL,'2016-08-08','2015-07-16 23:00:00',NULL,NULL,NULL),
	(20,'adsdas',NULL,'2015-07-08','2015-07-16 23:00:00',NULL,NULL,NULL),
	(21,'new','<div>sdsdsds</div>','2014-01-09','2015-07-16 23:09:00','[{\"upName\":\"IMG_0028.JPG\",\"type\":\"image\",\"sizes\":{\"small\":\"element\\/public\\/upload\\/20150721\\/s_el55ad6bb842655.jpg\",\"medium\":\"element\\/public\\/upload\\/20150721\\/m_el55ad6bb842655.jpg\"},\"path\":\"element\\/public\\/upload\\/20150721\\/o_el55ad6bb842655.JPG\"}]','1212',1),
	(22,'sdsds','test&nbsp;<div><br></div><div><br></div><div><b>sdsdsd</b></div>','2015-06-29','2015-07-17 19:00:00',NULL,NULL,NULL),
	(23,NULL,NULL,NULL,NULL,NULL,'1',NULL);

/*!40000 ALTER TABLE `test_table` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
