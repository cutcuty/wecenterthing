
DROP TABLE IF EXISTS `aws_weixin_third_party_login`;

CREATE TABLE `aws_weixin_third_party_login` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `account_id` int(10) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `rank` tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  KEY `enabled` (`enabled`),
  KEY `rank` (`rank`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信第三方接入';

LOCK TABLES `aws_weixin_third_party_login` WRITE;
/*!40000 ALTER TABLE `aws_weixin_third_party_login` DISABLE KEYS */;

INSERT INTO `aws_weixin_third_party_login` (`id`, `account_id`, `name`, `url`, `token`, `enabled`, `rank`)
VALUES
	(1,0,'wechat_shop','http://shop.test.com','abcd',1,2);

/*!40000 ALTER TABLE `aws_weixin_third_party_login` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
