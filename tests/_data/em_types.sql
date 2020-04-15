# ************************************************************
# Sequel Pro SQL dump
# Версия 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Адрес: 127.0.0.1 (MySQL 5.7.26)
# Схема: element
# Время создания: 2020-03-01 14:31:05 +0000
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

LOCK TABLES `em_types` WRITE;
/*!40000 ALTER TABLE `em_types` DISABLE KEYS */;

INSERT INTO `em_types` (`id`, `table`, `field`, `type`, `required`, `settings`, `name`)
VALUES
	(17,'products','images','em_file',0,'{\"path\":\"public/images/\",\"required\":\"false\"}',NULL),
	(18,'block_type','file','em_file',0,'{\"savePath\":\"element\\/public\\/upload\\/\",\"resolutions\":[{\"code\":\"small\",\"width\":\"50\",\"height\":\"50\",\"required\":\"1\"}]}',NULL),
	(19,'block_type','text','em_text',0,NULL,NULL),
	(20,'block_type','date','em_date',0,NULL,NULL),
	(21,'block_type','datetime','em_date',0,NULL,NULL),
	(22,'block_type','node','em_node',0,'{\"nodeTableCode\":\"products\",\"nodeFieldCode\":\"id\",\"nodeSearchCode\":\"name\"}',NULL),
	(23,'block_type','matrix','em_matrix',0,'{\"nodeTableCode\":\"block_type_nodes\",\"keyField\":\"id\",\"nodeField\":\"block_type_id\"}',NULL);

/*!40000 ALTER TABLE `em_types` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
