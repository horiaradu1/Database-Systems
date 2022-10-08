-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 07, 2020 at 10:43 PM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quiz_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `failingstudents` ()  BEGIN
    SELECT  users.username,
            Quiz_Student.Score,
            Quiz_Student.Max_Score,
            Quiz_Student.Quiz_ID
    FROM    
            Quiz_Student
    INNER JOIN   
            users
    ON
            users.id = Quiz_Student.Student_ID
    AND 
            (Quiz_Student.Score/Quiz_Student.Max_Score) < 0.4;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Answers`
--

CREATE TABLE `Answers` (
  `Answer_ID` int(11) NOT NULL,
  `Answer_String` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Answers`
--

INSERT INTO `Answers` (`Answer_ID`, `Answer_String`) VALUES
(125, 'Correct'),
(126, 'Incorrect 1'),
(127, 'Incorrect 2'),
(128, 'Incorrect 3'),
(129, 'Correct'),
(130, 'Incorrect 1'),
(131, 'Incorrect 2'),
(132, 'Incorrect 3'),
(133, 'Correct'),
(134, 'Incorrect 1'),
(135, 'Incorrect 2'),
(136, 'Incorrect 3'),
(137, 'Correct'),
(138, 'Incorrect 1'),
(139, 'Incorrect 2'),
(140, 'I don\'t think is correct'),
(141, 'not yet ready but correct'),
(142, 'not yet ready'),
(143, 'not yet ready'),
(144, 'not yet ready'),
(145, 'yes'),
(146, 'no'),
(147, 'no'),
(148, 'no'),
(149, 'yes'),
(150, 'no'),
(151, 'no'),
(152, 'no'),
(153, 'yes'),
(154, 'no'),
(155, 'no'),
(156, 'no'),
(157, 'yes'),
(158, 'no'),
(159, 'no'),
(160, 'no'),
(161, 'yes'),
(162, 'no'),
(163, 'no'),
(164, 'no'),
(165, 'yes'),
(166, 'no'),
(167, 'no'),
(168, 'no'),
(169, 'CORRECT!'),
(170, 'WRONG'),
(171, 'WRONG'),
(172, 'WRONG'),
(173, 'CORRECT!'),
(174, 'WRONG'),
(175, 'WRONG'),
(176, 'WRONG'),
(177, 'CORRECT!'),
(178, 'WRONG'),
(179, 'WRONG'),
(180, 'WRONG'),
(181, 'john'),
(182, 'horia'),
(183, 'horia'),
(184, 'horia'),
(185, 'john'),
(186, 'horia'),
(187, 'horia'),
(188, 'horia'),
(189, 'john'),
(190, 'horia'),
(191, 'horia'),
(192, 'horia');

-- --------------------------------------------------------

--
-- Table structure for table `Question`
--

