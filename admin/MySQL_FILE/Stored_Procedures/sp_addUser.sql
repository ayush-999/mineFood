DROP PROCEDURE IF EXISTS `sp_addUser`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addUser`(
	IN `userName` VARCHAR(50),
	IN `userMobile` VARCHAR(15),
	IN `userEmail` VARCHAR(50),
    IN `status` INT,
    IN `addedOn` datetime
)
BEGIN
	INSERT INTO user (name, mobile, email, status, added_on)
     VALUES (userName, userMobile, userEmail, status, addedOn);
END