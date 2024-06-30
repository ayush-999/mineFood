DROP PROCEDURE IF EXISTS `sp_updateUser`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateUser`(
	IN `userId` INT,
    IN `userName` VARCHAR(255),
    IN `userMobile` VARCHAR(255),
    IN `userEmail` VARCHAR(255),
    IN `status` INT,
	IN `addedOn` datetime
)
BEGIN
    -- Check for existing delivery boy by mobile or email, excluding current record
    IF (SELECT COUNT(*) FROM user WHERE (mobile = userMobile OR email = userEmail) AND id != userId) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'User with this mobile or email already exists';
    ELSE
        -- Update Delivery Boy
        UPDATE user
		SET name = userName, mobile = userMobile, email = userEmail, status = status, added_on = addedOn
		WHERE id = userId;
    END IF;
END