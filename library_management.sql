-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2024 at 05:49 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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

CREATE TABLE `books` (
  `book_id` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) NOT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `isbn` varchar(50) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `publication_year` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `genre`, `isbn`, `quantity`, `publication_year`, `created_at`) VALUES
('101', 'Weep Not Child', 'Ngugi Wa Thiong', 'Family', '978-2356540008', 8, 2007, '2024-11-04 13:47:03'),
('102', 'A Mother\'s Sin', 'Aisha Gulled', 'Horror', '978-0002569874', 0, 2024, '2024-11-04 14:31:06'),
('103', 'To Kill a Mocking BIrd', 'Harper Lee', 'Fiction', '978-0061120084', 4, 1960, '2024-11-04 19:52:43'),
('104', 'Aritificial Intelligence: A Modern Approach', 'Stuart Russel, Peter Norvig', 'AI, Computer Science', '978-80136042594', 24, 2009, '2024-11-04 20:23:44'),
('105', 'Harry Potter and the Deathly Hallows', 'J. K Rowling', 'Adventure', '978-0326581420', 8, 2007, '2024-11-04 20:25:18'),
('106', 'Research Design:Qualitative, Quantitave and Mixed Mthods', 'John W. Creswell', 'Research, Methodology', '978-1506386706', 8, 2017, '2024-11-04 20:26:59'),
('107', 'The Intelligent Investor', 'Benjamin Graham', 'Finance, Investing', '978-0060555665', 1, 2003, '2024-11-04 20:28:24'),
('108', 'On Politics:A History of political Thought from Herodous to the present', 'Alan Ryan', 'Political Theory', '978-0241959460', 3, 2012, '2024-11-04 20:29:14'),
('110', 'The Elements of Style', 'William Strunk Jr', 'English, Writing', '978-1593279288', 50, 2000, '2024-11-04 20:30:56'),
('109', 'Principles of Mathematical Analysis', 'Walter Rudin', 'Mathematics, Analysis', '978-0070542358', 4, 1976, '2024-11-04 20:32:27'),
('111', 'Fluent Python: Clear, Concise, and Effective Programming', 'Luciano Ramalho', 'Programming, Python', '978-1491946008', 100, 2015, '2024-11-04 20:34:34'),
('112', 'How to Feed a Brain', 'Cavin Balaster', 'Health, Nutrition', '978-0999796707', 1, 2018, '2024-11-04 20:35:36'),
('113', 'The Night Watchman', 'Louise Edrich', 'Fiction, Historical Fiction', '978-0062671189', 5, 2020, '2024-11-04 20:36:55'),
('114', 'The Hardware Hacker', 'Andrew &quot;bubbie&quot; Huang', 'Computer Engineering, Hardware', '978-1593277581', 11, 2017, '2024-11-04 20:38:53'),
('115', 'Infinite Powers', 'Steven Strogatz', 'Mathematics, Calculus', '978-1328879981', 0, 2019, '2024-11-04 20:40:02'),
('116', 'The Human Compatible', 'Stuart Rusell', 'AI, Ethics', '978-0525558613', 3, 2019, '2024-11-04 20:41:28'),
('117', 'The Obesity Code', 'Dr. Jason Fung', 'Health, Nutrition', '978-1771641258', 2, 2016, '2024-11-04 20:42:40'),
('118', 'How Democracies Die', 'Steven Levitsky', 'Politics, Political Science', '978-1524762930', 2, 2018, '2024-11-04 20:43:45'),
('119', 'Capitalism, Alone', 'Branko Milanovic', 'Economics', '978-0674987593', 2, 2019, '2024-11-04 20:45:01'),
('120', 'The Psycology of Money', 'Morgan Housel', 'Finance', '978-057197689', 4, 2020, '2024-11-04 20:45:54'),
('121', 'The Rule of law', 'Tom Bingham', 'Law, Legal Theory', '978-0141034539', 31, 2014, '2024-11-05 09:40:43'),
('122', 'The color of Law', 'Richard Rothestein', 'Law, Social Justice', '978-16331494536', 7, 2017, '2024-11-05 09:42:02'),
('123', 'The Kiss Quotient', 'Helen Hoang', 'Romance, Contemporary', '978-0451490803', 2, 2018, '2024-11-05 09:43:09'),
('124', 'People We Meet on Vacation', 'Emily Henry', 'Romance, Contemporary', '978-1984806758', 1, 2021, '2024-11-05 09:44:26'),
('125', 'The Order of Time', 'Carbo Rovelli', 'Physics, Science', '978-0735216105', 13, 2018, '2024-11-05 09:45:51'),
('126', 'Digital Minimalism', 'Cal Newport', 'Information Technology', '978-0525536512', 80, 2019, '2024-11-05 09:47:08'),
('127', 'The Girl on the Train', 'Paula Hawkins', 'Thriller', '978-1594634024', 6, 2015, '2024-11-05 09:48:09'),
('128', 'The Silent Patient', 'Alex Michaelides', 'Thriller', '978-1250301697', 3, 2019, '2024-11-05 09:49:23'),
('129', 'Building a StoryBrand', 'Donald Miller', 'Storytelling, marketing', '978-0712033323', 7, 2017, '2024-11-05 09:50:51'),
('130', 'Breath: The New Science of a Lost Art', 'James Nestor', 'Health, Wellness', '978-0735213616', 4, 2020, '2024-11-05 09:52:25');

