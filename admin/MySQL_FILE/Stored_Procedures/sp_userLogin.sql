DROP PROCEDURE IF EXISTS `sp_userLogin`;

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_userLogin`(
    IN `p_username` VARCHAR(255), 
    IN `p_password` VARCHAR(255)
)
BEGIN
    SELECT username, password 
    FROM admin 
    WHERE username = p_username AND password = p_password;
END