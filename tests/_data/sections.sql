DROP TABLE IF EXISTS `sections`;

CREATE TABLE `sections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `parent_section` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;

INSERT INTO `sections` (`id`, `name`, `slug`, `parent_section`)
VALUES
	(1,'Breakfast Foods','breakfast-foods',NULL),
	(2,'Lunch Options','lunch-options',1),
	(3,'Dinner Entrees','dinner-entrees',1),
  (4,'Snacks and Appetizers','snacks-and-appetizers',1),
  (5,'Beverages','beverages',NULL),
  (6,'Meal Kits','meal-kits',5),
  (7,'Baked Goods','baked-goods',5),
  (8,'International Cuisine','international-cuisine',5),
  (9,'Vegetarian and Vegan Options','vegetarian-and-vegan-options',5),
  (10,'Gluten-Free Products','gluten-free-products',5),
  (11,'Organic Foods','organic-foods',10),
  (12,'Specialty Items','specialty-items',10),
  (13,'Meal Subscriptions','meal-subscriptions',NULL),
  (14,'Catering Services','catering-services',13),
  (15,'Gift Baskets and Boxes','gift-baskets-and-boxes',13);

/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;