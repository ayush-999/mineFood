DROP PROCEDURE IF EXISTS `sp_deleteBanner`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleteBanner`(
	IN `bannerId` INT
)
BEGIN
	DELETE FROM banner
    WHERE id = bannerId;
END