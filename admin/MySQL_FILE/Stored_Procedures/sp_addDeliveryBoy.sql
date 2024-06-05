DROP PROCEDURE IF EXISTS `sp_addDeliveryBoy`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addDeliveryBoy`(
	IN `deliveryBoyName` VARCHAR(50),
	IN `deliveryBoyMobile` VARCHAR(15),
	IN `deliveryBoyEmail` VARCHAR(50),
    IN `status` INT,
    IN `addedOn` datetime
)
BEGIN
	INSERT INTO delivery_boy (name, mobile, email, status, added_on)
     VALUES (deliveryBoyName, deliveryBoyMobile, deliveryBoyEmail, status, addedOn);
END