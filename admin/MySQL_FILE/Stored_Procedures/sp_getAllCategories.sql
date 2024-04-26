DROP PROCEDURE IF EXISTS `sp_getAllCategories`;

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getAllCategories`()
BEGIN
	SELECT * FROM category
    ORDER BY order_number;
END