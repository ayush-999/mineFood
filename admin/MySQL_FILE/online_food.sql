-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 18, 2024 at 03:01 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `online_food`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `sp_addCategory`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addCategory` (IN `categoryName` VARCHAR(255), IN `orderNumber` INT, IN `status` INT, IN `addedOn` DATETIME)   BEGIN
	INSERT INTO category (category_name, order_number, status, added_on)
     VALUES (categoryName, orderNumber, status, addedOn);
END$$

DROP PROCEDURE IF EXISTS `sp_addCouponCode`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addCouponCode` (IN `couponCodeName` VARCHAR(10), IN `couponType` ENUM('P','F'), IN `couponValue` INT, IN `cartMinValue` INT, IN `expDate` DATE, IN `startDate` DATE, IN `status` INT, IN `bgColor` VARCHAR(50), IN `txtColor` VARCHAR(50), IN `addedOn` DATETIME)   BEGIN
    -- Check for existing Coupon code by coupon_code
    IF (SELECT COUNT(*) FROM coupon_code WHERE coupon_name = couponCodeName) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Coupon already exists';
    ELSE
        -- Insert new Coupon code
        INSERT INTO coupon_code (coupon_name, coupon_type, coupon_value, cart_min_value, started_on, expired_on, status, bg_color, txt_color, added_on)
        VALUES (couponCodeName, couponType, couponValue, cartMinValue, startDate, expDate, status, bgColor, txtColor, addedOn);
    END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_addDeliveryBoy`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addDeliveryBoy` (IN `deliveryBoyName` VARCHAR(255), IN `deliveryBoyMobile` VARCHAR(15), IN `deliveryBoyEmail` VARCHAR(255), IN `status` INT, IN `addedOn` DATETIME)   BEGIN
    -- Check for existing delivery boy by mobile or email
    IF (SELECT COUNT(*) FROM delivery_boy WHERE mobile = deliveryBoyMobile OR email = deliveryBoyEmail) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Delivery boy with this mobile or email already exists';
    ELSE
        -- Insert new Delivery Boy
        INSERT INTO delivery_boy (name, mobile, email, status, added_on)
        VALUES (deliveryBoyName, deliveryBoyMobile, deliveryBoyEmail, status, addedOn);
    END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_addDish`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addDish` (IN `dishCategoryId` INT, IN `dishName` VARCHAR(100), IN `dishDetail` TEXT, IN `dishImage` VARCHAR(255), IN `dishType` ENUM('veg','non-veg'), IN `status` INT, IN `addedOn` DATETIME)   BEGIN
    -- Check for existing dish by name
    IF (SELECT COUNT(*) FROM dish WHERE dish_name = dishName) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Dish already exists';
    ELSE
        -- Insert new Dish
        INSERT INTO dish (category_id, dish_name, dish_detail, image, type, status, added_on)
        VALUES (dishCategoryId, dishName, dishDetail, dishImage, dishType, status, addedOn);
    END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_addUser`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addUser` (IN `userName` VARCHAR(255), IN `userMobile` VARCHAR(15), IN `userEmail` VARCHAR(255), IN `status` INT, IN `addedOn` DATETIME)   BEGIN
    -- Check for existing delivery boy by mobile or email
    IF (SELECT COUNT(*) FROM user WHERE mobile = userMobile OR email = userEmail) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'User with this mobile or email already exists';
    ELSE
        -- Insert new Delivery Boy
        INSERT INTO user (name, mobile, email, status, added_on)
     VALUES (userName, userMobile, userEmail, status, addedOn);
    END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_checkCategoryExists`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_checkCategoryExists` (IN `categoryName` VARCHAR(255))   BEGIN
	 SELECT COUNT(*) FROM category WHERE category_name = categoryName;
END$$

DROP PROCEDURE IF EXISTS `sp_deleteCategory`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleteCategory` (IN `categoryId` INT)   BEGIN
	DELETE FROM category
    WHERE id = categoryId;
END$$

DROP PROCEDURE IF EXISTS `sp_deleteCouponCode`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleteCouponCode` (IN `couponCodeId` INT)   BEGIN
	DELETE FROM coupon_code
    WHERE id = couponCodeId;
END$$

