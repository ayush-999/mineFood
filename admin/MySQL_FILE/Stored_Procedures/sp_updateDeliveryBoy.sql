DROP PROCEDURE IF EXISTS `sp_updateDeliveryBoy`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateDeliveryBoy`(
    IN `deliveryBoyId` INT,
    IN `deliveryBoyName` VARCHAR(255),
    IN `deliveryBoyMobile` VARCHAR(15),
    IN `deliveryBoyEmail` VARCHAR(255),
    IN `status` INT,
    IN `addedOn` DATETIME
)
BEGIN
    -- Check for existing delivery boy by mobile or email, excluding current record
    IF (SELECT COUNT(*) FROM delivery_boy WHERE (mobile = deliveryBoyMobile OR email = deliveryBoyEmail) AND id != deliveryBoyId) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Delivery boy with this mobile or email already exists';
    ELSE
        -- Update Delivery Boy
        UPDATE delivery_boy
        SET name = deliveryBoyName, mobile = deliveryBoyMobile, email = deliveryBoyEmail, status = status, added_on = addedOn
        WHERE id = deliveryBoyId;
    END IF;
END
