DROP PROCEDURE IF EXISTS `sp_updateDeliveryBoy`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateDeliveryBoy`(
	IN `deliveryBoyId` INT,
    IN `deliveryBoyName` VARCHAR(50),
    IN `deliveryBoyMobile` VARCHAR(15),
    IN `deliveryBoyEmail` VARCHAR(50),
    IN `status` INT,
	IN `addedOn` datetime
)
BEGIN
	UPDATE delivery_boy
    SET name = deliveryBoyName, mobile = deliveryBoyMobile, email = deliveryBoyEmail, status = status, added_on = addedOn
    WHERE id = deliveryBoyId;
END