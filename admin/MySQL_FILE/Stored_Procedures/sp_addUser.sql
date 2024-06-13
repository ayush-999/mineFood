DROP PROCEDURE IF EXISTS `sp_addUser`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addUser`(
	IN `userName` VARCHAR(50),
	IN `userMobile` VARCHAR(15),
	IN `userEmail` VARCHAR(50),
    IN `status` INT,
    IN `addedOn` datetime
)
BEGIN
    -- Check for existing delivery boy by mobile or email
    IF (SELECT COUNT(*) FROM user WHERE mobile = userMobile OR email = userEmail) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'User with this mobile or email already exists';
    ELSE
        -- Insert new Delivery Boy
        INSERT INTO user (name, mobile, email, status, added_on)
     VALUES (userName, userMobile, userEmail, status, addedOn);
    END IF;
END