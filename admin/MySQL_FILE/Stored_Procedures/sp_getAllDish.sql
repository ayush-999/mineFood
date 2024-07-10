DROP PROCEDURE IF EXISTS `sp_getAllDish`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getAllDish`()
BEGIN
    SELECT dish.*,
           category.category_name,
           category.status as category_status
    FROM dish, category
    where dish.category_id = category.id
    order by dish.id asc;
END