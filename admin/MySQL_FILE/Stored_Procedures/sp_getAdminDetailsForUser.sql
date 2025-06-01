DROP PROCEDURE IF EXISTS `sp_getAdminDetailsForUser`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getAdminDetailsForUser`()
BEGIN
    SELECT 
        a.id, 
        a.name,   
        a.added_on, 
        a.area, 
        a.state, 
        a.district, 
        a.pincode, 
        a.city, 
        a.country, 
        a.address, 
        a.admin_img, 
        a.contact_email, 
        a.contact_phone, 
        a.opening_hours,
        GROUP_CONCAT(CONCAT('{"title":"', sm.title, '","url":"', sm.url, '","icon":"', sm.icon, '"}') SEPARATOR ',') AS social_links
    FROM admin a
    LEFT JOIN social_media sm ON a.id = sm.admin_id
    WHERE a.id = 1  -- Assuming you want the admin with ID 1
    GROUP BY a.id;
END