DROP PROCEDURE IF EXISTS `sp_checkCategoryExists`;

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_checkCategoryExists`(
	IN categoryName VARCHAR(255)
)
BEGIN
	 SELECT COUNT(*) FROM category WHERE category_name = categoryName;
END