DROP PROCEDURE IF EXISTS `sp_deleteDeliveryBoy`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleteDeliveryBoy` (IN `deliveryBoyId` INT)   BEGIN
	DELETE FROM delivery_boy
    WHERE id = deliveryBoyId;
END$$

DROP PROCEDURE IF EXISTS `sp_deleteDish`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleteDish` (IN `dishId` INT)   BEGIN
	DELETE FROM dish
    WHERE id = dishId;
END$$

DROP PROCEDURE IF EXISTS `sp_deleteUser`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleteUser` (IN `userId` INT)   BEGIN
	DELETE FROM user
    WHERE id = userId;
END$$

DROP PROCEDURE IF EXISTS `sp_getAdminDetails`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getAdminDetails` ()   BEGIN
	SELECT *
    FROM admin
    ORDER BY username;
END$$

DROP PROCEDURE IF EXISTS `sp_getAllCategories`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getAllCategories` ()   BEGIN
	SELECT * FROM category
    ORDER BY order_number;
END$$

DROP PROCEDURE IF EXISTS `sp_getAllCouponCode`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getAllCouponCode` ()   BEGIN
	SELECT * FROM coupon_code;
END$$

DROP PROCEDURE IF EXISTS `sp_getAllDeliveryBoy`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getAllDeliveryBoy` ()   BEGIN
	SELECT * FROM delivery_boy;
END$$

DROP PROCEDURE IF EXISTS `sp_getAllDish`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getAllDish` ()   BEGIN
    SELECT dish.*, 
    category.category_name,
	category.status as category_status
    FROM dish, category 
    where dish.category_id = category.id 
    order by dish.id asc;
END$$

DROP PROCEDURE IF EXISTS `sp_getAllUsers`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getAllUsers` ()   BEGIN
	SELECT * FROM user;
END$$

DROP PROCEDURE IF EXISTS `sp_getDishById`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getDishById` (IN `dishId` INT)   BEGIN
    SELECT dish.*,
           category.category_name,
           category.status as category_status
    FROM dish
    JOIN category ON dish.category_id = category.id
    WHERE dish.id = dishId;
END$$

DROP PROCEDURE IF EXISTS `sp_updateAdmin`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateAdmin` (IN `p_adminId` INT, IN `p_name` VARCHAR(255), IN `p_username` VARCHAR(255), IN `p_password` VARCHAR(255), IN `p_email` VARCHAR(255), IN `p_mobile` VARCHAR(15), IN `addedOn` DATETIME, IN `p_address` VARCHAR(255), IN `p_profileImg` VARCHAR(255))   BEGIN
	UPDATE admin
    SET 
    name = p_name, 
    username = p_username, 
    password = p_password, 
    email = p_email, 
    mobile_no = p_mobile,
    added_on = addedOn,
    address = p_address,
    admin_img = p_profileImg
    WHERE id = p_adminId;
END$$

DROP PROCEDURE IF EXISTS `sp_updateCategory`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateCategory` (IN `categoryId` INT, IN `categoryName` VARCHAR(255), IN `orderNumber` INT, IN `status` INT, IN `addedOn` DATETIME)   BEGIN
	UPDATE category
    SET category_name = categoryName, order_number = orderNumber, status = status, added_on = addedOn
    WHERE id = categoryId;
END$$

