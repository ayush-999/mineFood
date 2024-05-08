DROP PROCEDURE IF EXISTS `sp_addCategory`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addCategory`(
	IN categoryName VARCHAR(255),
    IN orderNumber INT,
    IN status INT,
    IN addedOn datetime
)
BEGIN
	INSERT INTO category (category_name, order_number, status, added_on)
     VALUES (categoryName, orderNumber, status, addedOn);
END