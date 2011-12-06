-- MySQL dump 10.13  Distrib 5.1.49, for pc-linux-gnu (i686)
--
-- Host: localhost    Database: audiwikiswara
-- ------------------------------------------------------
-- Server version	5.1.47

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `analytics`
--

DROP TABLE IF EXISTS `analytics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `analytics` (
  `eventid` int(11) NOT NULL AUTO_INCREMENT,
  `eventype` varchar(255) NOT NULL,
  `msgrcd` int(11) NOT NULL,
  `msglstnd` int(11) NOT NULL,
  `durlistndto` varchar(255) NOT NULL,
  `invdgtpsd` int(1) NOT NULL,
  `context` varchar(255) NOT NULL,
  `whenpressed` varchar(255) NOT NULL,
  `callid` int(11) DEFAULT NULL,
  PRIMARY KEY (`eventid`)
) ENGINE=MyISAM AUTO_INCREMENT=165486 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bad_behavior`
--

DROP TABLE IF EXISTS `bad_behavior`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bad_behavior` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `request_method` text NOT NULL,
  `request_uri` text NOT NULL,
  `server_protocol` text NOT NULL,
  `http_headers` text NOT NULL,
  `user_agent` text NOT NULL,
  `request_entity` text NOT NULL,
  `key` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`(15)),
  KEY `user_agent` (`user_agent`(10))
) ENGINE=MyISAM AUTO_INCREMENT=6855 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `callLog`
--

DROP TABLE IF EXISTS `callLog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `callLog` (
  `user` text,
  `timeOfCall` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49282 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cdr`
--

