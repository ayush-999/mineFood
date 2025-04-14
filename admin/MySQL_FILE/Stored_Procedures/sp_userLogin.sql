DROP PROCEDURE IF EXISTS `sp_userLogin`;

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_userLogin`(
    IN `p_username` VARCHAR(255)
)
BEGIN
    SELECT 
        id,
        name,
        username,
        password,  -- This is the hashed password
        email,
        mobile_no,
        address,
        admin_img,
        added_on
    FROM admin 
    WHERE username = p_username;
END