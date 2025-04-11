DROP PROCEDURE IF EXISTS `sp_updateBanner`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateBanner`(
    IN `bannerId` INT,
    IN `bannerImageName` VARCHAR(100),
    IN `bannerHeading` VARCHAR(500),
    IN `bannerSubHeading` VARCHAR(500),
    IN `bannerLink` VARCHAR(100),
    IN `bannerLinkText` VARCHAR(100),
    IN `bannerOrderNumber` INT,
    IN `bannerAddedOn` DATETIME,
    IN `bannerStatus` INT)
BEGIN
    -- Check for existing banner with the same order number, excluding current record
    IF (SELECT COUNT(*) FROM banner WHERE order_number = bannerOrderNumber AND id != bannerId) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Banner with this order number already exists';
    ELSE
        -- Update banner
        UPDATE banner
        SET 
            image = bannerImageName,
            heading = bannerHeading,
            sub_heading = bannerSubHeading,
            link = bannerLink,
            link_txt = bannerLinkText,
            order_number = bannerOrderNumber,
            added_on = bannerAddedOn,
            status = bannerStatus
        WHERE id = bannerId;
    END IF;
END