DROP TABLE IF EXISTS `cdr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cdr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `calldate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `clid` varchar(80) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `src` varchar(80) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `dst` varchar(80) NOT NULL DEFAULT '',
  `dcontext` varchar(80) NOT NULL DEFAULT '',
  `channel` varchar(80) NOT NULL DEFAULT '',
  `dstchannel` varchar(80) NOT NULL DEFAULT '',
  `lastapp` varchar(80) NOT NULL DEFAULT '',
  `lastdata` varchar(80) NOT NULL DEFAULT '',
  `duration` int(11) NOT NULL DEFAULT '0',
  `billsec` int(11) NOT NULL DEFAULT '0',
  `disposition` varchar(45) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `amaflags` int(11) NOT NULL DEFAULT '0',
  `accountcode` varchar(20) NOT NULL DEFAULT '',
  `userfield` varchar(255) NOT NULL DEFAULT '',
  `uniqueid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `calldate` (`calldate`),
  KEY `dst` (`dst`),
  KEY `accountcode` (`accountcode`)
) ENGINE=MyISAM AUTO_INCREMENT=50431 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `circledata`
--

DROP TABLE IF EXISTS `circledata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `circledata` (
  `circle` varchar(10) DEFAULT NULL,
  `circlename` varchar(100) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lb_archived`
--

DROP TABLE IF EXISTS `lb_archived`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lb_archived` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `skip_count` int(11) DEFAULT '0',
  `posted` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `author_id` int(2) DEFAULT NULL,
  `message_input` text,
  `status` int(2) DEFAULT '1',
  `edited` int(11) NOT NULL DEFAULT '0',
  `station` int(11) DEFAULT NULL,
  `audio_file` varchar(255) DEFAULT NULL,
  `title` varchar(999) DEFAULT 'No title',
  `filelocal` int(2) DEFAULT '1',
  `audio_type` int(4) DEFAULT NULL,
  `audio_length` int(8) DEFAULT NULL,
  `audio_size` int(11) DEFAULT NULL,
  `message_html` text,
  `comment_on` int(2) DEFAULT NULL,
  `comment_size` int(11) DEFAULT NULL,
  `category1_id` int(4) DEFAULT NULL,
  `category2_id` int(4) DEFAULT NULL,
  `category3_id` int(4) DEFAULT NULL,
  `category4_id` int(4) DEFAULT NULL,
  `tags` text,
  `countweb` int(11) DEFAULT NULL,
  `countfla` int(11) DEFAULT NULL,
  `countpod` int(11) DEFAULT NULL,
  `countall` int(11) DEFAULT NULL,
  `videowidth` int(11) DEFAULT NULL,
  `videoheight` int(11) DEFAULT NULL,
  `explicit` int(2) DEFAULT NULL,
  `sticky` int(2) DEFAULT NULL,
  `user` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1146 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lb_authors`
--

DROP TABLE IF EXISTS `lb_authors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lb_authors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(32) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `mail` varchar(128) DEFAULT NULL,
  `realname` varchar(64) DEFAULT NULL,
  `joined` datetime DEFAULT NULL,
  `edit_own` int(11) DEFAULT NULL,
  `publish_own` int(11) DEFAULT NULL,
  `edit_all` int(11) DEFAULT NULL,
  `publish_all` int(11) DEFAULT NULL,
  `admin` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lb_categories`
--

DROP TABLE IF EXISTS `lb_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lb_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lb_comments`
--

DROP TABLE IF EXISTS `lb_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lb_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `posting_id` int(11) DEFAULT NULL,
  `posted` datetime DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `mail` varchar(128) DEFAULT NULL,
  `web` varchar(128) DEFAULT NULL,
  `ip` varchar(32) DEFAULT NULL,
  `message_input` text,
  `message_html` text,
  `audio_file` varchar(255) DEFAULT NULL,
  `audio_type` int(4) DEFAULT NULL,
  `audio_length` int(8) DEFAULT NULL,
  `audio_size` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=127742 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lb_links`
--

DROP TABLE IF EXISTS `lb_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lb_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `posting_id` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `linkorder` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lb_postings`
--

DROP TABLE IF EXISTS `lb_postings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lb_postings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `skip_count` int(11) DEFAULT '0',
  `posted` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `author_id` int(2) DEFAULT NULL,
  `message_input` text,
  `status` int(2) DEFAULT '1',
  `edited` int(11) NOT NULL DEFAULT '0',
  `station` int(11) DEFAULT '12345',
  `audio_file` varchar(255) DEFAULT NULL,
  `title` varchar(999) DEFAULT 'No title',
  `filelocal` int(2) DEFAULT '1',
  `audio_type` int(4) DEFAULT '1',
  `audio_length` int(8) DEFAULT NULL,
  `audio_size` int(11) DEFAULT NULL,
  `message_html` text,
  `comment_on` int(2) DEFAULT '1',
  `comment_size` int(11) DEFAULT NULL,
  `category1_id` int(4) DEFAULT NULL,
  `category2_id` int(4) DEFAULT NULL,
  `category3_id` int(4) DEFAULT NULL,
  `category4_id` int(4) DEFAULT NULL,
  `tags` text,
  `countweb` int(11) DEFAULT NULL,
  `countfla` int(11) DEFAULT NULL,
  `countpod` int(11) DEFAULT NULL,
  `countall` int(11) DEFAULT NULL,
  `videowidth` int(11) DEFAULT NULL,
  `videoheight` int(11) DEFAULT NULL,
  `explicit` int(2) DEFAULT NULL,
  `sticky` int(2) DEFAULT NULL,
  `user` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6431 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lb_settings`
--

DROP TABLE IF EXISTS `lb_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lb_settings` (
  `name` varchar(32) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mobileseries`
--

DROP TABLE IF EXISTS `mobileseries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mobileseries` (
  `series` varchar(10) DEFAULT NULL,
  `provider` varchar(5) DEFAULT NULL,
  `circle` varchar(5) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patients` (
  `patient_id` int(3) DEFAULT NULL,
  `number` int(10) DEFAULT NULL,
  `language` varchar(5) DEFAULT NULL,
  `month_enrolled` int(2) DEFAULT NULL,
  `day_enrolled` int(1) DEFAULT NULL,
  `weeks_pregnant_at_enrollment` int(2) DEFAULT NULL,
  `days_pregnant_at_enrollment` int(1) DEFAULT NULL,
  `contact_days` varchar(11) DEFAULT NULL,
  `contact_time_start` decimal(17,16) DEFAULT NULL,
  `contact_time_end` decimal(17,16) DEFAULT NULL,
  `message` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stations`
--

DROP TABLE IF EXISTS `stations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `public` int(11) NOT NULL,
  `moderator` text,
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone_number` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6033 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-07-30 14:37:40
