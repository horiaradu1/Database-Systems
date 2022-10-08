/* sudo /opt/lampp/bin/mysql -u root
*/

DROP DATABASE IF EXISTS `quiz_db`;
CREATE DATABASE `quiz_db`;
USE `quiz_db`;

CREATE TABLE `users` (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `username` varchar(255) NOT NULL UNIQUE,
    `password` varchar(255) NOT NULL
);

CREATE TABLE `Quiz` (
    `Quiz_ID` int PRIMARY KEY,
    `Quiz_Name` varchar(255) NOT NULL,
    `Quiz_Author_ID` int NOT NULL,
    `Quiz_Available` boolean NOT NULL,
    `Quiz_Duration` int NOT NULL
);

CREATE TABLE `Quiz_Student` (
    `Attempt_ID` int AUTO_INCREMENT,
    `Student_ID` int NOT NULL,
    `Quiz_ID` int NOT NULL,
    `Score` int NOT NULL,
    `Max_Score` int NOT NULL,
    `Date_of_Attempt` date NOT NULL,
    PRIMARY KEY (`Attempt_ID`, `Student_ID`, `Quiz_ID`)
);

CREATE TABLE `Question` (
    `Question_ID` int PRIMARY KEY NOT NULL,
    `Question_String` varchar(255) NOT NULL
);

CREATE TABLE `Quiz_Question` (
    `Question_ID` int AUTO_INCREMENT,
    `Quiz_ID` int NOT NULL,
    PRIMARY KEY (`Question_ID`, `Quiz_ID`)
);

CREATE TABLE `Answers` (
    `Answer_ID` int PRIMARY KEY NOT NULL,
    `Answer_String` varchar(255) NOT NULL
);

CREATE TABLE `Question_Answers` (
    `Answer_ID` int AUTO_INCREMENT,
    `Question_ID` int NOT NULL,
    `Is_Correct` boolean NOT NULL,
    PRIMARY KEY (`Answer_ID`, `Question_ID`)
);

CREATE TABLE `Quiz_Deleted_Log` (
    `Log_ID` int PRIMARY KEY AUTO_INCREMENT,
    `Staff_ID` int NOT NULL,
    `Quiz_ID` int NOT NULL,
    `Date_Time` datetime NOT NULL
);

ALTER TABLE `Quiz` ADD FOREIGN KEY (`Quiz_Author_ID`) REFERENCES `users` (`id`);
ALTER TABLE `Quiz_Student` ADD FOREIGN KEY (`Student_ID`) REFERENCES `users` (`id`);

ALTER TABLE `Quiz_Student` ADD FOREIGN KEY (`Quiz_ID`) REFERENCES `Quiz` (`Quiz_ID`) ON DELETE CASCADE;

ALTER TABLE `Question` ADD FOREIGN KEY (`Question_ID`) REFERENCES `Quiz_Question` (`Question_ID`) ON DELETE CASCADE;
ALTER TABLE `Quiz_Question` ADD FOREIGN KEY (`Quiz_ID`) REFERENCES `Quiz` (`Quiz_ID`) ON DELETE CASCADE;

ALTER TABLE `Answers` ADD FOREIGN KEY (`Answer_ID`) REFERENCES `Question_Answers` (`Answer_ID`) ON DELETE CASCADE;
ALTER TABLE `Question_Answers` ADD FOREIGN KEY (`Question_ID`) REFERENCES `Quiz_Question` (`Question_ID`) ON DELETE CASCADE;


DROP TRIGGER IF EXISTS quizdeleting;
DELIMITER //
CREATE TRIGGER quizdeleting
AFTER DELETE
    ON
        Quiz FOR EACH ROW
BEGIN
    INSERT INTO Quiz_Deleted_Log (Staff_ID, Quiz_ID, Date_Time) VALUES (@staff, OLD.Quiz_ID, CURRENT_TIMESTAMP);
END; //
DELIMITER ;

DROP PROCEDURE IF EXISTS failingstudents;
DELIMITER //
CREATE PROCEDURE failingstudents
()
BEGIN
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
END; //
DELIMITER ;