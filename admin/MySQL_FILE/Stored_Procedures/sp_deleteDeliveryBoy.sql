DROP PROCEDURE IF EXISTS `sp_deleteDeliveryBoy`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleteDeliveryBoy`(
    IN `deliveryBoyId` INT
)
BEGIN
	DELETE FROM delivery_boy
    WHERE id = deliveryBoyId;
END