DROP PROCEDURE IF EXISTS `sp_updateCouponCode`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateCouponCode` (IN `couponCodeId` INT, IN `couponCodeName` VARCHAR(10), IN `couponType` ENUM('P','F'), IN `couponValue` INT, IN `cartMinValue` INT, IN `startDate` DATE, IN `expDate` DATE, IN `status` INT, IN `bgColor` VARCHAR(50), IN `txtColor` VARCHAR(50))   BEGIN
    -- Check for existing coupon_code by coupon_name, excluding current record
    IF (SELECT COUNT(*) FROM coupon_code WHERE (coupon_name = couponCodeName) AND id != couponCodeId) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Coupon already exists';
    ELSE
        -- Update coupon_code
        UPDATE coupon_code
        SET coupon_name = couponCodeName, 
        coupon_type = couponType, 
        coupon_value = couponValue, 
        cart_min_value = cartMinValue, 
        started_on = startDate, 
        expired_on = expDate, 
        status = status, 
        bg_color = bgColor, 
        txt_color = txtColor
        WHERE id = couponCodeId;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_updateDeliveryBoy`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateDeliveryBoy` (IN `deliveryBoyId` INT, IN `deliveryBoyName` VARCHAR(255), IN `deliveryBoyMobile` VARCHAR(15), IN `deliveryBoyEmail` VARCHAR(255), IN `status` INT, IN `addedOn` DATETIME)   BEGIN
    -- Check for existing delivery boy by mobile or email, excluding current record
    IF (SELECT COUNT(*) FROM delivery_boy WHERE (mobile = deliveryBoyMobile OR email = deliveryBoyEmail) AND id != deliveryBoyId) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Delivery boy with this mobile or email already exists';
    ELSE
        -- Update Delivery Boy
        UPDATE delivery_boy
        SET name = deliveryBoyName, mobile = deliveryBoyMobile, email = deliveryBoyEmail, status = status, added_on = addedOn
        WHERE id = deliveryBoyId;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_updateDish`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateDish` (IN `dishId` INT, IN `dishCategoryId` INT, IN `dishName` VARCHAR(100), IN `dishDetail` TEXT, IN `dishImage` VARCHAR(255), IN `dishType` ENUM('veg','non-veg'), IN `status` INT, IN `addedOn` DATETIME)   BEGIN
    -- Check for existing dish by name, excluding current record
    IF
(
SELECT COUNT(*)
FROM dish
WHERE (dish_name = dishName)
  AND id != dishId) > 0 THEN
        SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Dish already exists';
ELSE
        -- Update Dish
UPDATE dish
SET category_id = dishCategoryId,
    dish_name   = dishName,
    dish_detail = dishDetail,
    image       = dishImage,
    type        = dishType,
    status      = status,
    added_on    = addedOn
WHERE id = dishId;
END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_updateUser`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateUser` (IN `userId` INT, IN `userName` VARCHAR(255), IN `userMobile` VARCHAR(15), IN `userEmail` VARCHAR(255), IN `status` INT, IN `addedOn` DATETIME)   BEGIN
    -- Check for existing delivery boy by mobile or email, excluding current record
    IF (SELECT COUNT(*) FROM user WHERE (mobile = userMobile OR email = userEmail) AND id != userId) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'User with this mobile or email already exists';
    ELSE
        -- Update Delivery Boy
        UPDATE user
		SET name = userName, mobile = userMobile, email = userEmail, status = status, added_on = addedOn
		WHERE id = userId;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_userLogin`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_userLogin` (IN `p_username` VARCHAR(255), IN `p_password` VARCHAR(255))   BEGIN
    SELECT username, password 
    FROM admin 
    WHERE username = p_username AND password = p_password;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile_no` varchar(15) NOT NULL,
  `added_on` datetime NOT NULL,
  `address` varchar(255) NOT NULL,
  `admin_img` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `username`, `password`, `email`, `mobile_no`, `added_on`, `address`, `admin_img`) VALUES
