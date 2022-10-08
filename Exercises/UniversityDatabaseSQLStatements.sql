CREATE TABLE `student`
(
  `ID` int PRIMARY KEY,
  `name` varchar(255),
  `dept_name` varchar(100),
  `tot_cred` smallint
);

CREATE TABLE `takes`
(
  `ID` int NOT NULL,
  `course_id` varchar(255) NOT NULL,
  `sec_id` int NOT NULL,
  `semester` varchar(100) NOT NULL,
  `year` int NOT NULL,
  `grade` varchar(100)
);

CREATE TABLE `section`
(
  `course_id` varchar(255) NOT NULL,
  `sec_id` int NOT NULL,
  `semester` varchar(100) NOT NULL,
  `year` int NOT NULL,
  `building` varchar(255),
  `room_no` int,
  `time_slot_id` varchar(255),
  PRIMARY KEY (`course_id`, `sec_id`, `semester`, `year`)
);

CREATE TABLE `time_slot`
(
  `time_slot_id` varchar(255) NOT NULL,
  `day` varchar(100) NOT NULL,
  `start_hour` int NOT NULL,
  `start_min` int NOT NULL,
  `end_hour` int,
  `end_min` int,
  PRIMARY KEY (`time_slot_id`, `day`, `start_hour`, `start_min`)
);

CREATE TABLE `classroom`
(
  `building` varchar(255),
  `room_no` int,
  `capacity` int,
  PRIMARY KEY (`building`, `room_no`)
);

CREATE TABLE `course`
(
  `course_id` varchar(255) PRIMARY KEY,
  `title` varchar(255),
  `dept_name` varchar(100),
  `credits` int
);

CREATE TABLE `prereq`
(
  `course_id` varchar(255) NOT NULL,
  `prereq_id` varchar(255) NOT NULL
);

CREATE TABLE `department`
(
  `dept_name` varchar(100) PRIMARY KEY,
  `building` varchar(255),
  `budget` int
);

CREATE TABLE `instructor`
(
  `ID` int PRIMARY KEY,
  `name` varchar(255),
  `dept_name` varchar(100),
  `salary` int
);

CREATE TABLE `teaches`
(
  `ID` int NOT NULL,
  `course_id` varchar(255) NOT NULL,
  `sec_id` int NOT NULL,
  `semester` varchar(100) NOT NULL,
  `year` int NOT NULL,
  PRIMARY KEY (`ID`, `course_id`, `sec_id`, `semester`, `year`)
);

CREATE TABLE `advisor`
(
  `s_id` int NOT NULL,
  `i_id` int NOT NULL
);


ALTER TABLE `takes` ADD
    FOREIGN KEY (`ID`)
    REFERENCES `student` (`ID`);

ALTER TABLE `takes` ADD
    FOREIGN KEY (`course_id`, `sec_id`, `semester`, `year`)
    REFERENCES `section` (`course_id`, `sec_id`, `semester`, `year`);

ALTER TABLE `section` ADD
    FOREIGN KEY (`building`, `room_no`)
    REFERENCES `classroom` (`building`, `room_no`);

ALTER TABLE `section` ADD
    FOREIGN KEY (`time_slot_id`)
    REFERENCES `time_slot` (`time_slot_id`);

ALTER TABLE `course` ADD 
    FOREIGN KEY (`dept_name`)
    REFERENCES `department` (`dept_name`);

ALTER TABLE `prereq` ADD
    FOREIGN KEY (`course_id`)
    REFERENCES `course` (`course_id`);

ALTER TABLE `prereq` ADD
    FOREIGN KEY (`prereq_id`)
    REFERENCES `course` (`course_id`);

ALTER TABLE `teaches` ADD
    FOREIGN KEY (`course_id`, `sec_id`, `semester`, `year`)
    REFERENCES `section` (`course_id`, `sec_id`, `semester`, `year`);

ALTER TABLE `teaches` ADD
    FOREIGN KEY (`ID`)
    REFERENCES `instructor` (`ID`);

ALTER TABLE `instructor` ADD
    FOREIGN KEY (`dept_name`)
    REFERENCES `department` (`dept_name`);

ALTER TABLE `advisor` ADD
    FOREIGN KEY (`s_id`)
    REFERENCES `student` (`ID`);

ALTER TABLE `advisor` ADD
    FOREIGN KEY (`i_id`)
    REFERENCES `instructor` (`ID`);