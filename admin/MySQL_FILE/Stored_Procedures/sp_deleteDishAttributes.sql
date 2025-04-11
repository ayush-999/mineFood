DROP PROCEDURE IF EXISTS `sp_deleteDishAttributes`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleteDishAttributes`(IN `dishId` INT)
BEGIN
    -- Delete all attributes for the given dish ID
    DELETE FROM dish_details WHERE dish_id = dishId;
END