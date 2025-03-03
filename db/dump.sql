-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: nust_elearning
-- ------------------------------------------------------
-- Server version	8.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `assignments`
--

DROP TABLE IF EXISTS `assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assignments` (
  `assignment_id` int NOT NULL AUTO_INCREMENT,
  `course_id` int DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `type` enum('quiz','project','exam','homework') NOT NULL,
  `due_date` timestamp NOT NULL,
  `total_points` decimal(5,2) DEFAULT '100.00',
  `weight_percentage` decimal(5,2) DEFAULT '100.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`assignment_id`),
  KEY `idx_assignment_course` (`course_id`),
  CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assignments`
--

LOCK TABLES `assignments` WRITE;
/*!40000 ALTER TABLE `assignments` DISABLE KEYS */;
INSERT INTO `assignments` VALUES (1,4,'E-Learning Platform','Develop an e-learning platform for NUST students','project','2025-03-09 21:47:55',100.00,40.00,'2025-03-02 21:47:55'),(2,4,'Project Management Plan','Create a comprehensive project management plan','homework','2025-03-16 21:47:55',100.00,20.00,'2025-03-02 21:47:55'),(3,6,'Group Project Phase 1','Initial project proposal and planning','project','2025-03-07 21:47:55',100.00,30.00,'2025-03-02 21:47:55'),(4,1,'Mathematical Proofs','Complex mathematical proofs assignment','homework','2025-03-05 21:47:55',100.00,15.00,'2025-03-02 21:47:55'),(5,2,'Simulation Model','Create a simulation model for a real-world problem','project','2025-03-12 21:47:55',100.00,35.00,'2025-03-02 21:47:55'),(6,3,'Mobile App Prototype','Develop a prototype mobile application','project','2025-03-10 21:47:55',100.00,45.00,'2025-03-02 21:47:55');
/*!40000 ALTER TABLE `assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courses` (
  `course_id` int NOT NULL AUTO_INCREMENT,
  `course_code` varchar(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `instructor_id` int DEFAULT NULL,
  `duration_weeks` int DEFAULT NULL,
  `duration_hours` int DEFAULT NULL,
  `status` enum('active','inactive','archived') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`course_id`),
  UNIQUE KEY `course_code` (`course_code`),
  KEY `instructor_id` (`instructor_id`),
  CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (1,'CSC4101','Advanced Mathematical Structures for Computing','Advanced mathematical concepts essential for computer science',1,14,56,'active','2025-03-02 21:47:55'),(2,'CSC4102','Computational Modelling','Fundamentals of computational modeling and simulation',1,14,56,'active','2025-03-02 21:47:55'),(3,'CSC4103','Mobile Application Development','Development of mobile applications for various platforms',2,14,56,'active','2025-03-02 21:47:55'),(4,'CSC4104','Software Project Management','Project management principles for software development',3,14,56,'active','2025-03-02 21:47:55'),(5,'CSC4105','Design and Analysis of Algorithms','Study of algorithm design techniques and analysis',2,14,56,'active','2025-03-02 21:47:55'),(6,'CSC4106','Group Project','Collaborative software development project',4,14,56,'active','2025-03-02 21:47:55');
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enrollments`
--

DROP TABLE IF EXISTS `enrollments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `enrollments` (
  `enrollment_id` int NOT NULL AUTO_INCREMENT,
  `student_id` int DEFAULT NULL,
  `course_id` int DEFAULT NULL,
  `status` enum('in_progress','completed','dropped') DEFAULT 'in_progress',
  `progress_percentage` decimal(5,2) DEFAULT '0.00',
  `enrollment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `completion_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`enrollment_id`),
  UNIQUE KEY `unique_enrollment` (`student_id`,`course_id`),
  KEY `idx_enrollment_student` (`student_id`),
  KEY `idx_enrollment_course` (`course_id`),
  CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enrollments`
--

LOCK TABLES `enrollments` WRITE;
/*!40000 ALTER TABLE `enrollments` DISABLE KEYS */;
INSERT INTO `enrollments` VALUES (1,5,1,'in_progress',65.00,'2025-03-02 21:47:55',NULL),(2,5,2,'in_progress',45.00,'2025-03-02 21:47:55',NULL),(3,5,3,'in_progress',80.00,'2025-03-02 21:47:55',NULL),(4,5,4,'in_progress',90.00,'2025-03-02 21:47:55',NULL),(5,5,5,'in_progress',75.00,'2025-03-02 21:47:55',NULL),(6,5,6,'in_progress',60.00,'2025-03-02 21:47:55',NULL),(7,6,1,'in_progress',70.00,'2025-03-02 21:47:55',NULL),(8,6,2,'in_progress',55.00,'2025-03-02 21:47:55',NULL),(9,6,3,'in_progress',85.00,'2025-03-02 21:47:55',NULL);
/*!40000 ALTER TABLE `enrollments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grades`
--

DROP TABLE IF EXISTS `grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grades` (
  `grade_id` int NOT NULL AUTO_INCREMENT,
  `submission_id` int DEFAULT NULL,
  `grade_value` decimal(3,1) NOT NULL,
  `feedback` text,
  `graded_by` int DEFAULT NULL,
  `graded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`grade_id`),
  KEY `submission_id` (`submission_id`),
  KEY `graded_by` (`graded_by`),
  CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`submission_id`),
  CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`graded_by`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grades`
--

LOCK TABLES `grades` WRITE;
/*!40000 ALTER TABLE `grades` DISABLE KEYS */;
INSERT INTO `grades` VALUES (1,1,2.1,'Good work on the platform design. Consider improving the user interface.',3,'2025-03-02 21:47:55'),(2,3,1.8,'Project proposal needs more detail on implementation strategy.',4,'2025-03-02 21:47:55');
/*!40000 ALTER TABLE `grades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `submissions`
--

DROP TABLE IF EXISTS `submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `submissions` (
  `submission_id` int NOT NULL AUTO_INCREMENT,
  `assignment_id` int DEFAULT NULL,
  `student_id` int DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `submission_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('submitted','graded','pending_review') DEFAULT 'submitted',
  PRIMARY KEY (`submission_id`),
  UNIQUE KEY `unique_submission` (`assignment_id`,`student_id`),
  KEY `idx_submission_student` (`student_id`),
  KEY `idx_submission_status` (`status`),
  CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`assignment_id`),
  CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `submissions`
--

LOCK TABLES `submissions` WRITE;
/*!40000 ALTER TABLE `submissions` DISABLE KEYS */;
INSERT INTO `submissions` VALUES (1,1,5,'/uploads/student5/assignment1.pdf','2025-02-28 21:47:55','graded'),(2,2,5,'/uploads/student5/assignment2.pdf','2025-03-01 21:47:55','submitted'),(3,3,5,'/uploads/student5/group_project.zip','2025-02-25 21:47:55','graded');
/*!40000 ALTER TABLE `submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `role` enum('student','instructor','admin') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'mufaro.kaseke@nust.ac.zw','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Mufaro','Kaseke','instructor','2025-03-02 21:47:55'),(2,'andile.dube@nust.ac.zw','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Andile','Dube','instructor','2025-03-02 21:47:55'),(3,'chilumani@nust.ac.zw','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','K','Chilumani','instructor','2025-03-02 21:47:55'),(4,'mutengeni@nust.ac.zw','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Mutengeni','M','instructor','2025-03-02 21:47:55'),(5,'student1@students.nust.ac.zw','$2y$10$.x0oBypUo/kdsBcH7q6qvuslKvE.JrNMKU16VfUFv8ulgBnTXuf96','John','Doe','student','2025-03-02 21:47:55'),(6,'student2@students.nust.ac.zw','$2y$10$.x0oBypUo/kdsBcH7q6qvuslKvE.JrNMKU16VfUFv8ulgBnTXuf96','Jane','Smith','student','2025-03-02 21:47:55'),(7,'student3@students.nust.ac.zw','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Bob','Johnson','student','2025-03-02 21:47:55'),(8,'mufarodkaseke@gmail.com','$2y$10$.x0oBypUo/kdsBcH7q6qvuslKvE.JrNMKU16VfUFv8ulgBnTXuf96','Mufaro D','Kaseke','student','2025-03-02 21:48:45');
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

-- Dump completed on 2025-03-03 13:06:56
