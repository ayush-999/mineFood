DROP PROCEDURE IF EXISTS `sp_getSettings`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getSettings`()
BEGIN
	SELECT * FROM setting;
END