DROP PROCEDURE IF EXISTS `sp_getAllDeliveryBoy`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getAllDeliveryBoy`()
BEGIN
	SELECT * FROM delivery_boy;
END