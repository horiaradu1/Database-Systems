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