DROP PROCEDURE IF EXISTS `sp_getAllUsers`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getAllUsers`()
BEGIN
	SELECT * FROM user;
END