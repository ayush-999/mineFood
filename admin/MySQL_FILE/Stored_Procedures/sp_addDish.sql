DROP PROCEDURE IF EXISTS `sp_addDish`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addDish`(
    IN `dishCategoryId` INT,
    IN `dishName` VARCHAR(100),
    IN `dishDetail` TEXT,
    IN `dishImage` VARCHAR(255),
    IN `dishType` ENUM ('veg','non-veg'),
    IN `status` INT,
    IN `addedOn` DATETIME,
    OUT `newDishId` INT
)
BEGIN
    -- Check for existing dish by name
    IF (SELECT COUNT(*) FROM dish WHERE dish_name = dishName) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Dish already exists';
    ELSE
        -- Insert new Dish
        INSERT INTO dish (category_id, dish_name, dish_detail, image, type, status, added_on)
        VALUES (dishCategoryId, dishName, dishDetail, dishImage, dishType, status, addedOn);
        
        -- Get the last inserted dish ID
        SET newDishId = LAST_INSERT_ID();
    END IF;
END