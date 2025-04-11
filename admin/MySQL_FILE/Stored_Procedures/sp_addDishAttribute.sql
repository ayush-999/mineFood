DROP PROCEDURE IF EXISTS `sp_addDishAttribute`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addDishAttribute`(IN `dishId` INT, IN `attribute` VARCHAR(100), IN `price` DECIMAL(10,2), IN `addedOn` DATETIME)
BEGIN
    INSERT INTO dish_details(dish_id, attribute, price, added_on)
    VALUES (dishId, attribute, price, addedOn);
END