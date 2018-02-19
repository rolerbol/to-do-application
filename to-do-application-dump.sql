CREATE DATABASE  IF NOT EXISTS `to_do_application` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `to_do_application`;
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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AclResources`
--

LOCK TABLES `AclResources` WRITE;
/*!40000 ALTER TABLE `AclResources` DISABLE KEYS */;
INSERT INTO `AclResources` VALUES (1,'Application\\Controller\\AuthController_index'),(2,'Application\\Controller\\AuthController_login'),(3,'Application\\Controller\\AuthController_logout'),(4,'Application\\Controller\\AuthController_register'),(5,'Application\\Controller\\AuthController_rebuild'),(6,'Application\\Controller\\TaskController_list'),(7,'Application\\Controller\\TaskController_userTasks'),(8,'Application\\Controller\\TaskController_addTask'),(9,'Application\\Controller\\TaskController_addTaskList'),(10,'Application\\Controller\\TaskController_index'),(11,'Application\\Controller\\IndexController_index'),(12,'Application\\Controller\\AdminController_index'),(13,'Application\\Controller\\TaskListController_addTaskList'),(14,'Application\\Controller\\TaskListController_index'),(15,'Application\\Controller\\TaskListController_removeTaskList'),(16,'Application\\Controller\\TaskController_removeTask'),(17,'Application\\Controller\\TaskController_editTask'),(20,'Application\\Controller\\TaskListController_exportTaskList'),(21,'Application\\Controller\\TaskController_archiveTask'),(22,'Application\\Controller\\TaskListController_archiveTaskList'),(23,'Application\\Controller\\TaskListController_confirmDeleteTaskList');
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
  `aclAllow` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`aclRoleResourceID`),
  KEY `fk_RoleResources_Roles_idx` (`aclRoleID`),
  KEY `fk_AclRoleResources_AclResources_idx` (`aclResourceID`),
  CONSTRAINT `fk_AclRoleResources_AclResources` FOREIGN KEY (`aclResourceID`) REFERENCES `AclResources` (`aclResourceID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_AclRoleResources_Roles` FOREIGN KEY (`aclRoleID`) REFERENCES `Roles` (`roleID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AclRoleResources`
--

LOCK TABLES `AclRoleResources` WRITE;
/*!40000 ALTER TABLE `AclRoleResources` DISABLE KEYS */;
INSERT INTO `AclRoleResources` VALUES (1,1,1,1),(2,1,2,1),(3,1,3,1),(4,1,4,1),(5,3,5,1),(6,3,6,1),(7,3,7,1),(8,3,8,1),(9,3,9,1),(10,3,10,1),(11,3,11,1),(12,3,3,1),(13,3,13,1),(15,3,15,1),(16,3,16,1),(17,3,17,1),(18,3,20,1),(26,3,12,1),(27,3,21,1),(28,3,22,1),(29,2,6,1),(30,2,7,1),(31,2,8,1),(32,2,9,1),(33,2,10,1),(34,2,11,1),(36,2,13,1),(37,2,14,1),(38,2,15,1),(39,2,16,1),(40,2,17,1),(41,2,20,1),(42,2,21,1),(43,2,22,1),(44,2,3,1),(45,3,23,1);
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
  `taskArchive` tinyint(1) NOT NULL DEFAULT '0',
  `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateModified` timestamp NULL DEFAULT NULL,
  `dateDeleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`taskID`),
  KEY `fk_Tasks_Users_idx` (`taskUserID`),
  KEY `fk_Tasks_TaskTypes_idx` (`taskTypeID`),
  CONSTRAINT `fk_Tasks_TaskTypes` FOREIGN KEY (`taskTypeID`) REFERENCES `TaskTypes` (`taskTypeID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Tasks_Users` FOREIGN KEY (`taskUserID`) REFERENCES `Users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Tasks`
--

LOCK TABLES `Tasks` WRITE;
/*!40000 ALTER TABLE `Tasks` DISABLE KEYS */;
INSERT INTO `Tasks` VALUES (1,1,1,'CREATED','asdadadasdadadasdadadasdadadasdadadasdadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadasdadadad',NULL,0,'2018-02-18 18:57:15',NULL,NULL),(2,1,1,'CREATED','asda','2018-02-18 19:38:33',0,'2018-02-18 19:38:33',NULL,NULL),(3,1,1,'CREATED','123213','2018-02-18 19:38:40',0,'2018-02-18 19:38:40',NULL,NULL),(4,1,1,'CREATED','asdad','2018-02-18 19:40:50',0,'2018-02-18 19:40:50',NULL,NULL),(5,1,1,'CREATED','123123','2018-02-18 19:40:51',0,'2018-02-18 19:40:51',NULL,NULL),(6,1,1,'CREATED','asd','2018-02-18 19:41:31',0,'2018-02-18 19:41:31',NULL,NULL),(9,1,1,'CREATED','asdasd','2018-02-18 20:42:18',0,'2018-02-18 20:42:18',NULL,NULL),(10,1,1,'CREATED','123123','2018-02-18 20:43:46',0,'2018-02-18 20:43:46',NULL,NULL),(15,1,1,'CREATED','asdasda','2018-02-18 21:25:58',0,'2018-02-18 21:25:58',NULL,NULL),(16,1,1,'CREATED','asdasda','2018-02-18 21:26:43',0,'2018-02-18 21:26:43',NULL,NULL),(17,1,1,'CREATED','asda','2018-02-18 21:27:06',0,'2018-02-18 21:27:06',NULL,NULL),(18,1,1,'CREATED','asdad','2018-02-18 21:27:21',0,'2018-02-18 21:27:21',NULL,NULL),(19,1,1,'CREATED','asdad','2018-02-18 21:27:29',0,'2018-02-18 21:27:29',NULL,NULL),(20,1,1,'CREATED','asdad','2018-02-18 21:28:37',0,'2018-02-18 21:28:37',NULL,NULL),(21,1,1,'CREATED','asdad','2018-02-18 21:32:14',0,'2018-02-18 21:32:14',NULL,NULL),(22,1,1,'CREATED','asdad','2018-02-18 21:34:26',0,'2018-02-18 21:34:26',NULL,'2018-02-19 12:51:40'),(23,1,1,'CREATED','1111','2018-02-18 21:34:32',0,'2018-02-18 21:34:32',NULL,NULL),(24,1,1,'CREATED','asdsad','2018-02-18 21:34:35',0,'2018-02-18 21:34:35',NULL,'2018-02-19 12:51:48'),(25,1,1,'CREATED','asdad','2018-02-19 08:51:50',0,'2018-02-19 08:51:50',NULL,NULL),(26,1,1,'CREATED','123123','2018-02-19 08:51:53',0,'2018-02-19 08:51:53',NULL,NULL),(27,1,1,'CREATED','asdsad','2018-02-19 12:04:23',0,'2018-02-19 12:04:23',NULL,NULL),(28,1,1,'CREATED','asdsad','2018-02-19 12:04:47',0,'2018-02-19 12:04:47',NULL,NULL),(29,1,1,'CREATED','asdsad','2018-02-19 12:11:01',0,'2018-02-19 12:11:01',NULL,'2018-02-19 12:51:52'),(30,1,1,'CREATED','asdasd','2018-02-19 12:11:33',0,'2018-02-19 12:11:33',NULL,NULL),(31,1,1,'CREATED','asdsad','2018-02-19 12:27:48',0,'2018-02-19 12:27:48',NULL,NULL),(32,1,1,'CLOSED','asdasd1231231','2018-02-19 13:05:56',1,'2018-02-19 13:05:56','2018-02-19 15:24:15',NULL),(33,1,1,'IN_PROCESSING','asdad','2018-02-19 14:36:24',0,'2018-02-19 14:36:24','2018-02-19 14:36:34',NULL),(34,1,1,'CREATED','123123','2018-02-19 14:36:29',0,'2018-02-19 14:36:29',NULL,'2018-02-19 15:22:19'),(35,1,1,'CREATED','sdad1231','2018-02-19 14:45:43',0,'2018-02-19 14:45:43',NULL,NULL),(36,1,1,'CREATED','asdad','2018-02-19 20:30:09',0,'2018-02-19 20:30:09',NULL,NULL),(37,1,1,'COMPLETED','asdad','2018-02-19 20:31:00',0,'2018-02-19 20:31:00','2018-02-19 20:31:07',NULL),(38,1,6,'CREATED','asdadsa','2018-02-19 20:35:27',0,'2018-02-19 20:35:27',NULL,NULL),(39,1,1,'CREATED','asdsad','2018-02-19 20:44:21',0,'2018-02-19 20:44:21',NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UserRoles`
--

LOCK TABLES `UserRoles` WRITE;
/*!40000 ALTER TABLE `UserRoles` DISABLE KEYS */;
INSERT INTO `UserRoles` VALUES (1,1,3),(5,5,2),(6,6,2);
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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UserTaskListTasks`
--

LOCK TABLES `UserTaskListTasks` WRITE;
/*!40000 ALTER TABLE `UserTaskListTasks` DISABLE KEYS */;
INSERT INTO `UserTaskListTasks` VALUES (17,2,22),(18,3,23),(19,1,24),(20,5,25),(21,5,26),(22,5,27),(23,5,28),(24,2,29),(25,7,30),(26,2,31),(27,1,32),(28,1,33),(29,1,34),(30,9,35),(31,10,36),(32,11,37),(33,12,38),(34,11,39);
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
  `archive` tinyint(1) NOT NULL DEFAULT '0',
  `deleteRequest` tinyint(1) NOT NULL DEFAULT '0',
  `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateModified` timestamp NULL DEFAULT NULL,
  `dateDeleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`userTaskListID`),
  KEY `fk_UserTaskLists_Users_idx` (`userID`),
  CONSTRAINT `fk_UserTaskLists_Users` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UserTaskLists`
--

LOCK TABLES `UserTaskLists` WRITE;
/*!40000 ALTER TABLE `UserTaskLists` DISABLE KEYS */;
INSERT INTO `UserTaskLists` VALUES (1,1,'Test',0,0,'2018-02-18 20:06:54',NULL,'2018-02-19 15:32:45'),(2,1,'test 123123',0,0,'2018-02-18 21:24:50',NULL,'2018-02-19 12:51:55'),(3,1,'22222',0,0,'2018-02-18 21:24:57',NULL,'2018-02-19 12:10:21'),(4,1,'asdasd',0,0,'2018-02-19 08:51:16',NULL,'2018-02-19 12:10:01'),(5,1,'123123',0,0,'2018-02-19 08:51:46',NULL,'2018-02-19 12:09:57'),(6,1,'1111',0,0,'2018-02-19 11:46:11',NULL,'2018-02-19 12:09:34'),(7,1,'asdasd',0,0,'2018-02-19 12:11:07',NULL,'2018-02-19 12:24:41'),(8,1,'123123',0,0,'2018-02-19 12:11:26',NULL,'2018-02-19 12:11:29'),(9,1,'asdada',1,0,'2018-02-19 14:45:29','2018-02-19 15:32:18',NULL),(10,1,'asdad',0,0,'2018-02-19 20:29:17',NULL,'2018-02-19 20:30:48'),(11,1,'123',0,0,'2018-02-19 20:30:53',NULL,'2018-02-19 20:59:18'),(12,6,'asdad',0,0,'2018-02-19 20:35:22',NULL,NULL),(13,1,'sadsad',0,1,'2018-02-19 21:01:45','2018-02-19 21:01:50',NULL),(14,1,'asdad',0,1,'2018-02-19 21:03:45','2018-02-19 21:03:50',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES (1,'rolerbol@abv.bg','$2y$10$llhww7kJ6/sj6qeiWerihuQldXWXQiB2uja5rgwImGSA1bMCU98ga','2018-02-18 15:03:50',NULL,NULL),(5,'rolerbol@abv.bg','$2y$10$FKB9lZMxmMmIRi1ytu73oeMPjB2U5Px3VCXobE6Y9DE9r/9Ou5lxq','2018-02-19 20:26:16',NULL,NULL),(6,'rolerbolclient@abv.bg','$2y$10$LNcgljTJx2W3fCTf4qfUl.3Je7kTMN7f0QmLvWbcVwHwZi71p/dwi','2018-02-19 20:32:50',NULL,NULL);
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

-- Dump completed on 2018-02-19 23:09:51
