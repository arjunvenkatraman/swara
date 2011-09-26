-- MySQL dump 10.13  Distrib 5.1.56, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: swara
-- ------------------------------------------------------
-- Server version	5.1.56

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
-- Dumping data for table `analytics`
--

LOCK TABLES `analytics` WRITE;
/*!40000 ALTER TABLE `analytics` DISABLE KEYS */;
/*!40000 ALTER TABLE `analytics` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `bad_behavior`
--

LOCK TABLES `bad_behavior` WRITE;
/*!40000 ALTER TABLE `bad_behavior` DISABLE KEYS */;
/*!40000 ALTER TABLE `bad_behavior` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `callLog`
--

LOCK TABLES `callLog` WRITE;
/*!40000 ALTER TABLE `callLog` DISABLE KEYS */;
/*!40000 ALTER TABLE `callLog` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `cdr`
--

LOCK TABLES `cdr` WRITE;
/*!40000 ALTER TABLE `cdr` DISABLE KEYS */;
/*!40000 ALTER TABLE `cdr` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `circledata`
--

LOCK TABLES `circledata` WRITE;
/*!40000 ALTER TABLE `circledata` DISABLE KEYS */;
INSERT INTO `circledata` VALUES ('Code','Circle','Language\r\n'),('AP ','Andhra Pradesh Telecom Circle.','Hindi\r\n'),('AS ','Assam Telecom Circle.','Hindi\r\n'),('BR ','Bihar and Jharkhand Telecom Circle.','Hindi\r\n'),('DL ','Delhi Metro Telecom Circle -includes NCR  Faridabad  Ghaziabad  Gurgaon and Noida-','Hindi\r\n'),('GJ ','Gujarat Telecom Circle -includes Daman and Diu  Dadra and Nagar Haveli-','Hindi\r\n'),('HP ','Himachal Pradesh Telecom Circle.','Hindi\r\n'),('HR ','Haryana Telecom Circle -excludes Faridabad  Gurgaon and Panchkula-','Hindi\r\n'),('JK ','Jammu and Kashmir Telecom Circle.','Hindi\r\n'),('KL ','Kerala Telecom Circle -includes Lakshadeep-','Hindi\r\n'),('KA ','Karnataka Telecom Circle.','Hindi\r\n'),('KO ','Kolkata Metro Telecom Circle -includes parts of Haora  Hooghly  North and South 24 Parganas and Nadi','Hindi\r\n'),('MH ','Maharashtra Telecom Circle -includes Goa but excludes Mumbai  Navi Mumbai and Kalyan-','Hindi\r\n'),('MP ','Madhya Pradesh and Chhattisgarh Telecom Circle.','Hindi\r\n'),('MU ','Mumbai Metro Telecom Circle -includes Navi Mumbai and Kalyan-','Hindi\r\n'),('NE ','North East India Telecom Circle -includes Arunachal Pradesh  Meghalaya  Mizoram  Nagaland  Manipur a','Hindi\r\n'),('OR ','Orissa Telecom Circle.','Hindi\r\n'),('PB ','Punjab Telecom Circle -includes Chandigarh and Panchkula-','Hindi\r\n'),('RJ ','Rajasthan Telecom Circle.','Hindi\r\n'),('TN ','Tamil Nadu Telecom Circle -includes Chennai  MEPZ  Mahabalipuram and Minjur and Pondichery except Ya','Hindi\r\n'),('UE ','Uttar Pradesh -East- Telecom Circle.','Hindi\r\n'),('UW ','Uttar Pradesh -West- and Uttarakhand Telecom Circle -excludes Ghaziabad and Noida-','Hindi\r\n'),('WB ','West Bengal Telecom Circle -includes Andaman and Nicobar  excludes Calcutta Telecom District-','Hindi\r\n'),('ZZ ','Customer Care -All Over India-','Hindi\r\n');
/*!40000 ALTER TABLE `circledata` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `lb_archived`
--

LOCK TABLES `lb_archived` WRITE;
/*!40000 ALTER TABLE `lb_archived` DISABLE KEYS */;
/*!40000 ALTER TABLE `lb_archived` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `lb_authors`
--

LOCK TABLES `lb_authors` WRITE;
/*!40000 ALTER TABLE `lb_authors` DISABLE KEYS */;
/*!40000 ALTER TABLE `lb_authors` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `lb_categories`
--

LOCK TABLES `lb_categories` WRITE;
/*!40000 ALTER TABLE `lb_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `lb_categories` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `lb_comments`
--

LOCK TABLES `lb_comments` WRITE;
/*!40000 ALTER TABLE `lb_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `lb_comments` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `lb_links`
--

LOCK TABLES `lb_links` WRITE;
/*!40000 ALTER TABLE `lb_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `lb_links` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `lb_postings`
--

LOCK TABLES `lb_postings` WRITE;
/*!40000 ALTER TABLE `lb_postings` DISABLE KEYS */;
/*!40000 ALTER TABLE `lb_postings` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `lb_settings`
--

LOCK TABLES `lb_settings` WRITE;
/*!40000 ALTER TABLE `lb_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `lb_settings` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `mobileseries`
--

LOCK TABLES `mobileseries` WRITE;
/*!40000 ALTER TABLE `mobileseries` DISABLE KEYS */;
INSERT INTO `mobileseries` VALUES ('Series','Provi','State'),('9000','AT','AP'),('9001','AT','RJ'),('9002','AT','WB'),('9003','AT','TN'),('9004','AT','MU'),('9005','AT','UE'),('9006','AT','BR'),('9007','AT','KO'),('9008','AT','KA'),('9009','ID','MP'),('9010','ID','AP'),('9011','ID','MH'),('9012','ID','UW'),('9013','DP','DL'),('9014','RG','AP'),('9015','RG','DL'),('9016','RG','GJ'),('9017','RG','HR'),('9018','RG','JK'),('9019','RG','KA'),('9020','RG','KL'),('9021','RG','MH'),('9022','RG','MU'),('9023','RG','PB'),('9024','RG','RJ'),('9025','RG','CH'),('9026','RG','AS'),('9027','RG','UW'),('9028','TD','MH'),('9029','TD','MU'),('9030','TD','AP'),('9031','TD','BR'),('9032','TD','AP'),('9033','TD','GJ'),('9034','TD','HR'),('9035','TD','KA'),('9036','TD','KA'),('9037','TD','KL'),('9038','TD','KO'),('9039','TD','MP'),('9040','TD','OR'),('9041','TD','PB'),('9042','TD','TC'),('9043','TD','TC'),('9044','TD','UE'),('9045','TD','UW'),('9046','TD','WB'),('9047','VF','TN'),('9048','VF','KL'),('9049','VF','MH'),('9050','VF','HR'),('9051','VF','KO'),('9052','VF','AP'),('9053','UN','HR'),('9054','UN','HP'),('9055','UN','JK'),('9056','UN','PB'),('9057','UN','RJ'),('9058','UN','UW'),('9059','UN','AP'),('9060','UN','KA'),('9061','UN','KL'),('9062','UN','KO'),('9063','DC','AP'),('9064','DC','AS'),('9065','DC','BR'),('9066','DC','DL'),('9067','DC','GJ'),('9068','DC','HR'),('9069','DC','HP'),('9070','DC','JK'),('9071','DC','KA'),('9072','DC','KL'),('9073','DC','KO'),('9074','DC','MP'),('9075','DC','MH'),('9076','DC','MU'),('9077','DC','NE'),('9078','DC','OR'),('9079','DC','RJ'),('9080','DC','TN'),('9081','DC','UE'),('9082','DC','UW'),('9083','DC','WB'),('9084','UN','DL'),('9085','ID','AS'),('9086','ID','JK'),('9087','ID','KA'),('9088','ID','KO'),('9089','ID','NE'),('9090','ID','OR'),('9091','ID','PB'),('9092','ID','CH'),('9093','ID','WB'),('9094','AC','CH'),('9095','AC','TN'),('9096','AT','MH'),('9097','AC','BR'),('9098','RG','MP'),('9099','VF','GJ'),('9100','LM',''),('9101','LM','AS'),('9102','LM','BR'),('9103','LM','DL'),('9104','LM','mp'),('9105','LM','HR'),('9106','LM','HP'),('9107','LM','JK'),('9108','LM','KA'),('9109','LM','KL'),('9110','LM','KO'),('9111','LM','MP'),('9112','LM','MH'),('9113','LM','NE'),('9114','LM','OR'),('9115','LM','PB'),('9116','LM','RJ'),('9117','LM','TN'),('9118','LM','UE'),('9119','LM','UW'),('9120','LM','WB'),('9121','UN','AS'),('9122','UN','BR'),('9123','UN','NE'),('9124','UN','OR'),('9125','UN','UE'),('9126','UN','WB'),('9127','ST','AS'),('9128','ST','BR'),('9129','ST','HP'),('9130','ST','JK'),('9131','ST','NE'),('9132','ST','OR'),('9133','MT','AP'),('9134','MT','AS'),('9135','MT','BR'),('9136','MT','DL'),('9137','MT','GJ'),('9138','MT','HR'),('9139','MT','HP'),('9140','MT','JK'),('9141','MT','KA'),('9142','MT','KL'),('9143','MT','KO'),('9144','MT','MP'),('9145','MT','MH'),('9146','MT','MU'),('9147','MT','NE'),('9148','MT','OR'),('9149','MT','PB'),('9150','MT','CH'),('9151','MT','UE'),('9152','RG','UW'),('9153','MT','KO'),('9154','SP','AP'),('9155','SP','DL'),('9156','SP','HR'),('9157','SP','MH'),('9158','VF','MU'),('9159','VF','TN'),('9160','VF','AP'),('9161','VF','UE'),('9162','AT','BR'),('9163','AT','KO'),('9164','ID','KA'),('9165','AT','MU'),('9166','AT','RJ'),('9167','VF','MU'),('9168','ET','TN'),('9169','ET','UE'),('9170','ET','UW'),('9171','UN','CH'),('9172','UN','MU'),('9173','UN','GJ'),('9174','UN','MP'),('9175','UN','MH'),('9176','VF','CH'),('9177','AT','AP'),('9178','AT','OR'),('9179','AT','MP'),('9180','CC','AP'),('9181','CC','AS'),('9182','CC','BR'),('9183','CC','CH'),('9184','CC','GJ'),('9185','CC','HP'),('9186','CC','JK'),('9187','CC','KA'),('9188','CC','KL'),('9189','CC','KO'),('9190','CC','MP'),('9191','CC','NE'),('9192','CC','OR'),('9193','CC','PB'),('9194','CC','RJ'),('9195','CC','TN'),('9196','CC','UE'),('9197','CC','WB'),('9198','AT','UE'),('9199','AT','BR'),('9200','TC','MP'),('9201','TC','MP'),('9202','TC','MP'),('9203','TC','MP'),('9204','TC','BR'),('9205','TC','JK'),('9206','TC','NE'),('9207','TC','AS'),('9208','TC','UE'),('9209','TC','MH'),('9210','TC','DL'),('9211','TC','DL'),('9212','TC','DL'),('9213','TC','DL'),('9214','TC','RJ'),('9215','TC','HR'),('9216','TC','PB'),('9217','TC','PB'),('9218','TC','HP'),('9219','TC','UW'),('9220','TC','MU'),('9221','TC','MU'),('9222','TC','MU'),('9223','TC','MU'),('9224','TC','MU'),('9225','TC','MH'),('9226','TC','MH'),('9227','TC','GJ'),('9228','TC','GJ'),('9229','TC','MP'),('9230','TC','KO'),('9231','TC','KO'),('9232','TC','WB'),('9233','TC','WB'),('9234','TC','BR'),('9235','TC','UE'),('9236','TC','UE'),('9237','TC','OR'),('9238','TC','OR'),('9239','TC','KO'),('9240','TC','CH'),('9241','TC','KA'),('9242','TC','KA'),('9243','TC','KA'),('9244','TC','TN'),('9245','TC','TN'),('9246','TC','AP'),('9247','TC','AP'),('9248','TC','AP'),('9249','TC','KL'),('9250','TC','DL'),('9251','TC','RJ'),('9252','TC','RJ'),('9253','TC','HR'),('9254','TC','HR'),('9255','TC','HR'),('9256','TC','PB'),('9257','TC','PB'),('9258','TC','UW'),('9259','TC','UW'),('9260','TC','TN'),('9261','TC','DL'),('9262','TC','TN'),('9263','TC','TN'),('9264','TC','TN'),('9265','TC','TN'),('9266','TC','TN'),('9267','TC','TN'),('9268','TC','DL'),('9269','TC','RJ'),('9270','TC','MH'),('9271','TC','MH'),('9272','TC','MH'),('9273','TC','MH'),('9274','TC','GJ'),('9275','TC','GJ'),('9276','TC','GJ'),('9277','TC','GJ'),('9278','TC','DL'),('9279','TC','BR'),('9280','TC','CH'),('9281','TC','CH'),('9282','TC','CH'),('9283','TC','CH'),('9284','TC','CH'),('9285','TC','CH'),('9286','TC','UW'),('9287','TC','KL'),('9288','TC','KL'),('9289','TC','DL'),('9290','TC','AP'),('9291','TC','AP'),('9292','TC','AP'),('9293','TC','AP'),('9294','TC','AP'),('9295','TC','AP'),('9296','TC','AP'),('9297','TC','AP'),('9298','TC','AP'),('9299','TC','AP'),('9300','RC','MP'),('9301','RC','MP'),('9302','RC','MP'),('9303','RC','MP'),('9304','RC','BR'),('9305','RC','UE'),('9306','RC','JK'),('9307','RC','UE'),('9308','RC','BR'),('9309','RC','RJ'),('9310','RC','DL'),('9311','RC','DL'),('9312','RC','DL'),('9313','RC','DL'),('9314','RC','RJ'),('9315','RC','HR'),('9316','RC','PB'),('9317','RC','PB'),('9318','RC','HP'),('9319','RC','UW'),('9320','RC','MU'),('9321','RC','MU'),('9322','RC','MU'),('9323','RC','MU'),('9324','RC','MU'),('9325','RC','MH'),('9326','RC','MH'),('9327','RC','GJ'),('9328','RC','GJ'),('9329','RC','MP'),('9330','RC','KO'),('9331','RC','KO'),('9332','RC','WB'),('9333','RC','WB'),('9334','RC','BR'),('9335','RC','UE'),('9336','RC','UE'),('9337','RC','OR'),('9338','RC','OR'),('9339','RC','KO'),('9340','RC','CH'),('9341','RC','KA'),('9342','RC','KA'),('9343','RC','KA'),('9344','RC','TN'),('9345','RC','TN'),('9346','RC','AP'),('9347','RC','AP'),('9348','RC','AP'),('9349','RC','KL'),('9350','RC','DL'),('9351','RC','RJ'),('9352','RC','RJ'),('9353','RC','HR'),('9354','RC','HR'),('9355','RC','HR'),('9356','RC','PB'),('9357','RC','PB'),('9358','RC','UW'),('9359','RC','UW'),('9360','RC','TN'),('9361','RC','TN'),('9362','RC','TN'),('9363','RC','TN'),('9364','RC','TN'),('9365','RC','TN'),('9366','RC','TN'),('9367','RC','TN'),('9368','RC','UW'),('9369','RC','UE'),('9370','RC','MH'),('9371','RC','MH'),('9372','RC','MH'),('9373','RC','MH'),('9374','RC','GJ'),('9375','RC','GJ'),('9376','RC','GJ'),('9377','RC','GJ'),('9378','RC','WB'),('9379','RC','KA'),('9380','RC','CH'),('9381','RC','CH'),('9382','RC','CH'),('9383','RC','CH'),('9384','RC','CH'),('9385','RC','CH'),('9386','RC','BR'),('9387','RC','KL'),('9388','RC','KL'),('9389','RC','UE'),('9390','RC','AP'),('9391','RC','AP'),('9392','RC','AP'),('9393','RC','AP'),('9394','RC','AP'),('9395','RC','AP'),('9396','RC','AP'),('9397','RC','AP'),('9398','RC','AP'),('9399','RC','AP'),('9400','BS','AP'),('9401','BS','AS'),('9402','BS','NE'),('9403','BS','MH'),('9404','BS','MH'),('9405','BS','MH'),('9406','BS','MP'),('9407','BS','MP'),('9408','BS','GJ'),('9409','BS','GJ'),('9410','BS','UW'),('9411','BS','UW'),('9412','BS','UW'),('9413','BS','RJ'),('9414','BS','RJ'),('9415','BS','UE'),('9416','BS','HR'),('9417','BS','PB'),('9418','BS','HP'),('9419','BS','JK'),('9420','BS','MH'),('9421','BS','MH'),('9422','BS','MH'),('9423','BS','MH'),('9424','BS','MP'),('9425','BS','MP'),('9426','BS','GJ'),('9427','BS','GJ'),('9428','BS','GJ'),('9429','BS','GJ'),('9430','BS','BR'),('9431','BS','BR'),('9432','BS','KO'),('9433','BS','KO'),('9434','BS','WB'),('9435','BS','AS'),('9436','BS','NE'),('9437','BS','OR'),('9438','BS','OR'),('9439','BS','OR'),('9440','BS','AP'),('9441','BS','AP'),('9442','BS','TN'),('9443','BS','TN'),('9444','BS','CH'),('9445','BS','CH'),('9446','BS','KL'),('9447','BS','KL'),('9448','BS','KA'),('9449','BS','KA'),('9450','BS','UE'),('9451','BS','UE'),('9452','BS','UE'),('9453','BS','UE'),('9454','BS','UE'),('9455','BS','UE'),('9456','BS','UW'),('9457','BS','UW'),('9458','BS','UW'),('9459','BS','HP'),('9460','BS','RJ'),('9461','BS','RJ'),('9462','BS','RJ'),('9463','BS','PB'),('9464','BS','PB'),('9465','BS','PB'),('9466','BS','HR'),('9467','BS','HR'),('9468','BS','RJ'),('9469','BS','JK'),('9470','BS','BR'),('9471','BS','BR'),('9472','BS','BR'),('9473','BS','UE'),('9474','BS','WB'),('9475','BS','WB'),('9476','BS','WB'),('9477','BS','KO'),('9478','BS','PB'),('9479','BS','MP'),('9480','BS','DL'),('9481','BS','DL'),('9482','BS','KA'),('9483','BS','DL'),('9484','BS','DL'),('9485','BS','DL'),('9486','BS','TN'),('9487','BS','TN'),('9488','BS','TN'),('9489','BS','TN'),('9490','BS','AP'),('9491','BS','AP'),('9492','BS','MP'),('9493','BS','AP'),('9494','BS','AP'),('9495','BS','KL'),('9496','BS','KL'),('9497','BS','KL'),('9498','BS','CH'),('9499','BS','CH'),('9500','AT','TN'),('9501','AT','PB'),('9502','AT','AP'),('9503','AT','MH'),('9504','AC','BR'),('9505','ID','AP'),('9506','ID','UE'),('9507','ID','BR'),('9508','RG','AS'),('9509','RG','RJ'),('9510','RG','GJ'),('9511','ET','MH'),('9512','ET','GJ'),('9513','ET','KA'),('9514','ET','TN'),('9515','ET','AP'),('9516','ET','KL'),('9517','ET','PB'),('9518','ET','HR'),('9519','ET','UE'),('9520','ET','UW'),('9521','ET','RJ'),('9522','ET','MP'),('9523','ET','BR'),('9524','AC','TN'),('9525','ID','BR'),('9526','ID','KL'),('9527','ID','MH'),('9528','RG','UW'),('9529','RG','RJ'),('9530','CG','PB'),('9531','CG','WB'),('9532','CG','UE'),('9533','RG','AP'),('9534','VF','BR'),('9535','AT','KA'),('9536','VF','UW'),('9537','VF','GJ'),('9538','VF','KA'),('9539','VF','KL'),('9540','ID','DL'),('9541','RG','HR'),('9542','ID','AP'),('9543','RG','CH'),('9544','ID','KL'),('9545','VF','MH'),('9546','AT','BR'),('9547','AT','WB'),('9548','RG','UW'),('9549','VF','RJ'),('9550','AT','AP'),('9551','AC','CH'),('9552','ID','MH'),('9553','ID','AP'),('9554','VF','UE'),('9555','RG','DL'),('9556','AT','OR'),('9557','AT','UW'),('9558','AT','GJ'),('9559','AT','UE'),('9560','AT','DL'),('9561','AT','MH'),('9562','ID','KL'),('9563','AC','WB'),('9564','VF','WB'),('9565','VF','UE'),('9566','AT','TN'),('9567','AT','KL'),('9568','ID','UW'),('9569','RG','PB'),('9570','VF','BR'),('9571','AT','RJ'),('9572','AT','BR'),('9573','AT','AP'),('9574','ID','GJ'),('9575','ID','MP'),('9576','ID','BR'),('9577','AC','AS'),('9578','AC','TN'),('9579','RG','MH'),('9580','RG','UE'),('9581','VF','AP'),('9582','VF','DL'),('9583','VF','OR'),('9584','VF','MP'),('9585','VF','TN'),('9586','VF','GJ'),('9587','VF','RJ'),('9588','ET','MU'),('9589','AT','MP'),('9590','RG','KA'),('9591','AT','KA'),('9592','ID','PB'),('9593','VF','WB'),('9594','ID','MU'),('9595','RG','MH'),('9596','AT','JK'),('9597','AT','TN'),('9598','ID','UE'),('9599','ET','DL'),('9600','AT','TN'),('9601','AT','GJ'),('9602','AT','RJ'),('9603','ID','AP'),('9604','ID','MH'),('9605','ID','KL'),('9606','RC','NE'),('9607','RC','AS'),('9608','RG','BR'),('9609','VF','WB'),('9610','VF','RJ'),('9611','AT','KA'),('9612','AT','NE'),('9613','AC','AS'),('9614','AC','WB'),('9615','AC','NE'),('9616','ID','UE'),('9617','ID','MP'),('9618','AT','AP'),('9619','VF','MU'),('9620','VF','KA'),('9621','AT','UE'),('9622','AT','JK'),('9623','ID','MH'),('9624','ID','GJ'),('9625','RG','HP'),('9626','VF','TN'),('9627','VF','UW'),('9628','VF','UE'),('9629','AT','TN'),('9630','AT','MP'),('9631','AT','BR'),('9632','AT','KA'),('9633','AT','KL'),('9634','AT','UW'),('9635','AT','WB'),('9636','AT','RJ'),('9637','VF','MH'),('9638','VF','GJ'),('9639','ID','UW'),('9640','ID','AP'),('9641','RG','WB'),('9642','VF','AP'),('9643','UN','BR'),('9644','UN','MP'),('9645','VF','KL'),('9646','VF','PB'),('9647','VF','WB'),('9648','VF','UE'),('9649','VF','RJ'),('9650','AT','DL'),('9651','AT','UE'),('9652','AT','AP'),('9653','PG','PB'),('9654','VF','DL'),('9655','VF','TN'),('9656','ID','KL'),('9657','ID','MH'),('9658','AC','OR'),('9659','AC','TN'),('9660','AT','RJ'),('9661','AT','BR'),('9662','AT','GJ'),('9663','AT','KA'),('9664','LM','MU'),('9665','AT','MU'),('9666','ID','AP'),('9667','MT','RJ'),('9668','AT','OR'),('9669','ID','MP'),('9670','VF','UE'),('9671','VF','HR'),('9672','VF','RJ'),('9673','VF','MH'),('9674','VF','KO'),('9675','VF','UW'),('9676','AT','AP'),('9677','AT','TN'),('9678','AT','AS'),('9679','AT','WB'),('9680','AT','RJ'),('9681','RG','KO'),('9682','CC','UW'),('9683','CC','HR'),('9684','CC','MH'),('9685','AT','MP'),('9686','AT','KA'),('9687','VF','GJ'),('9688','AC','TN'),('9689','ID','MH'),('9690','ID','UW'),('9691','RG','MP'),('9692','RG','OR'),('9693','RG','BR'),('9694','ID','RJ'),('9695','AT','UE'),('9696','RG','UE'),('9697','AC','JK'),('9698','AC','TN'),('9699','RG','MH'),('8000','RG','GJ'),('8001','VF','WB'),('8002','AT','BR'),('8003','AT','RJ'),('8004','CG','UE'),('8005','CG','UE'),('8006','VF','UE'),('8007','VF','MH'),('8008','AT','AP'),('8009','AT','UE'),('8010','RG','DL'),('8011','AT','AS'),('8012','AC','TN'),('8013','AC','KO'),('8014','AC','NE'),('8015','TD','TN'),('8016','AT','WB'),('8017','VF','KO'),('8018','AT','OR'),('8019','TD','AP'),('8020','',''),('8021','',''),('8022','',''),('8023','',''),('8024','',''),('8025','',''),('8026','',''),('8027','',''),('8028','',''),('8029','',''),('8030','',''),('8031','',''),('8032','',''),('8033','',''),('8034','',''),('8035','',''),('8036','',''),('8037','',''),('8038','',''),('8039','',''),('8040','',''),('8041','',''),('8042','',''),('8043','',''),('8044','',''),('8045','',''),('8046','',''),('8047','',''),('8048','',''),('8049','',''),('8050','TD','KA'),('8051','VF','BR'),('8052','VF','UE'),('8053','VF','HR'),('8054','VF','PB'),('8055','RG','MH'),('8056','AT','TN'),('8057','ID','UW'),('8058','ID','RJ'),('8059','ID','HR'),('8060','',''),('8061','',''),('8062','',''),('8063','',''),('8064','',''),('8065','',''),('8066','',''),('8067','',''),('8068','',''),('8069','',''),('8070','',''),('8071','',''),('8072','',''),('8073','',''),('8074','',''),('8075','',''),('8076','',''),('8077','',''),('8078','',''),('8079','',''),('8080','RG','MU'),('8081','RG','UE'),('8082','LM','MU'),('8083','AC','BR'),('8084','AT','BR'),('8085','AT','MP'),('8086','VF','KL'),('8087','TD','MH'),('8088','RG','KA'),('8089','TD','KL'),('8090','TD','DL'),('8091','TD','HP'),('8092','TD','RJ'),('8093','TD','OR'),('8094','VF','RJ'),('8095','VF','KA'),('8096','ID','AP'),('8097','TD','MU'),('8098','VF','TN'),('8099','RG','AP'),('8100','RG','KO'),('8101','RG','WB'),('8102','RG','BR'),('8103','RG','MP'),('8104','MT','RJ'),('8105','AT','KA'),('8106','AT','AP'),('8107','AT','RJ'),('8108','ID','MU'),('8109','TD','MP'),('8110','',''),('8111','',''),('8112','',''),('8113','',''),('8114','',''),('8115','',''),('8116','',''),('8117','',''),('8118','',''),('8119','',''),('8120','ID','MP'),('8121','ID','AP'),('8122','TD','TN'),('8123','TD','KA'),('8124','ID','TN'),('8125','TD','AP'),('8126','AT','UW'),('8127','AT','UE'),('8128','AT','GJ'),('8129','AT','KL'),('8130','',''),('8131','',''),('8132','',''),('8133','',''),('8134','',''),('8135','',''),('8136','',''),('8137','',''),('8138','',''),('8139','',''),('8140','ID','GJ'),('8141','VF','GJ'),('8142','VF','AP'),('8143','TD','AP'),('8144','RG','TN'),('8145','VF','WB'),('8146','AT','PB'),('8147','TD','KA'),('8148','TD','TN'),('8149','TD','MH'),('8150','',''),('8151','',''),('8152','',''),('8153','',''),('8154','',''),('8155','',''),('8156','',''),('8157','',''),('8158','',''),('8159','',''),('8160','',''),('8161','',''),('8162','',''),('8163','',''),('8164','',''),('8165','',''),('8166','',''),('8167','',''),('8168','',''),('8169','',''),('8170','',''),('8171','',''),('8172','',''),('8173','',''),('8174','',''),('8175','',''),('8176','',''),('8177','',''),('8178','',''),('8179','',''),('8180','',''),('8181','',''),('8182','',''),('8183','',''),('8184','',''),('8185','',''),('8186','',''),('8187','',''),('8188','',''),('8189','',''),('8190','',''),('8191','',''),('8192','',''),('8193','',''),('8194','',''),('8195','',''),('8196','',''),('8197','',''),('8198','',''),('8199','',''),('8400','',''),('8401','',''),('8402','',''),('8403','',''),('8404','',''),('8405','',''),('8406','',''),('8407','',''),('8408','',''),('8409','',''),('8410','',''),('8411','',''),('8412','',''),('8413','',''),('8414','',''),('8415','',''),('8416','',''),('8417','',''),('8418','',''),('8419','',''),('8420','',''),('8421','',''),('8422','',''),('8423','',''),('8424','',''),('8425','',''),('8426','',''),('8427','AT','PB'),('8428','',''),('8429','',''),('8430','',''),('8431','',''),('8432','',''),('8433','',''),('8434','',''),('8435','',''),('8436','UN','WB'),('8437','ID','PB'),('8438','',''),('8439','',''),('8440','',''),('8441','',''),('8442','',''),('8443','',''),('8444','',''),('8445','',''),('8446','',''),('8447','VF','DL'),('8448','',''),('8449','',''),('8450','',''),('8451','',''),('8452','',''),('8453','',''),('8454','',''),('8455','',''),('8456','',''),('8457','',''),('8458','',''),('8459','',''),('8460','',''),('8461','',''),('8462','',''),('8463','',''),('8464','',''),('8465','',''),('8466','',''),('8467','',''),('8468','',''),('8469','',''),('8470','',''),('8471','',''),('8472','',''),('8473','',''),('8474','',''),('8475','',''),('8476','',''),('8477','',''),('8478','',''),('8479','',''),('8480','',''),('8481','',''),('8295','AT','HR'),('8483','',''),('8484','',''),('8485','',''),('8486','',''),('8487','',''),('8488','',''),('8489','',''),('8490','',''),('8491','',''),('8492','',''),('8493','',''),('8494','',''),('8495','',''),('8496','',''),('8497','',''),('8498','',''),('8499','',''),('8500','',''),('8501','',''),('8502','',''),('8503','',''),('8504','',''),('8505','',''),('8506','',''),('8507','',''),('8508','',''),('8509','',''),('8510','',''),('8511','',''),('8512','',''),('8513','',''),('8514','',''),('8516','',''),('8516','',''),('8517','',''),('8518','',''),('8619','',''),('8620','',''),('8621','',''),('8622','',''),('8623','',''),('8624','',''),('8625','',''),('8626','',''),('8527','AT','DL'),('8628','',''),('8629','',''),('8630','',''),('8631','',''),('8632','',''),('8633','',''),('8634','',''),('8635','',''),('8636','',''),('8637','',''),('8638','',''),('8639','',''),('8640','',''),('8641','',''),('8642','',''),('8643','',''),('8644','',''),('8645','',''),('8646','',''),('8647','',''),('8648','',''),('8649','',''),('8650','',''),('8651','',''),('8652','ID','MH'),('8653','',''),('8654','',''),('8655','',''),('8656','',''),('8657','',''),('8658','AT','OR'),('8659','',''),('8660','',''),('8661','',''),('8662','',''),('8663','',''),('8664','',''),('8665','',''),('8666','',''),('8667','',''),('8668','',''),('8669','',''),('8670','',''),('8671','',''),('8672','',''),('8673','',''),('8674','',''),('8675','',''),('8676','',''),('8677','',''),('8678','',''),('8679','',''),('8680','',''),('8681','',''),('8482','',''),('8683','',''),('8684','',''),('8685','',''),('8686','AC','AP'),('8687','',''),('8688','',''),('8689','',''),('8690','',''),('8691','',''),('8692','',''),('8693','',''),('8694','',''),('8695','RG','TN'),('8696','',''),('8697','',''),('8698','',''),('8699','TD','PB'),('8600','AT','MH'),('8601','',''),('8602','',''),('8603','TD','BR'),('8604','',''),('8605','',''),('8606','',''),('8607','',''),('8608','',''),('8609','',''),('8610','',''),('8611','',''),('8612','',''),('8613','',''),('8614','',''),('8615','',''),('8616','',''),('8617','',''),('8618','',''),('8719','',''),('8720','',''),('8721','',''),('8722','',''),('8723','',''),('8724','',''),('8725','',''),('8726','',''),('8627','',''),('8728','',''),('8729','',''),('8730','',''),('8731','',''),('8732','',''),('8733','',''),('8734','',''),('8735','',''),('8736','',''),('8737','',''),('8738','',''),('8739','',''),('8740','',''),('8741','',''),('8742','',''),('8743','',''),('8744','',''),('8745','',''),('8746','',''),('8747','',''),('8748','',''),('8749','',''),('8750','ID','DL'),('8751','',''),('8752','',''),('8753','',''),('8754','AT','TN'),('8755','',''),('8756','',''),('8757','',''),('8758','',''),('8759','',''),('8760','',''),('8761','',''),('8762','',''),('8763','',''),('8764','',''),('8765','',''),('8766','',''),('8767','',''),('8768','',''),('8769','AT','RJ'),('8770','',''),('8771','',''),('8772','',''),('8773','',''),('8774','',''),('8775','',''),('8776','',''),('8777','',''),('8778','',''),('8779','',''),('8780','',''),('8781','',''),('8682','',''),('8783','',''),('8784','',''),('8785','',''),('8786','',''),('8787','',''),('8788','',''),('8789','',''),('8790','AT','AP'),('8791','TD','UW'),('8792','',''),('8793','',''),('8794','',''),('8795','',''),('8796','AC','MH'),('8797','ST','BR'),('8798','',''),('8799','',''),('9700','AC','AP'),('9701','AT','AP'),('9702','ID','MU'),('9703','VF','AP'),('9704','AT','AP'),('9705','ID','AP'),('9706','VF','AS'),('9707','RG','AS'),('9708','ID','BR'),('9709','VF','BR'),('9710','AC','CH'),('9711','VF','DL'),('9712','VF','GJ'),('9713','VF','MP'),('9714','ID','GJ'),('9715','AC','TN'),('9716','AC','DL'),('9717','AT','DL'),('9718','ID','DL'),('9719','VF','UW'),('9720','VF','UW'),('9721','VF','UE'),('9722','AC','GJ'),('9723','ID','GJ'),('9724','AT','GJ'),('9725','AT','GJ'),('9726','VF','GJ'),('9727','VF','GJ'),('9728','ID','HR'),('9729','AT','HR'),('9730','AT','MH'),('9731','AT','KA'),('9732','VF','WB'),('9733','VF','WB'),('9734','VF','WB'),('9735','VF','WB'),('9736','VF','HP'),('9737','ID','GJ'),('9738','AC','KA'),('9739','VF','KA'),('9740','AT','KA'),('9741','AT','KA'),('9742','VF','KA'),('9743','ID','KA'),('9744','ID','KL'),('9745','VF','KL'),('9746','AT','KL'),('9747','ID','KL'),('9748','AT','KO'),('9749','RG','WB'),('9750','AC','TN'),('9751','VF','TN'),('9752','AT','MP'),('9753','ID','MP'),('9754','ID','MP'),('9755','AT','MP'),('9756','ID','UW'),('9757','DP','MU'),('9758','VF','UW'),('9759','VF','UW'),('9760','AT','UW'),('9761','VF','UW'),('9762','AC','MH'),('9763','ID','MH'),('9764','VF','MH'),('9765','VF','MH'),('9766','AT','MH'),('9767','ID','MH'),('9768','AC','MU'),('9769','VF','MU'),('9770','RG','MP'),('9771','AT','BR'),('9772','VF','RJ'),('9773','LM','MU'),('9774','VF','NE'),('9775','VF','WB'),('9776','VF','OR'),('9777','AT','OR'),('9778','RG','OR'),('9779','AT','PB'),('9780','VF','PB'),('9781','ID','PB'),('9782','AC','RJ'),('9783','VF','RJ'),('9784','AT','RJ'),('9785','ID','RJ'),('9786','VF','TN'),('9787','VF','TN'),('9788','AC','TN'),('9789','AT','TN'),('9790','AT','TN'),('9791','AT','TN'),('9792','VF','UE'),('9793','AT','UE'),('9794','AT','UE'),('9795','ID','UE'),('9796','VF','JK'),('9797','AT','JK'),('9798','RG','BR'),('9799','AT','RJ'),('7000','AT','DL'),('7001','',''),('7002','',''),('7003','',''),('7004','',''),('7005','',''),('7006','',''),('7007','',''),('7008','',''),('7009','',''),('7010','',''),('7011','',''),('7012','',''),('7013','',''),('7014','',''),('7015','',''),('7016','',''),('7017','',''),('7018','',''),('7019','',''),('7020','',''),('7021','',''),('7022','',''),('7023','',''),('7024','',''),('7025','',''),('7026','',''),('7027','',''),('7028','',''),('7029','',''),('7030','',''),('7031','',''),('7032','',''),('7033','',''),('7034','',''),('7035','',''),('7036','',''),('7037','',''),('7038','',''),('7039','',''),('7040','',''),('7041','',''),('7042','',''),('7043','',''),('7044','',''),('7045','',''),('7046','',''),('7047','',''),('7048','',''),('7049','',''),('7050','',''),('7051','',''),('7052','',''),('7053','',''),('7054','',''),('7055','',''),('7056','',''),('7057','',''),('7058','',''),('7059','',''),('7060','',''),('7061','',''),('7062','',''),('7063','',''),('7064','',''),('7065','',''),('7066','',''),('7067','',''),('7068','',''),('7069','',''),('7070','',''),('7071','',''),('7072','',''),('7073','',''),('7074','',''),('7075','',''),('7076','',''),('7077','',''),('7078','',''),('7079','',''),('7080','',''),('7081','',''),('7082','',''),('7083','',''),('7084','',''),('7085','',''),('7086','',''),('7087','',''),('7088','',''),('7089','',''),('7090','',''),('7091','',''),('7092','',''),('7093','',''),('7094','',''),('7095','',''),('7096','',''),('7097','',''),('7098','',''),('7099','',''),('7100','',''),('7101','',''),('7102','',''),('7103','',''),('7104','',''),('7105','',''),('7106','',''),('7107','',''),('7108','',''),('7109','',''),('7110','',''),('7111','',''),('7112','',''),('7113','',''),('7114','',''),('7115','',''),('7116','',''),('7117','',''),('7118','',''),('7119','',''),('7120','',''),('7121','',''),('7122','',''),('7123','',''),('7124','',''),('7125','',''),('7126','',''),('7127','',''),('7128','',''),('7129','',''),('7130','',''),('7131','',''),('7132','',''),('7133','',''),('7134','',''),('7135','',''),('7136','',''),('7137','',''),('7138','',''),('7139','',''),('7140','',''),('7141','',''),('7142','',''),('7143','',''),('7144','',''),('7145','',''),('7146','',''),('7147','',''),('7148','',''),('7149','',''),('7150','',''),('7151','',''),('7152','',''),('7153','',''),('7154','',''),('7155','',''),('7156','',''),('7157','',''),('7158','',''),('7159','',''),('7160','',''),('7161','',''),('7162','',''),('7163','',''),('7164','',''),('7165','',''),('7166','',''),('7167','',''),('7168','',''),('7169','',''),('7170','',''),('7171','',''),('7172','',''),('7173','',''),('7174','',''),('7175','',''),('7176','',''),('7177','',''),('7178','',''),('7179','',''),('7180','',''),('7181','',''),('7182','',''),('7183','',''),('7184','',''),('7185','',''),('7186','',''),('7187','',''),('7188','',''),('7189','',''),('7190','',''),('7191','',''),('7192','',''),('7193','',''),('7194','',''),('7195','',''),('7196','',''),('7197','',''),('7198','',''),('7199','',''),('7200','TD','CH'),('7201','',''),('7202','',''),('7203','',''),('7204','T','KA'),('7205','TD','OR'),('7206','T','HR'),('7207','T','AP'),('7208','T','MU'),('7209','TD','BR'),('7210','',''),('7211','',''),('7212','',''),('7213','',''),('7214','',''),('7215','',''),('7216','',''),('7217','',''),('7218','',''),('7219','',''),('7220','',''),('7221','',''),('7222','',''),('7223','',''),('7224','',''),('7225','',''),('7226','',''),('7227','',''),('7228','',''),('7229','',''),('7230','',''),('7231','',''),('7232','',''),('7233','',''),('7234','',''),('7235','',''),('7236','',''),('7237','',''),('7238','',''),('7239','',''),('7240','',''),('7241','',''),('7242','',''),('7243','',''),('7244','',''),('7245','',''),('7246','',''),('7247','',''),('7248','',''),('7249','',''),('7250','AT','BR'),('7251','',''),('7252','',''),('7253','',''),('7254','',''),('7255','',''),('7256','',''),('7257','',''),('7258','',''),('7259','AT','KA'),('7260','',''),('7261','',''),('7262','',''),('7263','',''),('7264','',''),('7265','',''),('7266','',''),('7267','',''),('7268','',''),('7269','',''),('7270','',''),('7271','',''),('7272','',''),('7273','',''),('7274','',''),('7275','TD','UE'),('7276','TD','MH'),('7277','',''),('7278','AC','KO'),('7279','',''),('7280','',''),('7281','',''),('7282','',''),('7283','',''),('7284','',''),('7285','',''),('7286','',''),('7287','',''),('7288','',''),('7289','',''),('7290','',''),('7291','',''),('7292','',''),('7293','AC','KL'),('7294','',''),('7295','',''),('7296','',''),('7297','',''),('7298','',''),('7299','AC','CH'),('7300','',''),('7301','',''),('7302','',''),('7303','',''),('7304','',''),('7305','',''),('7306','',''),('7307','',''),('7308','',''),('7309','',''),('7310','',''),('7311','',''),('7312','',''),('7313','',''),('7314','',''),('7315','',''),('7316','',''),('7317','',''),('7318','',''),('7319','',''),('7320','',''),('7321','',''),('7322','',''),('7323','',''),('7324','',''),('7325','',''),('7326','',''),('7327','',''),('7328','',''),('7329','',''),('7330','',''),('7331','',''),('7332','',''),('7333','',''),('7334','',''),('7335','',''),('7336','',''),('7337','',''),('7338','',''),('7339','',''),('7340','',''),('7341','',''),('7342','',''),('7343','',''),('7344','',''),('7345','',''),('7346','',''),('7347','',''),('7348','',''),('7349','',''),('7350','',''),('7351','',''),('7352','',''),('7353','',''),('7354','',''),('7355','',''),('7356','',''),('7357','',''),('7358','',''),('7359','',''),('7360','',''),('7361','',''),('7362','',''),('7363','',''),('7364','',''),('7365','',''),('7366','',''),('7367','',''),('7368','',''),('7369','',''),('7370','',''),('7371','',''),('7372','',''),('7373','AC','TN'),('7374','',''),('7375','',''),('7376','CG','UE'),('7377','ID','OR'),('7378','',''),('7379','VF','UE'),('7380','',''),('7381','',''),('7382','',''),('7383','',''),('7384','',''),('7385','',''),('7386','',''),('7387','',''),('7388','',''),('7389','',''),('7390','',''),('7391','',''),('7392','',''),('7393','',''),('7394','',''),('7395','',''),('7396','UN','AP'),('7397','',''),('7398','VF','MP'),('7399','AC','AS'),('7400','',''),('7401','',''),('7402','',''),('7403','',''),('7404','',''),('7405','TI','OR'),('7406','',''),('7407','',''),('7408','',''),('7409','',''),('7410','',''),('7411','TI','KA'),('7412','',''),('7413','',''),('7414','',''),('7415','TI','MP'),('7416','TI','AP'),('7417','VG','UW'),('7418','TI','CH'),('7419','RC','MH'),('7420','',''),('7421','',''),('7422','',''),('7423','',''),('7424','',''),('7425','',''),('7426','',''),('7427','',''),('7428','RC','DL'),('7429','RC','KL'),('7430','',''),('7431','',''),('7432','',''),('7433','',''),('7434','',''),('7435','',''),('7436','',''),('7437','',''),('7438','',''),('7439','RC','KO'),('7440','',''),('7441','',''),('7442','',''),('7443','',''),('7444','',''),('7445','',''),('7446','',''),('7447','',''),('7448','',''),('7449','',''),('7450','',''),('7451','',''),('7452','',''),('7453','',''),('7454','',''),('7455','',''),('7456','',''),('7457','',''),('7458','',''),('7459','',''),('7460','',''),('7461','',''),('7462','',''),('7463','',''),('7464','',''),('7465','',''),('7466','',''),('7467','',''),('7468','',''),('7469','',''),('7470','',''),('7471','',''),('7472','',''),('7473','',''),('7474','',''),('7475','',''),('7476','',''),('7477','',''),('7478','',''),('7479','',''),('7480','',''),('7481','',''),('7482','',''),('7483','RC','KA'),('7484','',''),('7485','',''),('7486','',''),('7487','',''),('7488','RC','BR'),('7489','RC','MP'),('7490','',''),('7491','',''),('7492','',''),('7493','',''),('7494','',''),('7495','',''),('7496','',''),('7497','',''),('7498','RC','MU'),('7499','RC','UE'),('7500','ID','UW'),('7501','TD','WB'),('7502','AC','TN'),('7503','AC','DL'),('7504','AC','OR'),('7505','',''),('7506','',''),('7507','',''),('7508','',''),('7509','',''),('7510','',''),('7511','',''),('7512','',''),('7513','',''),('7514','',''),('7515','',''),('7516','',''),('7517','',''),('7518','',''),('7519','',''),('7520','',''),('7521','',''),('7522','',''),('7523','',''),('7524','',''),('7525','',''),('7526','',''),('7527','',''),('7528','',''),('7529','',''),('7530','',''),('7531','',''),('7532','',''),('7533','',''),('7534','',''),('7535','',''),('7536','',''),('7537','',''),('7538','',''),('7539','',''),('7540','',''),('7541','',''),('7542','',''),('7543','',''),('7544','',''),('7545','',''),('7546','',''),('7547','',''),('7548','',''),('7549','VF','BR'),('7550','VF','BR'),('7551','',''),('7552','',''),('7553','',''),('7554','',''),('7555','',''),('7556','',''),('7557','',''),('7558','',''),('7559','',''),('7560','',''),('7561','',''),('7562','',''),('7563','',''),('7564','',''),('7565','',''),('7566','VF','MP'),('7567','VF','GJ'),('7568','AT','RJ'),('7569','RG','AP'),('7570','',''),('7571','',''),('7572','',''),('7573','',''),('7574','',''),('7575','',''),('7576','',''),('7577','',''),('7578','',''),('7579','CG','UW'),('7580','',''),('7581','',''),('7582','',''),('7583','',''),('7584','',''),('7585','',''),('7586','',''),('7587','CG','MP'),('7588','CG','MH'),('7589','CG','PB'),('7590','',''),('7591','',''),('7592','',''),('7593','',''),('7594','',''),('7595','',''),('7596','',''),('7597','CG','RJ'),('7598','CG','TN'),('7599','CG','UW'),('7600','AT','GJ'),('7601','',''),('7602','AT','WB'),('7603','',''),('7604','',''),('7605','',''),('7606','',''),('7607','AT','UE'),('7608','',''),('7609','',''),('7610','',''),('7611','',''),('7612','','MP'),('7613','',''),('7614','',''),('7615','',''),('7616','',''),('7617','',''),('7618','',''),('7619','',''),('7620','RG','MH'),('7621','',''),('7622','',''),('7623','',''),('7624','',''),('7625','',''),('7626','',''),('7627','',''),('7628','',''),('7629','',''),('7630','',''),('7631','VF','BR'),('7632','',''),('7633','',''),('7634','',''),('7635','',''),('7636','',''),('7637','',''),('7638','',''),('7639','VF','TN'),('7640','',''),('7641','',''),('7642','',''),('7643','',''),('7644','',''),('7645','',''),('7646','',''),('7647','',''),('7648','',''),('7649','',''),('7650','',''),('7651','',''),('7652','',''),('7653','',''),('7654','ID','BR'),('7655','ID','BR'),('7656','',''),('7657','',''),('7658','',''),('7659','',''),('7660','',''),('7661','',''),('7662','',''),('7663','',''),('7664','',''),('7665','VF','RJ'),('7666','RG','MU'),('7667','RG','CH'),('7668','RG','MP'),('7669','','UE'),('7670','',''),('7671','',''),('7672','',''),('7673','',''),('7674','',''),('7675','',''),('7676','RG','KA'),('7677','RG','BR'),('7678','',''),('7679','RG','WB'),('7680','',''),('7681','',''),('7682','',''),('7683','',''),('7684','',''),('7685','',''),('7686','',''),('7687','',''),('7688','',''),('7689','',''),('7690','',''),('7691','',''),('7692','',''),('7693','',''),('7694','',''),('7695','',''),('7696','TD','PB'),('7697','ID','MP'),('7698','ID','GJ'),('7699','ID','WB'),('7700','',''),('7701','',''),('7702','AT','AP'),('7703','',''),('7704','',''),('7705','',''),('7706','',''),('7707','',''),('7708','AT','TN'),('7709','AT','MH'),('7710','',''),('7711','',''),('7712','',''),('7713','',''),('7714','',''),('7715','',''),('7716','',''),('7717','',''),('7718','',''),('7719','',''),('7720','',''),('7721','',''),('7722','',''),('7723','',''),('7724','',''),('7725','',''),('7726','',''),('7727','',''),('7728','',''),('7729','',''),('7730','',''),('7731','',''),('7732','',''),('7733','',''),('7734','',''),('7735','RG','OR'),('7736','',''),('7737','TD','RJ'),('7738','AT','MU'),('7739','AT','BR'),('7740','',''),('7741','',''),('7742','AT','RJ'),('7743','',''),('7744','',''),('7745','',''),('7746','',''),('7747','',''),('7748','',''),('7749','',''),('7750','',''),('7751','',''),('7752','',''),('7753','',''),('7754','',''),('7755','',''),('7756','',''),('7757','',''),('7758','',''),('7759','',''),('7760','AT','KA'),('7761','',''),('7762','',''),('7763','',''),('7764','',''),('7765','',''),('7766','',''),('7767','',''),('7768','',''),('7769','',''),('7770','',''),('7771','',''),('7772','',''),('7773','',''),('7774','',''),('7775','',''),('7776','',''),('7777','AT','DL'),('7778','',''),('7779','',''),('7780','',''),('7781','',''),('7782','',''),('7784','',''),('7784','',''),('7785','',''),('7786','',''),('7787','',''),('7788','',''),('7789','',''),('7790','',''),('7791','',''),('7792','',''),('7793','',''),('7794','',''),('7795','TD','KA'),('7796','',''),('7797','VF','WB'),('7798','VF','MH'),('7799','VF','AP'),('7800','VF','UE'),('7801','',''),('7802','',''),('7803','',''),('7804','',''),('7805','',''),('7806','',''),('7807','ST','HP'),('7808','ST','BR'),('7809','ST','OR'),('7810','',''),('7811','',''),('7812','',''),('7813','',''),('7814','RC','PB'),('7815','',''),('7816','',''),('7817','',''),('7818','',''),('7819','',''),('7820','',''),('7821','',''),('7822','',''),('7823','',''),('7824','',''),('7825','',''),('7826','',''),('7827','RG','DL'),('7828','RG','MP'),('7829','VF','KA'),('7830','VF','UW'),('7831','',''),('7832','',''),('7833','',''),('7834','',''),('7835','',''),('7836','',''),('7837','VF','PB'),('7838','VF','DL'),('7839','CG','UE'),('7840','',''),('7841','',''),('7842','VG','AP'),('7843','',''),('7844','',''),('7845','VG','TN'),('7846','',''),('7847','',''),('7848','',''),('7849','',''),('7850','',''),('7851','',''),('7852','',''),('7853','',''),('7854','',''),('7855','',''),('7856','',''),('7857','',''),('7858','',''),('7859','',''),('7860','ID','UE'),('7861','',''),('7862','',''),('7863','',''),('7864','',''),('7865','',''),('7866','',''),('7867','',''),('7868','',''),('7869','AT','MP'),('7870','UN','BR'),('7871','',''),('7872','',''),('7873','',''),('7874','',''),('7875','VF','MH'),('7876','RG',''),('7877','',''),('7878','RG','GJ'),('7879','RG','MP'),('7880','',''),('7881','',''),('7882','',''),('7883','',''),('7884','',''),('7885','',''),('7886','',''),('7887','',''),('7888','',''),('7889','',''),('7890','ID','KO'),('7891','',''),('7892','',''),('7893','AT','AP'),('7894','',''),('7895','AT','UW'),('7896','',''),('7897','AT','UE'),('7898','AT','MP'),('7899','UN','KA'),('7900','',''),('7901','',''),('7902','',''),('7903','',''),('7904','',''),('7905','',''),('7906','',''),('7907','',''),('7908','',''),('7909','',''),('7910','',''),('7911','',''),('7912','',''),('7913','',''),('7914','',''),('7915','',''),('7916','',''),('7917','',''),('7918','',''),('7919','',''),('7920','',''),('7921','',''),('7922','',''),('7923','',''),('7924','',''),('7925','',''),('7926','',''),('7927','',''),('7928','',''),('7929','',''),('7930','',''),('7931','',''),('7932','',''),('7933','',''),('7934','',''),('7935','',''),('7936','',''),('7937','',''),('7938','',''),('7939','',''),('7940','',''),('7941','',''),('7942','',''),('7943','',''),('7944','',''),('7945','',''),('7946','',''),('7947','',''),('7948','',''),('7949','',''),('7950','',''),('7951','',''),('7952','',''),('7953','',''),('7954','',''),('7955','',''),('7956','',''),('7957','',''),('7958','',''),('7959','',''),('7960','',''),('7961','',''),('7962','',''),('7963','',''),('7964','',''),('7965','',''),('7966','',''),('7967','',''),('7968','',''),('7969','',''),('7970','',''),('7971','',''),('7972','',''),('7973','',''),('7974','',''),('7975','',''),('7976','',''),('7977','',''),('7978','',''),('7979','',''),('7980','',''),('7981','',''),('7982','',''),('7983','',''),('7984','',''),('7985','',''),('7986','',''),('7987','',''),('7988','',''),('7989','',''),('7990','',''),('7991','',''),('7992','',''),('7993','',''),('7994','',''),('7995','',''),('7996','',''),('7997','',''),('7998','',''),('7999','',''),('9700','AC','AP'),('9701','AT','AP'),('9702','ID','MU'),('9703','VF','AP'),('9704','AT','AP'),('9705','ID','AP'),('9706','VF','AS'),('9707','RG','AS'),('9708','ID','BR'),('9709','VF','BR'),('9710','AC','CH'),('9711','VF','DL'),('9712','VF','GJ'),('9713','VF','MP'),('9714','ID','GJ'),('9715','AC','TN'),('9716','AC','DL'),('9717','AT','DL'),('9718','ID','DL'),('9719','VF','UW'),('9720','VF','UW'),('9721','VF','UE'),('9722','AC','GJ'),('9723','ID','GJ'),('9724','AT','GJ'),('9725','AT','GJ'),('9726','VF','GJ'),('9727','VF','GJ'),('9728','ID','HR'),('9729','AT','HR'),('9730','AT','MH'),('9731','AT','KA'),('9732','VF','WB'),('9733','VF','WB'),('9734','VF','WB'),('9735','VF','WB'),('9736','VF','HP'),('9737','ID','GJ'),('9738','AC','KA'),('9739','VF','KA'),('9740','AT','KA'),('9741','AT','KA'),('9742','VF','KA'),('9743','ID','KA'),('9744','ID','KL'),('9745','VF','KL'),('9746','AT','KL'),('9747','ID','KL'),('9748','AT','KO'),('9749','RG','WB'),('9750','AC','TN'),('9751','VF','TN'),('9752','AT','MP'),('9753','ID','MP'),('9754','ID','MP'),('9755','AT','MP'),('9756','ID','UW'),('9757','DP','MU'),('9758','VF','UW'),('9759','VF','UW'),('9760','AT','UW'),('9761','VF','UW'),('9762','AC','MH'),('9763','ID','MH'),('9764','VF','MH'),('9765','VF','MH'),('9766','AT','MH'),('9767','ID','MH'),('9768','AC','MU'),('9769','VF','MU'),('9770','RG','MP'),('9771','AT','BR'),('9772','VF','RJ'),('9773','LM','MU'),('9774','VF','NE'),('9775','VF','WB'),('9776','VF','OR'),('9777','AT','OR'),('9778','RG','OR'),('9779','AT','PB'),('9780','VF','PB'),('9781','ID','PB'),('9782','AC','RJ'),('9783','VF','RJ'),('9784','AT','RJ'),('9785','ID','RJ'),('9786','VF','TN'),('9787','VF','TN'),('9788','AC','TN'),('9789','AT','TN'),('9790','AT','TN'),('9791','AT','TN'),('9792','VF','UE'),('9793','AT','UE'),('9794','AT','UE'),('9795','ID','UE'),('9796','VF','JK'),('9797','AT','JK'),('9798','RG','BR'),('9799','AT','RJ'),('9800','AT','WB'),('9801','AT','BR'),('9802','DC','HR'),('9803','DC','PB'),('9804','AC','KO'),('9805','AT','HP'),('9806','AC','MP'),('9807','AC','UE'),('9808','DC','UW'),('9809','DC','KL'),('9810','AT','DL'),('9811','VF','DL'),('9812','ID','HR'),('9813','VF','HR'),('9814','ID','PB'),('9815','AT','PB'),('9816','AT','HP'),('9817','RG','HP'),('9818','AT','DL'),('9819','VF','MU'),('9820','VF','MU'),('9821','LM','MU'),('9822','ID','MH'),('9823','VF','MH'),('9824','ID','GJ'),('9825','VF','GJ'),('9826','ID','MP'),('9827','RG','MP'),('9828','VF','RJ'),('9829','AT','RJ'),('9830','VF','KO'),('9831','AT','KO'),('9832','RG','WB'),('9833','VF','MU'),('9834','AT','MP'),('9835','RG','BR'),('9836','VF','KO'),('9837','ID','UW'),('9838','VF','UE'),('9839','VF','UE'),('9840','AT','CH'),('9841','AC','CH'),('9842','AC','TN'),('9843','VF','TN'),('9844','ID','KA'),('9845','AT','KA'),('9846','VF','KL'),('9847','ID','KL'),('9848','ID','AP'),('9849','AT','AP'),('9850','ID','MH'),('9851','DC','WB'),('9852','AC','BR'),('9853','AC','OR'),('9854','AC','AS'),('9855','ID','PB'),('9856','AC','NE'),('9857','DC','HP'),('9858','DC','JK'),('9859','AC','AS'),('9860','AT','MH'),('9861','RG','OR'),('9862','AT','NE'),('9863','RG','NE'),('9864','RG','AS'),('9865','AC','TN'),('9866','AT','AP'),('9867','AT','MU'),('9868','DP','DL'),('9869','DP','MU'),('9870','LM','MU'),('9871','AT','DL'),('9872','AT','PB'),('9873','VF','DL'),('9874','VF','KO'),('9875','MT','RJ'),('9876','AT','PB'),('9877','PG','PB'),('9878','AT','PB'),('9879','VF','GJ'),('9880','AT','KA'),('9881','ID','MH'),('9882','ID','HP'),('9883','RG','KO'),('9884','VF','CH'),('9885','VF','AP'),('9886','VF','KA'),('9887','ID','RJ'),('9888','VF','PB'),('9889','ID','UE'),('9890','AT','MH'),('9891','ID','DL'),('9892','AT','MU'),('9893','AT','MP'),('9894','AT','TN'),('9895','AT','KL'),('9896','AT','HR'),('9897','AT','UW'),('9898','AT','GJ'),('9899','VF','DL'),('9900','AT','AH'),('9901','AT','KA'),('9902','AT','KA'),('9903','AT','KO'),('9904','ID','GJ'),('9905','RG','BR'),('9906','AT','JK'),('9907','RG','MP'),('9908','AT','AP'),('9909','VF','GJ'),('9910','AT','DL'),('9911','ID','DL'),('9912','ID','AP'),('9913','VF','GJ'),('9914','ID','PB'),('9915','AT','PB'),('9916','VF','KA'),('9917','ID','UW'),('9918','VF','UE'),('9919','VF','UE'),('9920','VF','MU'),('9921','ID','MH'),('9922','ID','MH'),('9923','VF','MH'),('9924','ID','GJ'),('9925','VF','GJ'),('9926','ID','MP'),('9927','ID','UW'),('9928','AT','RJ'),('9929','AT','RJ'),('9930','VF','MU'),('9931','AT','BR'),('9932','AT','WB'),('9933','AT','WB'),('9934','AT','BR'),('9935','AT','UE'),('9936','AT','UE'),('9937','AT','OR'),('9938','AT','OR'),('9939','AT','BR'),('9940','AT','CH'),('9941','AC','CH'),('9942','AC','TN'),('9943','VF','TN'),('9944','AT','TN'),('9945','AT','KA'),('9946','VF','KL'),('9947','ID','KL'),('9948','ID','AP'),('9949','AT','AP'),('9950','AT','RJ'),('9951','ID','AP'),('9952','AT','TN'),('9953','VF','DL'),('9954','AT','AS'),('9955','AT','BR'),('9956','AT','UE'),('9957','AT','AS'),('9958','AT','DL'),('9959','AT','AP'),('9960','AT','MH'),('9961','ID','KL'),('9962','VF','CH'),('9963','AT','AP'),('9964','ID','KA'),('9965','AC','TN'),('9966','VF','AP'),('9967','AT','MU'),('9968','DP','DL'),('9969','DP','MU'),('9970','AT','MH'),('9971','AT','DL'),('9972','AT','KA'),('9973','AT','BR'),('9974','AT','GJ'),('9975','AT','MH'),('9976','AC','TN'),('9977','ID','MP'),('9978','VF','GJ'),('9979','VF','GJ'),('9980','AT','KA'),('9981','AT','MP'),('9982','VF','RJ'),('9983','VF','RJ'),('9984','VF','UE'),('9985','VF','AP'),('9986','VF','KA'),('9987','AT','MU'),('9988','VF','PB'),('9989','AT','AP'),('9990','ID','DL'),('9991','VF','HR'),('9992','ID','HR'),('9993','AT','MP'),('9994','AT','TN'),('9995','AT','KL'),('9996','AT','HR'),('9997','AT','UW'),('9998','AT','GJ'),('9999','VF','DL'),('8700','',''),('8701','',''),('8702','',''),('8703','',''),('8704','',''),('8705','',''),('8706','',''),('8707','',''),('8708','',''),('8709','',''),('8710','',''),('8711','',''),('8712','',''),('8713','',''),('8714','',''),('8715','',''),('8716','',''),('8717','',''),('8718','',''),('8819','',''),('8820','',''),('8821','',''),('8822','RG','AS'),('8823','',''),('8824','',''),('8825','VC','TN'),('8826','AT','DL'),('8727','',''),('8828','DC','MU'),('8829','',''),('8830','',''),('8831','',''),('8832','',''),('8833','',''),('8834','',''),('8835','',''),('8836','',''),('8837','',''),('8838','',''),('8839','',''),('8840','',''),('8841','',''),('8842','',''),('8843','',''),('8844','LM','MU'),('8845','',''),('8846','',''),('8847','',''),('8848','',''),('8849','',''),('8850','',''),('8851','',''),('8852','',''),('8853','AT',''),('8854','',''),('8855','',''),('8856','',''),('8857','',''),('8858','',''),('8859','',''),('8860','VF','DL'),('8861','AT','KA'),('8862','',''),('8863','',''),('8864','',''),('8865','',''),('8866','TD','GJ'),('8867','TD','KA'),('8868','',''),('8869','',''),('8870','AT','TN'),('8871','TD','MP'),('8872','ID','PB'),('8873','ID','BR'),('8874','VF','UE'),('8875','VF','RJ'),('8876','VF','AS'),('8877','VF','BR'),('8878','VF','MP'),('8879','VF','MU'),('8880','RG','KA'),('8881','RG','UW'),('8782','',''),('8883','AC','TN'),('8884','',''),('8885','TD','AP'),('8886','VF','AP'),('8887','',''),('8888','ID','MH'),('8889','ID','MP'),('8890','AT','RJ'),('8891','TD','KL'),('8892','AC','KA'),('8893','RG','KL'),('8894','AT','HP'),('8895','CG','OR'),('8896','AC','UE'),('8897','AT','AP'),('8898','AC','MU'),('8899','RG','UW'),('8800','AT','DL'),('8801','AC','AP'),('8802','AC','DL'),('8803','AC','JK'),('8804','AC','BR'),('8805','ID','MH'),('8806','VF','MH'),('8807','TD','TN'),('8808','ID','UE'),('8809','AT','BR'),('8810','',''),('8811','',''),('8812','',''),('8813','',''),('8814','',''),('8815','',''),('8816','',''),('8817','RG','MP'),('8818','',''),('8919','',''),('8920','',''),('8921','',''),('8922','',''),('8923','UN',''),('8924','',''),('8925','',''),('8926','',''),('8827','AT','MP'),('8928','RG','MH'),('8929','',''),('8930','',''),('8931','',''),('8932','',''),('8933','',''),('8934','',''),('8935','',''),('8936','',''),('8937','',''),('8938','',''),('8939','VF','CH'),('8940','',''),('8941','',''),('8942','',''),('8943','',''),('8944','',''),('8945','',''),('8946','',''),('8947','',''),('8948','',''),('8949','',''),('8950','TD','HR'),('8951','TD','KA'),('8952','AT','NE'),('8953','AT','UE'),('8954','VF','UE'),('8955','RG','RJ'),('8956','RG','MH'),('8957','RG','UE'),('8958','ID','UW'),('8959','ID','MP'),('8960','TD','UE'),('8961','TD','KO'),('8962','TD','MP'),('8963','',''),('8964','',''),('8965','',''),('8966','',''),('8967','AT','WB'),('8968','AT','PB'),('8969','',''),('8970','ID','KA'),('8971','AT','KA'),('8972','AT','WB'),('8973','AC','TN'),('8974','AT','NE'),('8975','ID','MH'),('8976','TD','MH'),('8977','TD','AP'),('8978','AT','AP'),('8979','AT','UW'),('8980','VF','GJ'),('8981','TD','KO'),('8882','RG','DL'),('8983','TD','MH'),('8984','TD','OR'),('8985','CG','AP'),('8986','CG','BR'),('8987','CG','BR'),('8988','CG','HP'),('8989','CG','MP'),('8990','CG','MP'),('8991','CG','MH'),('8992','CG','PB'),('8993','CG','RJ'),('8994','CG','TN'),('8995','CG','UW'),('8996','CG','UW'),('8997','AT','RJ'),('8998','AT','BR'),('8999','AT','MU'),('8900','CG','WB'),('8901','CG','HR'),('8902','CG','KO'),('8903','CG','TN'),('8904','TD','KA'),('8905','RG','GJ'),('8906','AC','WB'),('8907','AC','KL'),('8908','AC','OR'),('8909','AC','UW'),('8910','',''),('8911','',''),('8912','',''),('8913','',''),('8914','',''),('8915','',''),('8916','',''),('8917','',''),('8918','',''),('8927','',''),('8982','TD','MP');
/*!40000 ALTER TABLE `mobileseries` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `stations`
--

LOCK TABLES `stations` WRITE;
/*!40000 ALTER TABLE `stations` DISABLE KEYS */;
/*!40000 ALTER TABLE `stations` ENABLE KEYS */;
UNLOCK TABLES;

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

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-07-30 16:25:41
