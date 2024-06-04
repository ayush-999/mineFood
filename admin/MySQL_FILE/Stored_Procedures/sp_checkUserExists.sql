DROP PROCEDURE IF EXISTS `sp_checkCategoryExists`;

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_checkUserExists`(
	IN `userName` VARCHAR(50)
)
BEGIN
	 SELECT COUNT(*) FROM user WHERE name = userName;
END