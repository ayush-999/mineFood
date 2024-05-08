DROP PROCEDURE IF EXISTS `sp_getAdminDetails`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getAdminDetails`()
BEGIN
	SELECT *
    FROM admin
    ORDER BY username;
END