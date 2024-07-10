DROP PROCEDURE IF EXISTS `sp_getDishById`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getDishById`(IN dishId INT)
BEGIN
SELECT dish.*,
       category.category_name,
       category.status as category_status
FROM dish
         JOIN category ON dish.category_id = category.id
WHERE dish.id = dishId;
END