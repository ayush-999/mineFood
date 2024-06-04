DROP PROCEDURE IF EXISTS `sp_updateUser`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateUser`(
	IN `userId` INT,
    IN `userName` VARCHAR(50),
    IN `userMobile` VARCHAR(15),
    IN `userEmail` VARCHAR(15),
    IN `status` INT,
	IN `addedOn` datetime
)
BEGIN
	UPDATE user
    SET name = userName, mobile = userMobile, email = userEmail, status = status, added_on = addedOn
    WHERE id = userId;
END