(1, 'Ayush', 'admin', 'Ayush@123', 'admin@gmail.com', '+919993832158', '2024-07-10 03:04:37', 'Pendra, Bilaspur, Chhattisgarh, 495119', '05.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

DROP TABLE IF EXISTS `banner`;
CREATE TABLE IF NOT EXISTS `banner` (
  `id` int NOT NULL AUTO_INCREMENT,
  `image` varchar(100) NOT NULL,
  `heading` varchar(500) NOT NULL,
  `sub_heading` varchar(500) NOT NULL,
  `link` varchar(100) NOT NULL,
  `link_txt` varchar(100) NOT NULL,
  `order_number` int NOT NULL,
  `added_on` datetime NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banner`
--

INSERT INTO `banner` (`id`, `image`, `heading`, `sub_heading`, `link`, `link_txt`, `order_number`, `added_on`, `status`) VALUES
(1, '533799913_banner-4.jpg', 'Drink & Heathy Food', 'Fresh Heathy and Organic', 'shop', 'Order Now', 1, '2020-06-23 03:00:05', 1),
(2, '546847873_banner-4.jpg', 'Drink & Heathy Food', 'Fresh Heathy and Organic', 'shop', 'Order Now', 1, '2020-06-23 03:06:53', 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `order_number` int NOT NULL,
  `status` int NOT NULL,
  `added_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`, `order_number`, `status`, `added_on`) VALUES
(1, 'Chaat & Snacks', 2, 0, '2024-07-05 03:26:19'),
(5, 'Murg', 1, 1, '2024-07-05 04:22:57'),
(6, 'Sweets', 3, 1, '2024-07-05 04:18:53'),
(7, 'Chinese', 4, 1, '2024-07-10 06:34:55');

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

DROP TABLE IF EXISTS `contact_us`;
CREATE TABLE IF NOT EXISTS `contact_us` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `added_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `mobile`, `subject`, `message`, `added_on`) VALUES
(1, 'Vishal', 'phpvishal@gmail.com', '9999999999', 'Test Subject', 'test message', '2020-06-23 03:21:43');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_code`
--

DROP TABLE IF EXISTS `coupon_code`;
CREATE TABLE IF NOT EXISTS `coupon_code` (
  `id` int NOT NULL AUTO_INCREMENT,
  `coupon_name` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `coupon_type` enum('P','F') NOT NULL,
  `coupon_value` int NOT NULL,
  `cart_min_value` int NOT NULL,
  `started_on` date NOT NULL,
  `expired_on` date NOT NULL,
  `status` int NOT NULL,
  `bg_color` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'rgba(255, 176, 29, 1)',
  `txt_color` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'rgba(184, 65, 0, 1)',
  `added_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `coupon_code`
--

INSERT INTO `coupon_code` (`id`, `coupon_name`, `coupon_type`, `coupon_value`, `cart_min_value`, `started_on`, `expired_on`, `status`, `bg_color`, `txt_color`, `added_on`) VALUES
(6, 'WELCOME50', 'P', 50, 100, '2024-06-18', '2024-06-21', 1, 'rgb(234, 199, 0)', 'rgb(141, 100, 0)', '2024-06-24 09:35:41'),
(8, 'FAST50', 'F', 50, 100, '2024-06-25', '2024-06-24', 1, 'rgb(255, 176, 29)', 'rgb(184, 65, 0)', '2024-06-24 02:57:32');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_boy`
--

DROP TABLE IF EXISTS `delivery_boy`;
CREATE TABLE IF NOT EXISTS `delivery_boy` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `mobile` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` varchar(50) NOT NULL,
  `status` int NOT NULL,
  `email_verify` int NOT NULL,
  `added_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `delivery_boy`
--

INSERT INTO `delivery_boy` (`id`, `name`, `mobile`, `email`, `password`, `status`, `email_verify`, `added_on`) VALUES
(14, 'L1dpV005TkJIZVBOUnNYUUtGZ25Tdz09OjpTSk/Wj+tIbTEDrMS3VfRK', '+911234567890', 'R05yaDFGN1BmTUp1MTFDeUpyMmlEZz09OjqPtoVVIvR+JzZMskdn5Wo8', '', 1, 0, '2024-06-30 06:53:42'),
(15, 'SzA4UFBvOFhZQUgxU0NKU1pVc3krQT09OjpoxYdAFkr5X7o/2P2YhZpl', '+919993832158', 'WVZLTGZpNHZ5R1dUQ2lUUnBnNHZlS2gxRjlSWXZPUDlQa3Z6UUlSekc4WT06OogFWoiUArjg4zGQwV8PgGs=', '', 1, 0, '2024-07-10 03:04:07');

-- --------------------------------------------------------

--
-- Table structure for table `dish`
--

DROP TABLE IF EXISTS `dish`;
CREATE TABLE IF NOT EXISTS `dish` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `dish_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `dish_detail` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `image` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `type` enum('veg','non-veg') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` int NOT NULL,
  `added_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dish`
--

INSERT INTO `dish` (`id`, `category_id`, `dish_name`, `dish_detail`, `image`, `type`, `status`, `added_on`) VALUES
(1, 6, 'Gulab Jamun', 'Gulab Jamun', '', 'veg', 1, '2020-06-17 10:43:59'),
(3, 7, 'Chow mein', 'Chow mein', 'avatar.png', 'non-veg', 1, '2024-07-10 07:47:11'),
(4, 5, 'Butter Chicken', 'Butter chicken or murg makhani is a dish, originating in the Indian subcontinent, of chicken in a mildly spiced tomato sauce.', 'default-male.png', 'non-veg', 1, '2024-07-10 07:46:55');

-- --------------------------------------------------------

--
-- Table structure for table `dish_cart`
--

DROP TABLE IF EXISTS `dish_cart`;
CREATE TABLE IF NOT EXISTS `dish_cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `dish_detail_id` int NOT NULL,
  `qty` int NOT NULL,
  `added_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dish_cart`
--

INSERT INTO `dish_cart` (`id`, `user_id`, `dish_detail_id`, `qty`, `added_on`) VALUES
(3, 2, 6, 2, '2020-07-21 09:18:31');

-- --------------------------------------------------------

--
-- Table structure for table `dish_details`
--

DROP TABLE IF EXISTS `dish_details`;
CREATE TABLE IF NOT EXISTS `dish_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dish_id` int NOT NULL,
  `attribute` varchar(100) NOT NULL,
  `price` int NOT NULL,
  `status` int NOT NULL,
  `added_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dish_details`
--

INSERT INTO `dish_details` (`id`, `dish_id`, `attribute`, `price`, `status`, `added_on`) VALUES
(1, 3, 'Full', 300, 1, '2020-06-19 10:25:47'),
(2, 3, 'Half', 170, 1, '2020-06-19 10:49:45'),
(6, 1, 'Per Piece', 40, 1, '2020-06-20 00:00:00'),
(8, 4, 'Half', 250, 0, '2020-06-27 12:50:50'),
(9, 4, 'Full', 410, 1, '2020-06-27 12:50:50'),
(11, 5, 'Test1', 100, 1, '2020-07-06 12:00:24'),
(12, 5, 'Test2', 200, 0, '2020-07-06 12:00:24');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

DROP TABLE IF EXISTS `order_detail`;
CREATE TABLE IF NOT EXISTS `order_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `dish_details_id` int NOT NULL,
  `price` float NOT NULL,
  `qty` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`id`, `order_id`, `dish_details_id`, `price`, `qty`) VALUES
(1, 1, 6, 40, 6),
(2, 2, 6, 40, 4),
(3, 3, 6, 40, 3);

-- --------------------------------------------------------

--
-- Table structure for table `order_master`
--

DROP TABLE IF EXISTS `order_master`;
CREATE TABLE IF NOT EXISTS `order_master` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `total_price` float NOT NULL,
  `coupon_code` varchar(20) NOT NULL,
  `final_price` float NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `delivery_boy_id` int NOT NULL,
  `payment_status` varchar(20) NOT NULL,
  `payment_type` varchar(10) NOT NULL,
  `payment_id` varchar(100) NOT NULL,
  `order_status` int NOT NULL,
  `cancel_by` enum('user','admin') NOT NULL,
  `cancel_at` datetime NOT NULL,
  `added_on` datetime NOT NULL,
  `delivered_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_master`
--

INSERT INTO `order_master` (`id`, `user_id`, `name`, `email`, `mobile`, `address`, `total_price`, `coupon_code`, `final_price`, `zipcode`, `delivery_boy_id`, `payment_status`, `payment_type`, `payment_id`, `order_status`, `cancel_by`, `cancel_at`, `added_on`, `delivered_on`) VALUES
(1, 2, 'Vishal', 'ervishalwebdeveloper@gmail.com', '9999999999', 'Test', 240, '', 240, '110076', 0, 'pending', 'wallet', '', 5, 'admin', '2020-07-21 08:13:01', '2020-07-18 06:08:19', '0000-00-00 00:00:00'),
(2, 2, 'Vishal', 'ervishalwebdeveloper@gmail.com', '9999999999', 'test', 160, '', 160, '110076', 0, 'pending', 'wallet', '', 4, 'user', '0000-00-00 00:00:00', '2020-07-20 06:09:59', '0000-00-00 00:00:00'),
(3, 5, 'Vishal', 'phpvishal@gmail.com', '9999999999', 'Test', 120, '', 120, '110076', 0, 'pending', 'cod', '', 4, 'user', '0000-00-00 00:00:00', '2020-07-23 09:09:41', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

DROP TABLE IF EXISTS `order_status`;
CREATE TABLE IF NOT EXISTS `order_status` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_status` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`id`, `order_status`) VALUES
(1, 'Pending'),
(2, 'Cooking '),
(3, 'On the Way'),
(4, 'Delivered'),
(5, 'Cancel');

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

