DROP PROCEDURE IF EXISTS `sp_getAllCouponCode`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getAllCouponCode`()
BEGIN
	SELECT * FROM coupon_code;
END