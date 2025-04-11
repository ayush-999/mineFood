DROP PROCEDURE IF EXISTS `sp_addBanner`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addBanner`(
    IN `bannerImageName` VARCHAR(100),
    IN `bannerHeading` VARCHAR(500),
    IN `bannerSubHeading` VARCHAR(500),
    IN `bannerLink` VARCHAR(100),
    IN `bannerLinkText` VARCHAR(100),
    IN `bannerOrderNumber` INT,
    IN `bannerAddedOn` DATETIME,
    IN `bannerStatus` INT)
BEGIN
    -- Check for existing banner with the same order number
    IF (SELECT COUNT(*) FROM banner WHERE order_number = bannerOrderNumber) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Banner with this order number already exists';
    ELSE
        -- Insert new banner
        INSERT INTO banner (
            image,
            heading, 
            sub_heading, 
            link, 
            link_txt, 
            order_number, 
            added_on,
            status
        ) VALUES (
            bannerImageName,
            bannerHeading, 
            bannerSubHeading, 
            bannerLink, 
            bannerLinkText, 
            bannerOrderNumber, 
            bannerAddedOn, 
            bannerStatus
        );
    END IF;
END