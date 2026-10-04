DELIMITER $$

CREATE TRIGGER trg_student_scores_before_insert
BEFORE INSERT ON student_scores
FOR EACH ROW
BEGIN
    IF NEW.is_absent = 'PRESENT' THEN
        SET NEW.F01 = 0;
    ELSEIF NEW.is_absent = 'ABSENT' THEN
        SET NEW.F01 = NULL;
        SET NEW.F02 = NULL;
        SET NEW.F03 = NULL;
    END IF;
END$$

CREATE TRIGGER trg_student_scores_before_update
BEFORE UPDATE ON student_scores
FOR EACH ROW
BEGIN
    IF NEW.is_absent = 'PRESENT' THEN
        SET NEW.F01 = 0;
    ELSEIF NEW.is_absent = 'ABSENT' THEN
        SET NEW.F01 = NULL;
        SET NEW.F02 = NULL;
        SET NEW.F03 = NULL;
    END IF;
END$$

DELIMITER ;
