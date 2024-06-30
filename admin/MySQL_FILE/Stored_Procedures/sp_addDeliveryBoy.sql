DROP PROCEDURE IF EXISTS `sp_addDeliveryBoy`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addDeliveryBoy`(
    IN `deliveryBoyName` VARCHAR(255),
    IN `deliveryBoyMobile` VARCHAR(15),
    IN `deliveryBoyEmail` VARCHAR(255),
    IN `status` INT,
    IN `addedOn` DATETIME
)
BEGIN
    -- Check for existing delivery boy by mobile or email
    IF (SELECT COUNT(*) FROM delivery_boy WHERE mobile = deliveryBoyMobile OR email = deliveryBoyEmail) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Delivery boy with this mobile or email already exists';
    ELSE
        -- Insert new Delivery Boy
        INSERT INTO delivery_boy (name, mobile, email, status, added_on)
        VALUES (deliveryBoyName, deliveryBoyMobile, deliveryBoyEmail, status, addedOn);
    END IF;
END