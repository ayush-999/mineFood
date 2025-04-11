DROP PROCEDURE IF EXISTS `sp_deleteDish`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleteDish`(IN `dishId` INT)
BEGIN
	DELETE FROM dish
    WHERE id = dishId;
END