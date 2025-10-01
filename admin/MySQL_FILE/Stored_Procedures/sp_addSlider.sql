DROP PROCEDURE IF EXISTS `sp_addSlider`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addSlider`(
    IN `sliderImageName` VARCHAR(100),
    IN `sliderHeading` VARCHAR(500),
    IN `sliderSubHeading` VARCHAR(500),
    IN `sliderLink` VARCHAR(100),
    IN `sliderLinkText` VARCHAR(100),
    IN `sliderOrderNumber` INT,
    IN `sliderAddedOn` DATETIME,
    IN `sliderStatus` INT)
BEGIN
    -- Check for existing slider with the same order number
    IF (SELECT COUNT(*) FROM slider WHERE order_number = sliderOrderNumber) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Slider with this order number already exists';
    ELSE
        -- Insert new slider
        INSERT INTO slider (
            image,
            heading, 
            sub_heading, 
            link, 
            link_txt, 
            order_number, 
            added_on,
            status
        ) VALUES (
            sliderImageName,
            sliderHeading, 
            sliderSubHeading, 
            sliderLink, 
            sliderLinkText, 
            sliderOrderNumber, 
            sliderAddedOn, 
            sliderStatus
        );
    END IF;
END