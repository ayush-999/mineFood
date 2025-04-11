DROP PROCEDURE IF EXISTS `sp_updateDish`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateDish`(IN `dishId` INT, IN `dishCategoryId` INT, IN `dishName` VARCHAR(100), IN `dishDetail` TEXT, IN `dishImage` VARCHAR(255), IN `dishType` ENUM('veg','non-veg'), IN `status` INT, IN `addedOn` DATETIME)
BEGIN
    -- Check for existing dish by name, excluding current record
    IF
(
SELECT COUNT(*)
FROM dish
WHERE (dish_name = dishName)
  AND id != dishId) > 0 THEN
        SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Dish already exists';
ELSE
        -- Update Dish
UPDATE dish
SET category_id = dishCategoryId,
    dish_name   = dishName,
    dish_detail = dishDetail,
    image       = dishImage,
    type        = dishType,
    status      = status,
    added_on    = addedOn
WHERE id = dishId;
END IF;
END