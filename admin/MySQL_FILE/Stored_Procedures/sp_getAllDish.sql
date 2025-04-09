DROP PROCEDURE IF EXISTS `sp_getAllDish`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getAllDish`()
BEGIN
    SELECT dish.*,
           category.category_name,
           category.status as category_status
    FROM dish
             INNER JOIN category ON dish.category_id = category.id
    ORDER BY dish.id ASC;
END