CREATE TABLE `Question` (
  `Question_ID` int(11) NOT NULL,
  `Question_String` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Question`
--

INSERT INTO `Question` (`Question_ID`, `Question_String`) VALUES
(32, 'This is a medium question, what is the correct answer?'),
(33, 'This is a hard question, what is the correct answer?'),
(34, 'This is a very hard question, what is the correct answer?'),
(35, 'This is the most difficult question, what is the correct answer?'),
(36, 'mock question'),
(37, 'Long?'),
(38, 'Long?'),
(39, 'Long?'),
(40, 'Long?'),
(41, 'Long?'),
(42, 'Long?'),
(43, 'Easy'),
(44, 'Very Easy'),
(45, 'Easiest'),
(46, 'John\'s Question 1'),
(47, 'John\'s Question 2'),
(48, 'John\'s Question 3');

-- --------------------------------------------------------

--
-- Table structure for table `Question_Answers`
--

CREATE TABLE `Question_Answers` (
  `Answer_ID` int(11) NOT NULL,
  `Question_ID` int(11) NOT NULL,
  `Is_Correct` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Question_Answers`
--

INSERT INTO `Question_Answers` (`Answer_ID`, `Question_ID`, `Is_Correct`) VALUES
(125, 32, 1),
(126, 32, 0),
(127, 32, 0),
(128, 32, 0),
(129, 33, 1),
(130, 33, 0),
(131, 33, 0),
(132, 33, 0),
(133, 34, 1),
(134, 34, 0),
(135, 34, 0),
(136, 34, 0),
(137, 35, 1),
(138, 35, 0),
(139, 35, 0),
(140, 35, 0),
(141, 36, 1),
(142, 36, 0),
(143, 36, 0),
(144, 36, 0),
(145, 37, 1),
(146, 37, 0),
(147, 37, 0),
(148, 37, 0),
(149, 38, 1),
(150, 38, 0),
(151, 38, 0),
(152, 38, 0),
(153, 39, 1),
(154, 39, 0),
(155, 39, 0),
(156, 39, 0),
(157, 40, 1),
(158, 40, 0),
(159, 40, 0),
(160, 40, 0),
(161, 41, 1),
(162, 41, 0),
(163, 41, 0),
(164, 41, 0),
(165, 42, 1),
(166, 42, 0),
(167, 42, 0),
(168, 42, 0),
(169, 43, 1),
(170, 43, 0),
(171, 43, 0),
(172, 43, 0),
(173, 44, 1),
(174, 44, 0),
(175, 44, 0),
(176, 44, 0),
(177, 45, 1),
(178, 45, 0),
(179, 45, 0),
(180, 45, 0),
(181, 46, 1),
(182, 46, 0),
(183, 46, 0),
(184, 46, 0),
(185, 47, 1),
(186, 47, 0),
(187, 47, 0),
(188, 47, 0),
(189, 48, 1),
(190, 48, 0),
(191, 48, 0),
(192, 48, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Quiz`
--

CREATE TABLE `Quiz` (
  `Quiz_ID` int(11) NOT NULL,
  `Quiz_Name` varchar(255) NOT NULL,
  `Quiz_Author_ID` int(11) NOT NULL,
  `Quiz_Available` tinyint(1) NOT NULL,
  `Quiz_Duration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Quiz`
--

INSERT INTO `Quiz` (`Quiz_ID`, `Quiz_Name`, `Quiz_Author_ID`, `Quiz_Available`, `Quiz_Duration`) VALUES
(1, 'Very Hard Quiz', 1, 1, 120),
(2, 'Unavailable Quiz', 1, 0, 10),
(3, 'Very Long Quiz', 1, 1, 999),
(4, 'Easy Quiz', 1, 1, 5),
(6, 'John\'s Quiz', 2, 1, 24);

--
-- Triggers `Quiz`
--
DELIMITER $$
CREATE TRIGGER `quizdeleting` AFTER DELETE ON `Quiz` FOR EACH ROW BEGIN
    INSERT INTO Quiz_Deleted_Log (Staff_ID, Quiz_ID, Date_Time) VALUES (@staff, OLD.Quiz_ID, CURRENT_TIMESTAMP);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Quiz_Deleted_Log`
--

CREATE TABLE `Quiz_Deleted_Log` (
  `Log_ID` int(11) NOT NULL,
  `Staff_ID` int(11) NOT NULL,
  `Quiz_ID` int(11) NOT NULL,
  `Date_Time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Quiz_Deleted_Log`
--

INSERT INTO `Quiz_Deleted_Log` (`Log_ID`, `Staff_ID`, `Quiz_ID`, `Date_Time`) VALUES
(1, 1, 11, '2020-12-07 20:22:06'),
(2, 1, 10, '2020-12-07 21:09:45'),
(3, 2, 5, '2020-12-07 21:12:24');

-- --------------------------------------------------------

--
-- Table structure for table `Quiz_Question`
--

CREATE TABLE `Quiz_Question` (
  `Question_ID` int(11) NOT NULL,
  `Quiz_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Quiz_Question`
--

INSERT INTO `Quiz_Question` (`Question_ID`, `Quiz_ID`) VALUES
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 2),
(37, 3),
(38, 3),
(39, 3),
(40, 3),
(41, 3),
(42, 3),
(43, 4),
(44, 4),
(45, 4),
(46, 6),
(47, 6),
(48, 6);

-- --------------------------------------------------------

--
-- Table structure for table `Quiz_Student`
--

CREATE TABLE `Quiz_Student` (
  `Attempt_ID` int(11) NOT NULL,
  `Student_ID` int(11) NOT NULL,
  `Quiz_ID` int(11) NOT NULL,
  `Score` int(11) NOT NULL,
  `Max_Score` int(11) NOT NULL,
  `Date_of_Attempt` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Quiz_Student`
--

INSERT INTO `Quiz_Student` (`Attempt_ID`, `Student_ID`, `Quiz_ID`, `Score`, `Max_Score`, `Date_of_Attempt`) VALUES
(1, 1, 1, 1, 4, '2020-12-07'),
(2, 1, 1, 3, 4, '2020-12-07'),
(3, 1, 3, 0, 6, '2020-12-07'),
(4, 1, 3, 3, 6, '2020-12-07'),
(5, 1, 3, 2, 6, '2020-12-07'),
(6, 1, 4, 3, 3, '2020-12-07'),
(7, 1, 4, 0, 3, '2020-12-07'),
(8, 1, 6, 3, 3, '2020-12-07'),
(9, 1, 6, 1, 3, '2020-12-07'),
(10, 2, 1, 4, 4, '2020-12-07'),
(11, 2, 1, 0, 4, '2020-12-07'),
(12, 2, 3, 2, 6, '2020-12-07'),
(13, 2, 6, 2, 3, '2020-12-07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'Horia', '123'),
(2, 'John', '1234');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Answers`
--
ALTER TABLE `Answers`
  ADD PRIMARY KEY (`Answer_ID`);

--
-- Indexes for table `Question`
--
ALTER TABLE `Question`
  ADD PRIMARY KEY (`Question_ID`);

--
-- Indexes for table `Question_Answers`
--
ALTER TABLE `Question_Answers`
  ADD PRIMARY KEY (`Answer_ID`,`Question_ID`),
  ADD KEY `Question_ID` (`Question_ID`);

--
-- Indexes for table `Quiz`
--
ALTER TABLE `Quiz`
  ADD PRIMARY KEY (`Quiz_ID`),
  ADD KEY `Quiz_Author_ID` (`Quiz_Author_ID`);

--
-- Indexes for table `Quiz_Deleted_Log`
--
ALTER TABLE `Quiz_Deleted_Log`
  ADD PRIMARY KEY (`Log_ID`);

--
-- Indexes for table `Quiz_Question`
--
ALTER TABLE `Quiz_Question`
  ADD PRIMARY KEY (`Question_ID`,`Quiz_ID`),
  ADD KEY `Quiz_ID` (`Quiz_ID`);

--
-- Indexes for table `Quiz_Student`
--
ALTER TABLE `Quiz_Student`
  ADD PRIMARY KEY (`Attempt_ID`,`Student_ID`,`Quiz_ID`),
  ADD KEY `Student_ID` (`Student_ID`),
  ADD KEY `Quiz_ID` (`Quiz_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Question_Answers`
--
ALTER TABLE `Question_Answers`
  MODIFY `Answer_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT for table `Quiz_Deleted_Log`
--
ALTER TABLE `Quiz_Deleted_Log`
  MODIFY `Log_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Quiz_Question`
--
ALTER TABLE `Quiz_Question`
  MODIFY `Question_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `Quiz_Student`
--
ALTER TABLE `Quiz_Student`
  MODIFY `Attempt_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Answers`
--
ALTER TABLE `Answers`
  ADD CONSTRAINT `Answers_ibfk_1` FOREIGN KEY (`Answer_ID`) REFERENCES `Question_Answers` (`Answer_ID`) ON DELETE CASCADE;

--
-- Constraints for table `Question`
--
ALTER TABLE `Question`
  ADD CONSTRAINT `Question_ibfk_1` FOREIGN KEY (`Question_ID`) REFERENCES `Quiz_Question` (`Question_ID`) ON DELETE CASCADE;

--
-- Constraints for table `Question_Answers`
--
ALTER TABLE `Question_Answers`
  ADD CONSTRAINT `Question_Answers_ibfk_1` FOREIGN KEY (`Question_ID`) REFERENCES `Quiz_Question` (`Question_ID`) ON DELETE CASCADE;

--
-- Constraints for table `Quiz`
--
ALTER TABLE `Quiz`
  ADD CONSTRAINT `Quiz_ibfk_1` FOREIGN KEY (`Quiz_Author_ID`) REFERENCES `users` (`id`);

--
-- Constraints for table `Quiz_Question`
--
ALTER TABLE `Quiz_Question`
  ADD CONSTRAINT `Quiz_Question_ibfk_1` FOREIGN KEY (`Quiz_ID`) REFERENCES `Quiz` (`Quiz_ID`) ON DELETE CASCADE;

--
-- Constraints for table `Quiz_Student`
--
ALTER TABLE `Quiz_Student`
  ADD CONSTRAINT `Quiz_Student_ibfk_1` FOREIGN KEY (`Student_ID`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `Quiz_Student_ibfk_2` FOREIGN KEY (`Quiz_ID`) REFERENCES `Quiz` (`Quiz_ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
