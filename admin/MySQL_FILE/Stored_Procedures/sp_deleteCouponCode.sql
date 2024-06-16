DROP PROCEDURE IF EXISTS `sp_deleteCouponCode`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleteCouponCode`(
    IN `couponCodeId` INT
)
BEGIN
	DELETE FROM coupon_code
    WHERE id = couponCodeId;
END