-- --------------------------------------------------------

--
-- Table structure for table `borrow_history`
--

CREATE TABLE `borrow_history` (
  `borrow_id` int(11) NOT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `book_id` varchar(50) DEFAULT NULL,
  `borrow_date` timestamp NULL DEFAULT current_timestamp(),
  `due_date` timestamp NULL DEFAULT NULL,
  `return_date` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `borrow_history`
--

INSERT INTO `borrow_history` (`borrow_id`, `student_id`, `book_id`, `borrow_date`, `due_date`, `return_date`) VALUES
(3, '151-371', '102', '2024-11-04 19:57:53', NULL, '2024-11-05 06:54:31'),
(4, '173-375', '113', '2024-11-04 20:48:14', NULL, '2024-11-07 09:14:03'),
(5, '165-080', '112', '2024-11-04 20:49:41', NULL, NULL),
(6, '165-080', '102', '2024-11-04 20:49:48', NULL, NULL),
(7, '175-514', '116', '2024-11-04 20:50:42', NULL, NULL),
(8, '175-514', '115', '2024-11-04 20:50:49', NULL, NULL),
(9, '175-514', '114', '2024-11-04 20:51:04', NULL, '2024-11-05 10:24:46'),
(10, '100-216', '101', '2024-11-04 20:51:51', NULL, NULL),
(11, '100-216', '105', '2024-11-04 20:51:55', NULL, '2024-11-08 04:45:24'),
(12, '133-203', '111', '2024-11-04 20:52:34', NULL, NULL),
(13, '133-203', '116', '2024-11-04 20:52:40', NULL, NULL),
(14, '133-203', '108', '2024-11-04 20:52:45', NULL, NULL),
(15, '133-203', '104', '2024-11-04 20:52:48', NULL, NULL),
(16, '180-348', '118', '2024-11-04 20:54:39', NULL, NULL),
(17, '180-348', '107', '2024-11-04 20:54:53', NULL, NULL),
(18, '180-348', '103', '2024-11-04 20:55:02', NULL, NULL),
(19, '180-348', '111', '2024-11-04 20:55:21', NULL, '2024-11-04 20:56:58'),
(20, '151-371', '116', '2024-11-05 06:54:54', NULL, NULL),
(21, '151-371', '109', '2024-11-05 06:55:05', NULL, NULL),
(22, '173-375', '112', '2024-11-07 09:15:24', NULL, NULL),
(23, '173-375', '103', '2024-11-07 09:15:29', NULL, NULL),
(24, '151-371', '103', '2024-11-08 04:36:19', NULL, '2024-11-08 04:36:25'),
(25, '185-687', '101', '2024-11-08 04:46:13', NULL, NULL),
(26, '185-687', '129', '2024-11-08 04:46:19', NULL, NULL),
(27, '185-687', '102', '2024-11-08 04:46:24', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `librarian`
--

CREATE TABLE `librarian` (
  `admin_id` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `librarian`
--

INSERT INTO `librarian` (`admin_id`, `password`, `name`) VALUES
('admin', 'admin123', 'Library Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `name`, `password`, `created_at`) VALUES
('151-371', 'Ssemujju Edris', 'cuu151371', '2024-11-04 11:08:05'),
('165-080', 'Makhoha Joanita', 'cuu165080', '2024-11-04 20:14:18'),
('133-203', 'Banigo Benjamin', 'cuu133203', '2024-11-04 20:15:13'),
('173-375', 'Banguan Singh Merah', 'cuu173375', '2024-11-04 20:15:51'),
('175-514', 'Kisozi Paul Kategaya', 'cuu175514', '2024-11-04 20:16:31'),
('180-348', 'Aisha Hirsi Gulled', 'cuu180348', '2024-11-04 20:17:04'),
('100-216', 'Monday Desire', 'cuu100216', '2024-11-04 20:17:44'),
('185-687', 'Ainebye Martin', 'cuu185687', '2024-11-04 20:20:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD UNIQUE KEY `isbn` (`isbn`);

--
-- Indexes for table `borrow_history`
--
ALTER TABLE `borrow_history`
  ADD PRIMARY KEY (`borrow_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `librarian`
--
ALTER TABLE `librarian`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `borrow_history`
--
ALTER TABLE `borrow_history`
  MODIFY `borrow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
