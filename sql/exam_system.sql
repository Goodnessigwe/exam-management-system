-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 20, 2025 at 12:19 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `exam_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `created_at`) VALUES
(1, 'New Exams Uploaded', 'Mathematics and Physics exams are now live.', '2025-09-15 23:34:54'),
(2, 'System Maintenance', 'Platform will be down this Friday from 2 AM – 4 AM.', '2025-09-15 23:34:54'),
(3, 'Upcoming Features', 'Practice quizzes will be added soon!', '2025-09-15 23:34:54');

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

DROP TABLE IF EXISTS `exams`;
CREATE TABLE IF NOT EXISTS `exams` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `duration` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`id`, `title`, `description`, `duration`, `created_at`) VALUES
(1, 'PHP Basics Quiz', 'Test your knowledge of basic PHP concepts.', 0, '2025-09-15 09:07:03'),
(2, 'PHP Basics Quiz2', 'Test your knowledge of basic PHP concepts2.', 0, '2025-09-15 09:08:33'),
(3, 'Exam Test', 'Exam Test1', 10, '2025-09-19 10:44:29');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question_id` int NOT NULL,
  `option_text` varchar(255) NOT NULL,
  `is_correct` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `question_id`, `option_text`, `is_correct`) VALUES
(1, 1, 'Personal Hypertext Processor', 0),
(2, 1, 'PHP: Hypertext Preprocessor', 1),
(3, 1, 'Private Home Page', 0),
(4, 1, 'Preprocessor Home Page', 0),
(5, 2, '#', 0),
(6, 2, '$', 1),
(7, 2, '&', 0),
(8, 2, '%', 0),
(9, 3, 'echo', 1),
(10, 3, 'printText()', 0),
(11, 3, 'printf', 0),
(12, 3, 'console.log()', 0);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `exam_id` int NOT NULL,
  `question_text` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `exam_id` (`exam_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `exam_id`, `question_text`, `created_at`) VALUES
(1, 1, 'What does PHP stand for?', '2025-09-15 09:11:02'),
(2, 1, 'Which symbol is used to start a variable in PHP?', '2025-09-15 09:11:02'),
(3, 1, 'Which function is used to output text in PHP?', '2025-09-15 09:11:02');

-- --------------------------------------------------------

--
-- Table structure for table `student_answers`
--

DROP TABLE IF EXISTS `student_answers`;
CREATE TABLE IF NOT EXISTS `student_answers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `exam_id` int NOT NULL,
  `question_id` int NOT NULL,
  `option_id` int NOT NULL,
  `is_correct` tinyint(1) DEFAULT '0',
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student_answers`
--

INSERT INTO `student_answers` (`id`, `user_id`, `exam_id`, `question_id`, `option_id`, `is_correct`, `submitted_at`) VALUES
(1, 2, 1, 1, 1, 0, '2025-09-15 14:59:03'),
(2, 2, 1, 2, 5, 0, '2025-09-15 14:59:03'),
(3, 2, 1, 3, 10, 0, '2025-09-15 14:59:03'),
(4, 2, 1, 1, 2, 1, '2025-09-15 15:00:05'),
(5, 2, 1, 2, 6, 1, '2025-09-15 15:00:05'),
(6, 2, 1, 3, 9, 1, '2025-09-15 15:00:05'),
(7, 2, 1, 1, 3, 0, '2025-09-15 15:01:46'),
(8, 2, 1, 2, 8, 0, '2025-09-15 15:01:46'),
(9, 2, 1, 3, 9, 1, '2025-09-15 15:01:46'),
(10, 2, 1, 1, 2, 1, '2025-09-15 15:02:06'),
(11, 2, 1, 2, 8, 0, '2025-09-15 15:02:06'),
(12, 2, 1, 3, 9, 1, '2025-09-15 15:02:06'),
(13, 3, 4, 4, 14, 1, '2025-09-19 10:58:25'),
(14, 3, 5, 5, 17, 1, '2025-09-19 11:02:25'),
(15, 3, 5, 6, 20, 1, '2025-09-19 11:02:25'),
(16, 3, 5, 7, 22, 1, '2025-09-19 11:02:25'),
(17, 2, 5, 8, 23, 0, '2025-09-19 13:18:38'),
(18, 2, 5, 9, 26, 0, '2025-09-19 13:18:38'),
(19, 2, 5, 10, 27, 0, '2025-09-19 13:18:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin') DEFAULT 'student',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Goody1', 'info1@gmail.com', '$2y$10$xFvaiy8J/gw/1C3T/OndbuMJd4dmUeZAqcseSsGabwA181QOWvrC2', 'student', '2025-09-13 15:19:13'),
(2, 'Joyce', 'info2@gmail.com', '$2y$10$YSwZ6J61BEeqL.LOr3ckBuy2rgjhkrR.xJM5ymabxzGD0JbRluZNq', 'student', '2025-09-13 15:45:21'),
(3, 'Admin', 'admin@gmail.com', '$2y$10$SYpJ/u7j4gUQH2DeRhJW1edaFCeEXq.EhlwnHAAvxC2CjL5cRzNRC', 'admin', '2025-09-17 13:55:50'),
(4, 'Joy', 'info3@gmail.com', '$2y$10$3ufwdluJo5h7MdNs54zinezfGX9dScl.cgvDbP6ZrqBWIwjFK4UBa', 'student', '2025-09-19 16:05:06');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
