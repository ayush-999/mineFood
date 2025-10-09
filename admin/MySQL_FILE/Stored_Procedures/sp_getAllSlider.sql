DROP PROCEDURE IF EXISTS `sp_getAllSlider`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getAllSlider`()
BEGIN
	SELECT * FROM slider order by order_number asc;
END