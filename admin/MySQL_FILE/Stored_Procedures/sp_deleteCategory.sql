DROP PROCEDURE IF EXISTS `sp_deleteCategory`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleteCategory`(
IN categoryId INT
)
BEGIN
	DELETE FROM category
    WHERE id = categoryId;
END