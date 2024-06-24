DROP PROCEDURE IF EXISTS `sp_addCouponCode`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addCouponCode`(
    IN `couponCodeName` VARCHAR(10),
    IN `couponType` ENUM('P','F'),
    IN `couponValue` INT,
    IN `cartMinValue` INT,
    IN `expDate` date,
    IN `startDate` date,
    IN `status` INT,
    IN `bgColor` VARCHAR(50),
    IN `txtColor` VARCHAR(50),
    IN `addedOn` DATETIME
)
BEGIN
    -- Check for existing Coupon code by coupon_code
    IF (SELECT COUNT(*) FROM coupon_code WHERE coupon_name = couponCodeName) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Coupon already exists';
    ELSE
        -- Insert new Coupon code
        INSERT INTO coupon_code (coupon_name, coupon_type, coupon_value, cart_min_value, started_on, expired_on, status, bg_color, txt_color, added_on)
        VALUES (couponCodeName, couponType, couponValue, cartMinValue, startDate, expDate, status, bgColor, txtColor, addedOn);
    END IF;
END