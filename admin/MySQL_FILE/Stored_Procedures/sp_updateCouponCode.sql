DROP PROCEDURE IF EXISTS `sp_updateCouponCode`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateCouponCode`(
    IN `couponCodeId` INT,
    IN `couponCodeName` VARCHAR(10),
    IN `couponType` ENUM('P','F'),
    IN `couponValue` INT,
    IN `cartMinValue` INT,
    IN `startDate` date,
    IN `expDate` date,
    IN `status` INT,
    IN `bgColor` VARCHAR(50),
    IN `txtColor` VARCHAR(50)
)
BEGIN
    -- Check for existing coupon_code by coupon_name, excluding current record
    IF (SELECT COUNT(*) FROM coupon_code WHERE (coupon_name = couponCodeName) AND id != couponCodeId) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Coupon already exists';
    ELSE
        -- Update coupon_code
        UPDATE coupon_code
        SET coupon_name = couponCodeName, 
        coupon_type = couponType, 
        coupon_value = couponValue, 
        cart_min_value = cartMinValue, 
        started_on = startDate, 
        expired_on = expDate, 
        status = status, 
        bg_color = bgColor, 
        txt_color = txtColor
        WHERE id = couponCodeId;
    END IF;
END
