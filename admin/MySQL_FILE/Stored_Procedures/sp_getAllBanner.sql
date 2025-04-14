DROP PROCEDURE IF EXISTS `sp_getAllBanner`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getAllBanner`()
BEGIN
	SELECT * FROM banner order by order_number asc;
END