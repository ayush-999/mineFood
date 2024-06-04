DROP PROCEDURE IF EXISTS `sp_deleteCategory`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleteUser`(
    IN `userId` INT
)
BEGIN
	DELETE FROM user
    WHERE id = userId;
END