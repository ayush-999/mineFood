DROP PROCEDURE IF EXISTS `sp_deleteSlider`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleteSlider`(
	IN `sliderId` INT
)
BEGIN
	DELETE FROM slider
    WHERE id = sliderId;
END