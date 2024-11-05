-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 05, 2024 at 06:22 AM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `book_id` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) NOT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `isbn` varchar(50) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `publication_year` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`book_id`),
  UNIQUE KEY `isbn` (`isbn`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `genre`, `isbn`, `quantity`, `publication_year`, `created_at`) VALUES
('101', 'Weep Not Child', 'Ngugi Wa Thiong', 'Family', '978-2356540008', 9, 2007, '2024-11-04 13:47:03'),
('102', 'A Mother\'s Sin', 'Aisha Gulled', 'Horror', '978-0002569874', 0, 2024, '2024-11-04 14:31:06'),
('103', 'To Kill a Mocking BIrd', 'Harper Lee', 'Fiction', '978-0061120084', 5, 1960, '2024-11-04 19:52:43'),
('104', 'Aritificial Intelligence: A Modern Approach', 'Stuart Russel, Peter Norvig', 'AI, Computer Science', '978-80136042594', 24, 2009, '2024-11-04 20:23:44'),
('105', 'Harry Potter and the Deathly Hallows', 'J. K Rowling', 'Adventure', '978-0326581420', 7, 2007, '2024-11-04 20:25:18'),
('106', 'Research Design:Qualitative, Quantitave and Mixed Mthods', 'John W. Creswell', 'Research, Methodology', '978-1506386706', 8, 2017, '2024-11-04 20:26:59'),
('107', 'The Intelligent Investor', 'Benjamin Graham', 'Finance, Investing', '978-0060555665', 1, 2003, '2024-11-04 20:28:24'),
('108', 'On Politics:A History of political Thought from Herodous to the present', 'Alan Ryan', 'Political Theory', '978-0241959460', 3, 2012, '2024-11-04 20:29:14'),
('110', 'The Elements of Style', 'William Strunk Jr', 'English, Writing', '978-1593279288', 50, 2000, '2024-11-04 20:30:56'),
('109', 'Principles of Mathematical Analysis', 'Walter Rudin', 'Mathematics, Analysis', '978-0070542358', 5, 1976, '2024-11-04 20:32:27'),
('111', 'Fluent Python: Clear, Concise, and Effective Programming', 'Luciano Ramalho', 'Programming, Python', '978-1491946008', 100, 2015, '2024-11-04 20:34:34'),
('112', 'How to Feed a Brain', 'Cavin Balaster', 'Health, Nutrition', '978-0999796707', 2, 2018, '2024-11-04 20:35:36'),
('113', 'The Night Watchman', 'Louise Edrich', 'Fiction, Historical Fiction', '978-0062671189', 4, 2020, '2024-11-04 20:36:55'),
('114', 'The Hardware Hacker', 'Andrew &quot;bubbie&quot; Huang', 'Computer Engineering, Hardware', '978-1593277581', 10, 2017, '2024-11-04 20:38:53'),
('115', 'Infinite Powers', 'Steven Strogatz', 'Mathematics, Calculus', '978-1328879981', 0, 2019, '2024-11-04 20:40:02'),
('116', 'The Human Compatible', 'Stuart Rusell', 'AI, Ethics', '978-0525558613', 4, 2019, '2024-11-04 20:41:28'),
('117', 'The Obesity Code', 'Dr. Jason Fung', 'Health, Nutrition', '978-1771641258', 2, 2016, '2024-11-04 20:42:40'),
('118', 'How Democracies Die', 'Steven Levitsky', 'Politics, Political Science', '978-1524762930', 2, 2018, '2024-11-04 20:43:45'),
('119', 'Capitalism, Alone', 'Branko Milanovic', 'Economics', '978-0674987593', 2, 2019, '2024-11-04 20:45:01'),
('120', 'The Psycology of Money', 'Morgan Housel', 'Finance', '978-057197689', 4, 2020, '2024-11-04 20:45:54');

-- --------------------------------------------------------

--
-- Table structure for table `borrow_history`
--

DROP TABLE IF EXISTS `borrow_history`;
CREATE TABLE IF NOT EXISTS `borrow_history` (
  `borrow_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) DEFAULT NULL,
  `book_id` varchar(50) DEFAULT NULL,
  `borrow_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `due_date` timestamp NULL DEFAULT NULL,
  `return_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`borrow_id`),
  KEY `student_id` (`user_id`),
  KEY `book_id` (`book_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `borrow_history`
--

INSERT INTO `borrow_history` (`borrow_id`, `user_id`, `book_id`, `borrow_date`, `due_date`, `return_date`) VALUES
(3, '151-371', '102', '2024-11-04 19:57:53', NULL, NULL),
(4, '173-375', '113', '2024-11-04 20:48:14', NULL, NULL),
(5, '165-080', '112', '2024-11-04 20:49:41', NULL, NULL),
(6, '165-080', '102', '2024-11-04 20:49:48', NULL, NULL),
(7, '175-514', '116', '2024-11-04 20:50:42', NULL, NULL),
(8, '175-514', '115', '2024-11-04 20:50:49', NULL, NULL),
(9, '175-514', '114', '2024-11-04 20:51:04', NULL, NULL),
(10, '100-216', '101', '2024-11-04 20:51:51', NULL, NULL),
(11, '100-216', '105', '2024-11-04 20:51:55', NULL, NULL),
(12, '133-203', '111', '2024-11-04 20:52:34', NULL, NULL),
(13, '133-203', '116', '2024-11-04 20:52:40', NULL, NULL),
(14, '133-203', '108', '2024-11-04 20:52:45', NULL, NULL),
(15, '133-203', '104', '2024-11-04 20:52:48', NULL, NULL),
(16, '180-348', '118', '2024-11-04 20:54:39', NULL, NULL),
(17, '180-348', '107', '2024-11-04 20:54:53', NULL, NULL),
(18, '180-348', '103', '2024-11-04 20:55:02', NULL, NULL),
(19, '180-348', '111', '2024-11-04 20:55:21', NULL, '2024-11-04 20:56:58');

-- --------------------------------------------------------

--
-- Table structure for table `librarian`
--

DROP TABLE IF EXISTS `librarian`;
CREATE TABLE IF NOT EXISTS `librarian` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `librarian`
--

INSERT INTO `librarian` (`username`, `password`, `name`) VALUES
('admin', 'admin123', 'Library Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `password`, `created_at`) VALUES
('151-371', 'Ssemujju Edris', 'cuu151371', '2024-11-04 11:08:05'),
('165-080', 'Makhoha Joanita', 'cuu165080', '2024-11-04 20:14:18'),
('133-203', 'Banigo Benjamin', 'cuu133203', '2024-11-04 20:15:13'),
('173-375', 'Banguan Singh Merah', 'cuu173375', '2024-11-04 20:15:51'),
('175-514', 'Kisozi Paul Kategaya', 'cuu175514', '2024-11-04 20:16:31'),
('180-348', 'Aisha Hirsi Gulled', 'cuu180348', '2024-11-04 20:17:04'),
('100-216', 'Monday Desire', 'cuu100216', '2024-11-04 20:17:44'),
('185-687', 'Ainebye Martin', 'cuu185687', '2024-11-04 20:20:00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
