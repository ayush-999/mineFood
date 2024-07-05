<?php

class Admin
{
    protected PDO $db;

    public function __construct(PDO $dbConnection)
    {
        $this->db = $dbConnection;
    }

    /****************** Category function start *****************/
    /**
     * @throws Exception
     */

    // TODO: JSON conversion needs work across categories functions
    public function get_all_categories(): bool|string
    {
        try {
            $strQuery = "CALL sp_getAllCategories()";
            $stmt = $this->db->prepare($strQuery);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($result);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function delete_category($categoryId): bool
    {
        try {
            $strQuery = "CALL sp_deleteCategory(?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $categoryId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function add_category($categoryName, $orderNumber, $status, $added_on): string
    {
        try {
            // Check if the category already exists
            $checkQuery = "CALL sp_checkCategoryExists(?)";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(1, $categoryName, PDO::PARAM_STR);
            $checkStmt->execute();

            if ($checkStmt->fetchColumn() > 0) {
                $checkStmt->closeCursor();
                return "Category already exists";
            } else {
                $checkStmt->closeCursor();
                // Insert new category
                $strQuery = "CALL sp_addCategory(?, ?, ?, ?)";
                $stmt = $this->db->prepare($strQuery);
                $stmt->bindParam(1, $categoryName, PDO::PARAM_STR);
                $stmt->bindParam(2, $orderNumber, PDO::PARAM_INT);
                $stmt->bindParam(3, $status, PDO::PARAM_INT);
                $stmt->bindParam(4, $added_on, PDO::PARAM_STR);
                $stmt->execute();
                return "Category added successfully";
            }
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function update_category($categoryId, $categoryName, $orderNumber, $status, $added_on): string
    {
        try {
            $currentNameQuery = "SELECT category_name FROM category WHERE id = ?";
            $currentNameStmt = $this->db->prepare($currentNameQuery);
            $currentNameStmt->bindParam(1, $categoryId, PDO::PARAM_INT);
            $currentNameStmt->execute();
            $currentName = $currentNameStmt->fetchColumn();
            $currentNameStmt->closeCursor(); // Close the cursor

            // Check if the name has been changed
            if ($categoryName != $currentName) {
                // If the name has been changed, check if the new name already exists
                $checkQuery = "CALL sp_checkCategoryExists(?)";
                $checkStmt = $this->db->prepare($checkQuery);
                $checkStmt->bindParam(1, $categoryName, PDO::PARAM_STR);
                $checkStmt->execute();

                if ($checkStmt->fetchColumn() > 0) {
                    $checkStmt->closeCursor();
                    return "Category name already exists";
                }
                $checkStmt->closeCursor(); // Close the cursor
            }

            $strQuery = "CALL sp_updateCategory(?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $categoryId, PDO::PARAM_INT);
            $stmt->bindParam(2, $categoryName, PDO::PARAM_STR);
            $stmt->bindParam(3, $orderNumber, PDO::PARAM_INT);
            $stmt->bindParam(4, $status, PDO::PARAM_INT);
            $stmt->bindParam(5, $added_on, PDO::PARAM_STR);
            $stmt->execute();
            return "Category updated successfully";
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /****************** Admin function start *****************/
    /**
     * @throws Exception
     */

    public function admin_login($username, $password)
    {
        try {
            $strQuery = "CALL sp_userLogin(?, ?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $username, PDO::PARAM_STR);
            $stmt->bindParam(2, $password, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function updateAdmin($profileId, $profileName, $profileUsername, $profileEmail, $profilePassword, $profileAddress, $profileMobile, $added_on, $profileImg): bool|string
    {
        try {
            $strQuery = "CALL sp_updateAdmin(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $profileId, PDO::PARAM_INT);
            $stmt->bindParam(2, $profileName, PDO::PARAM_STR);
            $stmt->bindParam(3, $profileUsername, PDO::PARAM_STR);
            $stmt->bindParam(4, $profilePassword, PDO::PARAM_STR);
            $stmt->bindParam(5, $profileEmail, PDO::PARAM_STR);
            $stmt->bindParam(6, $profileMobile, PDO::PARAM_STR);
            $stmt->bindParam(7, $added_on, PDO::PARAM_STR);
            $stmt->bindParam(8, $profileAddress, PDO::PARAM_STR);
            $stmt->bindParam(9, $profileImg, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->rowCount();
            if ($result > 0) {
                return json_encode(["message" => "Profile updated successfully"]);
            } else {
                return json_encode(["message" => "Profile not updated"]);
            }
            // return "Profile updated successfully";
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function getAdminDetails(): bool|string
    {
        try {
            $strQuery = "CALL sp_getAdminDetails()";
            $stmt = $this->db->prepare($strQuery);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return json_encode($result);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    /****************** User function start *****************/
    /**
     * @throws Exception
     */
    public function get_all_users(): bool|string
    {
        try {
            $strQuery = "CALL sp_getAllUsers()";
            $stmt = $this->db->prepare($strQuery);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as &$user) {
                $user['name'] = decryptData($user['name']);
                $user['email'] = decryptData($user['email']);
            }
            return json_encode($result);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function delete_user($userId): bool
    {
        try {
            $strQuery = "CALL sp_deleteUser(?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $userId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function add_user($userName, $userMobile, $userEmail, $status, $added_on)
    {
        try {
            $userName = encryptData($userName);
            $userEmail = encryptData($userEmail);
            // Insert new user
            $strQuery = "CALL sp_addUser(?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $userName, PDO::PARAM_STR);
            $stmt->bindParam(2, $userMobile, PDO::PARAM_STR);
            $stmt->bindParam(3, $userEmail, PDO::PARAM_STR);
            $stmt->bindParam(4, $status, PDO::PARAM_INT);
            $stmt->bindParam(5, $added_on, PDO::PARAM_STR);
            $stmt->execute();
            return "User added successfully";

        } catch (PDOException $e) {
            if ($e->errorInfo[0] === '45000') {
                return $e->errorInfo[2]; // Custom error message from stored procedure
            }
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function update_user($userId, $userName, $userMobile, $userEmail, $status, $added_on)
    {
        try {
            $userName = encryptData($userName);
            $userEmail = encryptData($userEmail);
            $strQuery = "CALL sp_updateUser(?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $userId, PDO::PARAM_INT);
            $stmt->bindParam(2, $userName, PDO::PARAM_STR);
            $stmt->bindParam(3, $userMobile, PDO::PARAM_STR);
            $stmt->bindParam(4, $userEmail, PDO::PARAM_STR);
            $stmt->bindParam(5, $status, PDO::PARAM_INT);
            $stmt->bindParam(6, $added_on, PDO::PARAM_STR);
            $stmt->execute();
            return "User updated successfully";
        } catch (PDOException $e) {
            if ($e->errorInfo[0] === '45000') {
                return $e->errorInfo[2]; // Custom error message from stored procedure
            }
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /****************** Delivery Boy function start *****************/
    /**
     * @throws Exception
     */
    public function get_all_delivery_boy(): bool|string
    {
        try {
            $strQuery = "CALL sp_getAllDeliveryBoy()";
            $stmt = $this->db->prepare($strQuery);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as &$delivery_boy) {
                $delivery_boy['name'] = decryptData($delivery_boy['name']);
                $delivery_boy['email'] = decryptData($delivery_boy['email']);
            }
            return json_encode($result);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function delete_deliveryBoy($deliveryBoyId): bool
    {
        try {
            $strQuery = "CALL sp_deleteDeliveryBoy(?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $deliveryBoyId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function add_deliveryBoy($deliveryBoyName, $deliveryBoyMobile, $deliveryBoyEmail, $status, $added_on)
    {
        try {
            $deliveryBoyName = encryptData($deliveryBoyName);
            $deliveryBoyEmail = encryptData($deliveryBoyEmail);
            // Insert new Delivery Boy
            $strQuery = "CALL sp_addDeliveryBoy(?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $deliveryBoyName, PDO::PARAM_STR);
            $stmt->bindParam(2, $deliveryBoyMobile, PDO::PARAM_STR);
            $stmt->bindParam(3, $deliveryBoyEmail, PDO::PARAM_STR);
            $stmt->bindParam(4, $status, PDO::PARAM_INT);
            $stmt->bindParam(5, $added_on, PDO::PARAM_STR);
            $stmt->execute();
            return "Delivery boy added successfully";
        } catch (PDOException $e) {
            if ($e->errorInfo[0] === '45000') {
                return $e->errorInfo[2]; // Custom error message from stored procedure
            }
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function update_deliveryBoy($deliveryBoyId, $deliveryBoyName, $deliveryBoyMobile, $deliveryBoyEmail, $status, $added_on)
    {
        try {
            $deliveryBoyName = encryptData($deliveryBoyName);
            $deliveryBoyEmail = encryptData($deliveryBoyEmail);
            // Update Delivery Boy
            $strQuery = "CALL sp_updateDeliveryBoy(?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $deliveryBoyId, PDO::PARAM_INT);
            $stmt->bindParam(2, $deliveryBoyName, PDO::PARAM_STR);
            $stmt->bindParam(3, $deliveryBoyMobile, PDO::PARAM_STR);
            $stmt->bindParam(4, $deliveryBoyEmail, PDO::PARAM_STR);
            $stmt->bindParam(5, $status, PDO::PARAM_INT);
            $stmt->bindParam(6, $added_on, PDO::PARAM_STR);
            $stmt->execute();
            return "Delivery boy updated successfully";
        } catch (PDOException $e) {
            if ($e->errorInfo[0] === '45000') {
                return $e->errorInfo[2]; // Custom error message from stored procedure
            }
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /****************** Coupon Code function start *****************/
    /**
     * @throws Exception
     */
    public function get_all_couponList(): bool|string
    {
        try {
            $strQuery = "CALL sp_getAllCouponCode()";
            $stmt = $this->db->prepare($strQuery);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($result);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function delete_couponCode($couponCodeId): bool
    {
        try {
            $strQuery = "CALL sp_deleteCouponCode(?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $couponCodeId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function add_couponCode($couponCodeName, $status, $couponCodeBgColor, $couponCodeTxtColor, $couponCodeType, $couponCodeStartDate, $couponCodeEndDate, $couponCodeCartValue, $couponCodeMinCartValue, $added_on)
    {
        try {
            // Insert new Coupon Code
            $strQuery = "CALL sp_addCouponCode(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $couponCodeName, PDO::PARAM_STR);
            $stmt->bindParam(2, $couponCodeType, PDO::PARAM_STR);
            $stmt->bindParam(3, $couponCodeCartValue, PDO::PARAM_INT);
            $stmt->bindParam(4, $couponCodeMinCartValue, PDO::PARAM_INT);
            $stmt->bindParam(5, $couponCodeStartDate, PDO::PARAM_STR);
            $stmt->bindParam(6, $couponCodeEndDate, PDO::PARAM_STR);
            $stmt->bindParam(7, $status, PDO::PARAM_INT);
            $stmt->bindParam(8, $couponCodeBgColor, PDO::PARAM_STR);
            $stmt->bindParam(9, $couponCodeTxtColor, PDO::PARAM_STR);
            $stmt->bindParam(10, $added_on, PDO::PARAM_STR);
            $stmt->execute();
            return "Coupon added successfully";
        } catch (PDOException $e) {
            if ($e->errorInfo[0] === '45000') {
                return $e->errorInfo[2]; // Custom error message from stored procedure
            }
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function update_couponCode($couponCodeId, $couponCodeName, $status, $couponCodeBgColor, $couponCodeTxtColor, $couponCodeType, $couponCodeStartDate, $couponCodeEndDate, $couponCodeCartValue, $couponCodeMinCartValue)
    {
        try {
            // Update Coupon Code
            $strQuery = "CALL sp_updateCouponCode(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $couponCodeId, PDO::PARAM_INT);
            $stmt->bindParam(2, $couponCodeName, PDO::PARAM_STR);
            $stmt->bindParam(3, $couponCodeType, PDO::PARAM_STR);
            $stmt->bindParam(4, $couponCodeCartValue, PDO::PARAM_INT);
            $stmt->bindParam(5, $couponCodeMinCartValue, PDO::PARAM_INT);
            $stmt->bindParam(6, $couponCodeStartDate, PDO::PARAM_STR);
            $stmt->bindParam(7, $couponCodeEndDate, PDO::PARAM_STR);
            $stmt->bindParam(8, $status, PDO::PARAM_INT);
            $stmt->bindParam(9, $couponCodeBgColor, PDO::PARAM_STR);
            $stmt->bindParam(10, $couponCodeTxtColor, PDO::PARAM_STR);
            $stmt->execute();
            return "Coupon updated successfully";
        } catch (PDOException $e) {
            if ($e->errorInfo[0] === '45000') {
                return $e->errorInfo[2]; // Custom error message from stored procedure
            }
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /****************** Dish function start ****************
     * @throws Exception
     */
    public function get_dish(): bool|string
    {
        try {
            $strQuery = "CALL sp_getAllDish()";
            $stmt = $this->db->prepare($strQuery);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($result);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

}