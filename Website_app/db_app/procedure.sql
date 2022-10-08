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