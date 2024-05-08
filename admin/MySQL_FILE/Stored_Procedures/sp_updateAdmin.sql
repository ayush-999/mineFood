DROP PROCEDURE IF EXISTS `sp_updateAdmin`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateAdmin`(
	IN `p_adminId` INT,
    IN `p_username` VARCHAR(255),
    IN `p_password` VARCHAR(255),
    IN `p_email` VARCHAR(255)
)
BEGIN
	UPDATE admin
    SET username = p_username, password = p_password, email = p_email
    WHERE id = p_adminId;
END