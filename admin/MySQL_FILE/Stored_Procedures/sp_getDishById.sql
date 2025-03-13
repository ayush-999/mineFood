DROP PROCEDURE IF EXISTS `sp_getDishById`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getDishById`(IN `dishId` INT)
BEGIN
    -- Fetch main dish details
    SELECT 
        dish.*,
        category.category_name,
        category.status AS category_status
    FROM 
        dish
    INNER JOIN 
        category 
    ON 
        dish.category_id = category.id
    WHERE 
        dish.id = dishId;

    -- Fetch associated dish details (attributes and prices)
    SELECT 
        dish_details.attribute,
        dish_details.price
    FROM 
        dish_details
    WHERE 
        dish_details.dish_id = dishId;
END