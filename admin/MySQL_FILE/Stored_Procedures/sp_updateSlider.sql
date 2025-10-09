DROP PROCEDURE IF EXISTS `sp_updateSlider`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateSlider`(
    IN `sliderId` INT,
    IN `sliderImageName` VARCHAR(100),
    IN `sliderHeading` VARCHAR(500),
    IN `sliderSubHeading` VARCHAR(500),
    IN `sliderLink` VARCHAR(100),
    IN `sliderLinkText` VARCHAR(100),
    IN `sliderOrderNumber` INT,
    IN `sliderAddedOn` DATETIME,
    IN `sliderStatus` INT)
BEGIN
    -- Check for existing slider with the same order number, excluding current record
    IF (SELECT COUNT(*) FROM slider WHERE order_number = sliderOrderNumber AND id != sliderId) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Slider with this order number already exists';
    ELSE
        -- Update slider
        UPDATE slider
        SET 
            image = sliderImageName,
            heading = sliderHeading,
            sub_heading = sliderSubHeading,
            link = sliderLink,
            link_txt = sliderLinkText,
            order_number = sliderOrderNumber,
            added_on = sliderAddedOn,
            status = sliderStatus
        WHERE id = sliderId;
    END IF;
END