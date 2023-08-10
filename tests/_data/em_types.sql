# ************************************************************
# Sequel Pro SQL dump
# Версия 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Адрес: 127.0.0.1 (MySQL 5.7.37)
# Схема: dz-element
# Время создания: 2023-07-22 16:22:16 +0000
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
  (21,'block_type','datetime','em_date',0,'{\"includeTime\":\"true\"}',NULL),
  (22,'block_type','node','em_node',0,'{\"multiple\":\"true\",\"nodeTableCode\":\"products\",\"nodeFieldCode\":\"id\",\"nodeSearchCode\":\"name\"}',NULL),
  (23,'block_type','matrix','em_matrix',0,'{\"finalTableCode\":\"block_type_nodes\",\"localField\":\"id\",\"finalTableField\":\"block_type_id\"}',NULL),
  (24,'block_type','int','em_int',0,'',NULL),
  (25,'block_type','flag','em_check',0,'',NULL),
  (26,'pages','products','em_matrix',0,'{\"isManyToMany\":\"true\",\"localField\":\"id\",\"nodeTableCode\":\"pages_products\",\"nodeTableField\":\"page_id\",\"nodeTableFinalTableField\":\"product_id\",\"finalTableCode\":\"products\",\"finalTableField\":\"id\"}',NULL),
  (27,'block_type','list','em_list',0,'{\"list\":[{\"key\":\"first\",\"value\":\"\\u041f\\u0435\\u0440\\u0432\\u043e\\u0435 \\u0437\\u043d\\u0430\\u0447\\u0435\\u043d\\u0438\\u0435\"},{\"key\":\"second\",\"value\":\"\\u0412\\u0442\\u043e\\u0440\\u043e\\u0435 \\u0437\\u043d\\u0430\\u0447\\u0435\\u043d\\u0438\\u0435\"},{\"key\":\"third\",\"value\":\"\\u0422\\u0440\\u0435\\u0442\\u0435\\u0435 \\u0437\\u043d\\u0430\\u0447\\u0435\\u043d\\u0438\\u0435\"},{\"key\":\"forth\",\"value\":\"\\u0427\\u0435\\u0442\\u0432\\u0435\\u0440\\u0442\\u043e\\u0435 \\u0437\\u043d\\u0430\\u0447\\u0435\\u043d\\u0438\\u0435\"}],\"multiple\":\"false\"}',NULL),
  (28,'block_type','section','em_section',0,'{\"multiple\":\"false\",\"sectionTableCode\":\"sections\",\"sectionFieldCode\":\"id\",\"sectionSearchCode\":\"name\",\"sectionParentsFieldCode\":\"parent_section\",\"saveInForeign\":\"true\",\"foreignTableCode\":\"block_sections\",\"foreignElementFieldCode\":\"block_id\",\"foreignSectionFieldCode\":\"section_id\"}',NULL);

/*!40000 ALTER TABLE `em_types` ENABLE KEYS */;
UNLOCK TABLES;


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
