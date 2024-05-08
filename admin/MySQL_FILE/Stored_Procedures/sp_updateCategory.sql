DROP PROCEDURE IF EXISTS `sp_updateCategory`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateCategory`(
	IN categoryId INT,
    IN categoryName VARCHAR(255),
    IN orderNumber INT,
    IN status INT,
	IN addedOn datetime
)
BEGIN
	UPDATE category
    SET category_name = categoryName, order_number = orderNumber, status = status, added_on = addedOn
    WHERE id = categoryId;
END
