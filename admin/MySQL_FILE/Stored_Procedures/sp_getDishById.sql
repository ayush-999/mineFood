DROP PROCEDURE IF EXISTS `sp_getDishById`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getDishById`(IN `dishId` INT)
BEGIN
    -- Fetch main dish details
    SELECT 
        dish.*,
        category.category_name,
        category.status AS category_status,
        dish_details.attribute As attribute,
        dish_details.price As price
    FROM 
        dish
    INNER JOIN 
        category 
    ON 
        dish.category_id = category.id
	INNER JOIN
		dish_details
	ON 
		dish.id = dish_details.dish_id
    WHERE 
        dish.id = dishId;
END