DROP TABLE IF EXISTS `rating`;
CREATE TABLE IF NOT EXISTS `rating` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `order_id` int NOT NULL,
  `dish_detail_id` int NOT NULL,
  `rating` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`id`, `user_id`, `order_id`, `dish_detail_id`, `rating`) VALUES
(1, 1, 6, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
CREATE TABLE IF NOT EXISTS `setting` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cart_min_price` int NOT NULL,
  `cart_min_price_msg` varchar(250) NOT NULL,
  `website_close` int NOT NULL,
  `wallet_amt` int NOT NULL,
  `website_close_msg` varchar(250) NOT NULL,
  `referral_amt` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `cart_min_price`, `cart_min_price_msg`, `website_close`, `wallet_amt`, `website_close_msg`, `referral_amt`) VALUES
(1, 40, 'Cart min price will be 50 rs', 0, 0, 'Website Closed for today', 50);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `mobile` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` int NOT NULL,
  `email_verify` int NOT NULL,
  `rand_str` varchar(20) NOT NULL,
  `referral_code` varchar(20) NOT NULL,
  `from_referral_code` varchar(20) NOT NULL,
  `added_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `mobile`, `password`, `status`, `email_verify`, `rand_str`, `referral_code`, `from_referral_code`, `added_on`) VALUES
(16, 'V21Vb1hwb2cybnJwWlpKTitObXNjUT09Ojp2QN20fkmGuEwsR2jvZymo', 'VVZQVEZMK1pTNkd2SVpLY3YwSzU1czljWVloY285U2xDd2lyRnpMY1EwND06OtVauJw9Cj5LF7uTI38gbHM=', '+919876543210', '', 0, 0, '', '', '', '2024-06-30 06:54:18');

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

DROP TABLE IF EXISTS `wallet`;
CREATE TABLE IF NOT EXISTS `wallet` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `amt` int NOT NULL,
  `msg` varchar(500) NOT NULL,
  `type` enum('in','out') NOT NULL,
  `payment_id` varchar(50) NOT NULL,
  `added_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wallet`
--

INSERT INTO `wallet` (`id`, `user_id`, `amt`, `msg`, `type`, `payment_id`, `added_on`) VALUES
(2, 2, 50, 'Registration', 'in', '', '2020-07-18 05:11:38'),
(4, 2, 50, 'Shoping', 'out', '', '0000-00-00 00:00:00'),
(5, 2, 100, 'Added', 'in', '', '0000-00-00 00:00:00'),
(7, 2, 100, 'Added', 'in', '', '2020-07-18 05:58:29'),
(8, 2, 20, 'Added', 'in', '', '2020-07-18 05:59:02'),
(9, 2, 15, 'Added', 'in', '', '2020-07-18 06:00:35'),
(10, 2, 30, 'Added', 'in', '', '2020-07-18 06:01:17'),
(11, 2, 10, 'Added', 'in', '20200718111212800110168602301710786', '2020-07-18 06:04:04'),
(13, 2, 160, 'Order Id-2', 'out', '', '2020-07-18 06:09:59'),
(14, 2, 800, 'Added', 'in', '20200718111212800110168644701732407', '2020-07-18 06:17:19'),
(15, 3, 0, 'Register', 'in', '', '2020-07-18 08:00:53'),
(16, 2, 200, 'Order Id-3', 'out', '', '2020-07-19 04:29:04'),
(17, 2, 200, 'Order Id-4', 'out', '', '2020-07-19 04:30:51'),
(18, 3, 100, 'Test msg', 'in', '', '2020-07-21 08:22:33'),
(19, 2, 200, 'Test Msg', 'in', '', '2020-07-21 08:22:46'),
(22, 2, 50, 'Referral Amt from phpvishal@gmail.com', 'in', '', '2020-07-23 09:12:28');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
