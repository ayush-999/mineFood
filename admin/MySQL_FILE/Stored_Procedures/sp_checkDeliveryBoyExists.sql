DROP PROCEDURE IF EXISTS `sp_checkDeliveryBoyExists`;

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_checkDeliveryBoyExists`(
	IN `deliveryBoyName` VARCHAR(50)
)
BEGIN
	 SELECT COUNT(*) FROM delivery_boy WHERE name = deliveryBoyName;
END