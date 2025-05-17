DROP PROCEDURE IF EXISTS `sp_updateAdmin`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateAdmin`(
    IN `p_adminId` INT,
    IN `p_name` VARCHAR(50),
    IN `p_username` VARCHAR(50),
    IN `p_password` VARCHAR(255),
    IN `p_email` VARCHAR(50),
    IN `p_mobile` VARCHAR(15),
    IN `addedOn` datetime,
    IN `p_area` VARCHAR(255),
    IN `p_state` VARCHAR(100),
    IN `p_district` VARCHAR(100),
    IN `p_pincode` INT,
    IN `p_city` VARCHAR(100),
    IN `p_country` VARCHAR(100),
    IN `p_address` VARCHAR(255),
    IN `p_profileImg` VARCHAR(255),
    IN `p_contactEmail` VARCHAR(50),
    IN `p_contactPhone` VARCHAR(15),
    IN `p_opening_hours` TEXT
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
        area = p_area,
        state = p_state,
        district = p_district,
        pincode = p_pincode,
        city = p_city,
        country = p_country,
        address = p_address,
        admin_img = p_profileImg,
        contact_email = p_contactEmail,
        contact_phone = p_contactPhone,
        opening_hours = p_opening_hours
    WHERE id = p_adminId;
    
    SELECT ROW_COUNT() AS rows_affected;
END