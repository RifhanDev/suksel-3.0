use etender;
DELIMITER //
CREATE TRIGGER `tr_refund_number_increment` BEFORE INSERT ON `refunds` FOR EACH ROW BEGIN
    IF (
        SELECT COUNT(*) FROM refund_reference_numbers  
            WHERE year=YEAR(CURDATE())
    ) = 0
    THEN 
        INSERT INTO refund_reference_numbers (year, `number`, created_at) 
            VALUES (YEAR(CURDATE()), 1, NOW());
        SET NEW.number =  CONCAT(
            YEAR(CURDATE()),
            '-', 
            (SELECT LPAD(`number`, 9, 0) FROM refund_reference_numbers 
                WHERE year=YEAR(CURDATE())
            )
        );
    ELSE
        UPDATE refund_reference_numbers 
            SET `number` = `number` + 1 
            WHERE year=YEAR(CURDATE());

        SET NEW.number = CONCAT(
            YEAR(CURDATE()),
            '-', 
            (SELECT LPAD(`number`, 9, 0) FROM refund_reference_numbers 
                WHERE year=YEAR(CURDATE())
            )
        );
    END IF;
END
//
DELIMITER ;