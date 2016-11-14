
/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_techtest` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `db_techtest`;

/*Table structure for table `contact` */

DROP TABLE IF EXISTS `contact`;

CREATE TABLE `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `organization` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `reason` enum('Feedback','Help','HR','Other') NOT NULL,
  `specify` text,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `contact` */

insert  into `contact`(`id`,`name`,`last_name`,`organization`,`email`,`text`,`reason`,`specify`,`created`) values (1,'naam','lastai','ogri','emlu@fsfsdf.com','naam','Help','','2016-11-14 04:14:57'),(2,'name gaian','lstnam,e','org','sdfsd@sdgdfgdf.com','test','Other','sfsdfs','2016-11-14 04:15:32'),(3,'new test','last','or','sdfdfsd@sdfsdffss.com','test test test test test test test test \r\ntest \r\ntest \r\ntest \r\ntest test test test test test test test \r\ntest \r\ntest \r\ntest \r\ntest test test test test test test test \r\ntest \r\ntest \r\ntest ','Help','','2016-11-14 04:25:18');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`password`,`email`,`created`,`modified`) values (1,'Sam','$2y$10$YE9LAXniBT/M37IIez3LDOG1cbSpRdc0vSEPImEfCxpsZ2g/2b1FC','samir@otech.ne.jp',NULL,'2016-11-11 07:15:42');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
