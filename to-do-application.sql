-- MySQL dump 10.13  Distrib 5.7.21, for Linux (x86_64)
--
-- Host: localhost    Database: to_do_application
-- ------------------------------------------------------
-- Server version	5.7.21-0ubuntu0.16.04.1

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
-- Table structure for table `AclResources`
--

DROP TABLE IF EXISTS `AclResources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AclResources` (
  `aclResourceID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aclResourceName` varchar(255) NOT NULL,
  PRIMARY KEY (`aclResourceID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AclResources`
--

LOCK TABLES `AclResources` WRITE;
/*!40000 ALTER TABLE `AclResources` DISABLE KEYS */;
/*!40000 ALTER TABLE `AclResources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AclRoleResources`
--

DROP TABLE IF EXISTS `AclRoleResources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AclRoleResources` (
  `aclRoleResourceID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aclRoleID` int(10) unsigned NOT NULL,
  `aclResourceID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`aclRoleResourceID`),
  KEY `fk_RoleResources_Roles_idx` (`aclRoleID`),
  KEY `fk_RoleResources_Resources_idx` (`aclResourceID`),
  CONSTRAINT `fk_AclRoleResources_AclResources` FOREIGN KEY (`aclResourceID`) REFERENCES `AclRoleResources` (`aclRoleResourceID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_AclRoleResources_Roles` FOREIGN KEY (`aclRoleID`) REFERENCES `Roles` (`roleID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AclRoleResources`
--

LOCK TABLES `AclRoleResources` WRITE;
/*!40000 ALTER TABLE `AclRoleResources` DISABLE KEYS */;
/*!40000 ALTER TABLE `AclRoleResources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Roles`
--

DROP TABLE IF EXISTS `Roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Roles` (
  `roleID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `roleName` varchar(45) NOT NULL,
  `active` tinyint(3) NOT NULL DEFAULT '1',
  `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateModified` timestamp NULL DEFAULT NULL,
  `dateDeleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`roleID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Roles`
--

LOCK TABLES `Roles` WRITE;
/*!40000 ALTER TABLE `Roles` DISABLE KEYS */;
INSERT INTO `Roles` VALUES (1,'Guest',1,'2018-02-18 14:15:33',NULL,NULL),(2,'User',1,'2018-02-18 14:15:33',NULL,NULL),(3,'Administrator',1,'2018-02-18 14:15:33',NULL,NULL);
/*!40000 ALTER TABLE `Roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TaskTypes`
--

DROP TABLE IF EXISTS `TaskTypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TaskTypes` (
  `taskTypeID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `taskTypeName` varchar(45) NOT NULL,
  `active` tinyint(3) NOT NULL DEFAULT '1',
  `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateModified` timestamp NULL DEFAULT NULL,
  `dateDeleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`taskTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TaskTypes`
--

LOCK TABLES `TaskTypes` WRITE;
/*!40000 ALTER TABLE `TaskTypes` DISABLE KEYS */;
INSERT INTO `TaskTypes` VALUES (1,'To do',1,'2018-02-18 18:56:35',NULL,NULL),(2,'Important',1,'2018-02-18 18:56:35',NULL,NULL),(3,'Reminder',1,'2018-02-18 18:56:35',NULL,NULL);
/*!40000 ALTER TABLE `TaskTypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Tasks`
--

DROP TABLE IF EXISTS `Tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Tasks` (
  `taskID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `taskTypeID` int(10) unsigned NOT NULL,
  `taskUserID` int(10) unsigned NOT NULL,
  `taskStatus` enum('CREATED','IN_PROCESSING','CLOSED','COMPLETED') NOT NULL,
  `taskText` varchar(500) NOT NULL,
  `taskDateTime` timestamp NULL DEFAULT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateModified` timestamp NULL DEFAULT NULL,
  `dateDeleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`taskID`),
  KEY `fk_Tasks_Users_idx` (`taskUserID`),
  KEY `fk_Tasks_TaskTypes_idx` (`taskTypeID`),
  CONSTRAINT `fk_Tasks_TaskTypes` FOREIGN KEY (`taskTypeID`) REFERENCES `TaskTypes` (`taskTypeID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Tasks_Users` FOREIGN KEY (`taskUserID`) REFERENCES `Users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Tasks`
--

LOCK TABLES `Tasks` WRITE;
/*!40000 ALTER TABLE `Tasks` DISABLE KEYS */;
INSERT INTO `Tasks` VALUES (1,1,1,'CREATED','asdadadasdadadasdadadasdadadasdadadasdadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadad',NULL,'2018-02-18 18:57:15',NULL,NULL),(2,1,1,'CREATED','asda','2018-02-18 19:38:33','2018-02-18 19:38:33',NULL,NULL),(3,1,1,'CREATED','123213','2018-02-18 19:38:40','2018-02-18 19:38:40',NULL,NULL),(4,1,1,'CREATED','asdad','2018-02-18 19:40:50','2018-02-18 19:40:50',NULL,NULL),(5,1,1,'CREATED','123123','2018-02-18 19:40:51','2018-02-18 19:40:51',NULL,NULL),(6,1,1,'CREATED','asd','2018-02-18 19:41:31','2018-02-18 19:41:31',NULL,NULL),(9,1,1,'CREATED','asdasd','2018-02-18 20:42:18','2018-02-18 20:42:18',NULL,NULL),(10,1,1,'CREATED','123123','2018-02-18 20:43:46','2018-02-18 20:43:46',NULL,NULL),(15,1,1,'CREATED','asdasda','2018-02-18 21:25:58','2018-02-18 21:25:58',NULL,NULL),(16,1,1,'CREATED','asdasda','2018-02-18 21:26:43','2018-02-18 21:26:43',NULL,NULL),(17,1,1,'CREATED','asda','2018-02-18 21:27:06','2018-02-18 21:27:06',NULL,NULL),(18,1,1,'CREATED','asdad','2018-02-18 21:27:21','2018-02-18 21:27:21',NULL,NULL),(19,1,1,'CREATED','asdad','2018-02-18 21:27:29','2018-02-18 21:27:29',NULL,NULL),(20,1,1,'CREATED','asdad','2018-02-18 21:28:37','2018-02-18 21:28:37',NULL,NULL),(21,1,1,'CREATED','asdad','2018-02-18 21:32:14','2018-02-18 21:32:14',NULL,NULL),(22,1,1,'CREATED','asdad','2018-02-18 21:34:26','2018-02-18 21:34:26',NULL,NULL),(23,1,1,'CREATED','1111','2018-02-18 21:34:32','2018-02-18 21:34:32',NULL,NULL),(24,1,1,'CREATED','asdsad','2018-02-18 21:34:35','2018-02-18 21:34:35',NULL,NULL);
/*!40000 ALTER TABLE `Tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `UserRoles`
--

DROP TABLE IF EXISTS `UserRoles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UserRoles` (
  `userRoleID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(10) unsigned NOT NULL,
  `roleID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`userRoleID`),
  KEY `fk_UserRoles_Roles_idx` (`roleID`),
  KEY `fk_UserRoles_Users_idx` (`userID`),
  CONSTRAINT `fk_UserRoles_Roles` FOREIGN KEY (`roleID`) REFERENCES `Roles` (`roleID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_UserRoles_Users` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UserRoles`
--

LOCK TABLES `UserRoles` WRITE;
/*!40000 ALTER TABLE `UserRoles` DISABLE KEYS */;
INSERT INTO `UserRoles` VALUES (1,1,2);
/*!40000 ALTER TABLE `UserRoles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `UserTaskListTasks`
--

DROP TABLE IF EXISTS `UserTaskListTasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UserTaskListTasks` (
  `userTaskListTaskID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userTaskListID` int(10) unsigned NOT NULL,
  `taskID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`userTaskListTaskID`),
  KEY `fk_UserTaskListTasks_Tasks_idx` (`taskID`),
  KEY `fk_UserTaskListTasks_1_idx` (`userTaskListID`),
  CONSTRAINT `fk_UserTaskListTasks_1` FOREIGN KEY (`userTaskListID`) REFERENCES `UserTaskLists` (`userTaskListID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_UserTaskListTasks_Tasks` FOREIGN KEY (`taskID`) REFERENCES `Tasks` (`taskID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UserTaskListTasks`
--

LOCK TABLES `UserTaskListTasks` WRITE;
/*!40000 ALTER TABLE `UserTaskListTasks` DISABLE KEYS */;
INSERT INTO `UserTaskListTasks` VALUES (17,2,22),(18,3,23),(19,1,24);
/*!40000 ALTER TABLE `UserTaskListTasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `UserTaskLists`
--

DROP TABLE IF EXISTS `UserTaskLists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UserTaskLists` (
  `userTaskListID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(10) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateModified` timestamp NULL DEFAULT NULL,
  `dateDeleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`userTaskListID`),
  KEY `fk_UserTaskLists_Users_idx` (`userID`),
  CONSTRAINT `fk_UserTaskLists_Users` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UserTaskLists`
--

LOCK TABLES `UserTaskLists` WRITE;
/*!40000 ALTER TABLE `UserTaskLists` DISABLE KEYS */;
INSERT INTO `UserTaskLists` VALUES (1,1,'Test','2018-02-18 20:06:54',NULL,NULL),(2,1,'test 123123','2018-02-18 21:24:50',NULL,NULL),(3,1,'22222','2018-02-18 21:24:57',NULL,NULL);
/*!40000 ALTER TABLE `UserTaskLists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `userID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userEmail` varchar(255) NOT NULL,
  `userPassword` varchar(60) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateModified` timestamp NULL DEFAULT NULL,
  `dateDeleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`userID`),
  KEY `Users_userEmail_idx` (`userEmail`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES (1,'rolerbol@abv.bg','$2y$10$llhww7kJ6/sj6qeiWerihuQldXWXQiB2uja5rgwImGSA1bMCU98ga','2018-02-18 15:03:50',NULL,NULL);
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-02-19  7:37:51
