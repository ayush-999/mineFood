DROP PROCEDURE IF EXISTS `sp_updateAdmin`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateAdmin`(
	IN `p_adminId` INT,
    IN `p_name` VARCHAR(255),
    IN `p_username` VARCHAR(255),
    IN `p_password` VARCHAR(255),
    IN `p_email` VARCHAR(255),
    IN `p_mobile` VARCHAR(255),
    IN `addedOn` datetime,
    IN `p_address` VARCHAR(255),
    IN `p_profileImg` VARCHAR(255)
)
BEGIN
	UPDATE admin
    SET 
    name = p_name, 
    username = p_username, 
    password = p_password, 
    email = p_email, 
    mobile_no = p_mobile,
    added_on = addedOn,
    address = p_address,
    admin_img = p_profileImg
    WHERE id = p_adminId;
END