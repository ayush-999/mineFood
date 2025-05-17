<?php

class Admin
{
    public function __construct(protected PDO $db) {}

    /****************** Category function start *****************/
    /**
     * Retrieves all categories from the database
     * @return bool|string JSON encoded string of all categories or false on failure
     * @throws Exception If there's a database error during the operation
     * @throws PDOException If there's a specific PDO database error
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
     * Deletes a specific category by ID
     * @param int $categoryId The ID of the category to delete
     * @return bool True if deletion was successful
     * @throws Exception If there's a database error during deletion
     * @throws PDOException If there's a specific PDO database error
     */
    public function delete_category(int $categoryId): bool
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
     * Adds a new category to the database
     * @param string $categoryName Name of the new category
     * @param int $orderNumber Order number for category display
     * @param int $status Status of the category (active/inactive)
     * @param string $added_on Date when category was added
     * @return string Success or error message
     * @throws Exception If there's a database error during addition
     * @throws PDOException If there's a specific PDO database error or if category already exists
     */
    public function add_category(string $categoryName, int $orderNumber, int $status, string $added_on): string
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
     * Updates an existing category
     * @param int $categoryId ID of the category to update
     * @param string $categoryName New name for the category
     * @param int $orderNumber New order number
     * @param int $status New status (active/inactive)
     * @param string $added_on Update timestamp
     * @return string Success or error message
     * @throws Exception If there's a database error during update
     * @throws PDOException If there's a specific PDO database error or if new name already exists
     */
    public function update_category(int $categoryId, string $categoryName, int $orderNumber, int $status, string $added_on): string
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
     * Authenticates admin login
     * @param string $username Admin username
     * @param string $password Admin password
     * @return array|false Admin details if successful, false otherwise
     * @throws Exception If there's a general authentication error
     * @throws PDOException If there's a database error during authentication
     */
    public function admin_login(string $username, string $password): false|array
    {
        try {
            $strQuery = "CALL sp_userLogin(?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $username, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && $this->verifyPassword($password, $user['password'])) {
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Updates admin profile information
     * @param int $profileId ID of the admin profile
     * @param string $profileName Full name of admin
     * @param string $profileUsername Login username
     * @param string $profileEmail Email address
     * @param string $profilePassword Hashed password
     * @param string $profileAddress Physical address
     * @param string $profileMobile Mobile number
     * @param string $added_on Update timestamp
     * @param string $profileImg Profile image path
     * @return bool|string JSON encoded success/error message
     * @throws Exception If there's a general update error
     * @throws PDOException If there's a database error during update
     */
    public function updateAdmin(
        int $profileId,
        string $profileName,
        string $profileUsername,
        string $profileEmail,
        string $profilePassword,
        string $profileAddress,
        string $profileMobile,
        string $added_on,
        string $area,
        string $city,
        string $district,
        int $pincode,
        string $state,
        string $country,
        string $profileImg,
        string $contactEmail,
        string $contactNumber,
        string $openingHours = ''
    ): bool|string {
        try {
            $strQuery = "CALL sp_updateAdmin(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $profileId, PDO::PARAM_INT);
            $stmt->bindParam(2, $profileName, PDO::PARAM_STR);
            $stmt->bindParam(3, $profileUsername, PDO::PARAM_STR);
            $stmt->bindParam(4, $profilePassword, PDO::PARAM_STR);
            $stmt->bindParam(5, $profileEmail, PDO::PARAM_STR);
            $stmt->bindParam(6, $profileMobile, PDO::PARAM_STR);
            $stmt->bindParam(7, $added_on, PDO::PARAM_STR);
            $stmt->bindParam(8, $area, PDO::PARAM_STR);
            $stmt->bindParam(9, $state, PDO::PARAM_STR);
            $stmt->bindParam(10, $district, PDO::PARAM_STR);
            $stmt->bindParam(11, $pincode, PDO::PARAM_INT);
            $stmt->bindParam(12, $city, PDO::PARAM_STR);
            $stmt->bindParam(13, $country, PDO::PARAM_STR);
            $stmt->bindParam(14, $profileAddress, PDO::PARAM_STR);
            $stmt->bindParam(15, $profileImg, PDO::PARAM_STR);
            $stmt->bindParam(16, $contactEmail, PDO::PARAM_STR);
            $stmt->bindParam(17, $contactNumber, PDO::PARAM_STR);
            $stmt->bindParam(18, $openingHours, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->rowCount();
            if ($result > 0) {
                return json_encode(["message" => "Profile updated successfully"]);
            } else {
                return json_encode(["message" => "Profile not updated"]);
            }
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Retrieves admin details
     * @return bool|string JSON encoded admin details
     * @throws Exception If there's a general retrieval error
     * @throws PDOException If there's a database error during retrieval
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

    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /****************** User function start *****************/
    /**
     * Retrieves all users from the database
     * @return bool|string JSON encoded list of all users
     * @throws Exception If there's a general retrieval error
     * @throws PDOException If there's a database error during retrieval
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
     * Deletes a user by ID
     * @param int $userId ID of the user to delete
     * @return bool True if deletion was successful
     * @throws Exception If there's a general deletion error
     * @throws PDOException If there's a database error during deletion
     */
    public function delete_user(int $userId): bool
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
     * Adds a new user to the system
     * @param string $userName User's full name (will be encrypted)
     * @param string $userMobile User's mobile number
     * @param string $userEmail User's email (will be encrypted)
     * @param int $status Account status
     * @param string $added_on Creation timestamp
     * @return string Success or error message
     * @throws Exception If there's a general addition error
     * @throws PDOException If there's a database error or if user already exists (error code 45000)
     */
    public function add_user(string $userName, string $userMobile, string $userEmail, int $status, string $added_on): string
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
     * Updates user information
     * @param int $userId ID of the user to update
     * @param string $userName Updated full name (will be encrypted)
     * @param string $userMobile Updated mobile number
     * @param string $userEmail Updated email (will be encrypted)
     * @param int $status Updated account status
     * @param string $added_on Update timestamp
     * @return string Success or error message
     * @throws Exception If there's a general update error
     * @throws PDOException If there's a database error or if email already exists (error code 45000)
     */
    public function update_user(int $userId, string $userName, string $userMobile, string $userEmail, int $status, string $added_on): string
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
     * Retrieves all delivery boys from the database
     * @return bool|string JSON encoded list of all delivery boys
     * @throws Exception If there's a general retrieval error
     * @throws PDOException If there's a database error during retrieval
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
     * Deletes a delivery boy by ID
     * @param int $deliveryBoyId ID of the delivery boy to delete
     * @return bool True if deletion was successful
     * @throws Exception If there's a general deletion error
     * @throws PDOException If there's a database error during deletion
     */
    public function delete_deliveryBoy(int $deliveryBoyId): bool
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
     * Adds a new delivery boy
     * @param string $deliveryBoyName Full name (will be encrypted)
     * @param string $deliveryBoyMobile Mobile number
     * @param string $deliveryBoyEmail Email (will be encrypted)
     * @param int $status Account status
     * @param string $added_on Creation timestamp
     * @return string Success or error message
     * @throws Exception If there's a general addition error
     * @throws PDOException If there's a database error or if delivery boy already exists (error code 45000)
     */
    public function add_deliveryBoy(string $deliveryBoyName, string $deliveryBoyMobile, string $deliveryBoyEmail, int $status, string $added_on): string
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
     * Updates delivery boy information
     * @param int $deliveryBoyId ID of the delivery boy to update
     * @param string $deliveryBoyName Updated full name (will be encrypted)
     * @param string $deliveryBoyMobile Updated mobile number
     * @param string $deliveryBoyEmail Updated email (will be encrypted)
     * @param int $status Updated account status
     * @param string $added_on Update timestamp
     * @return string Success or error message
     * @throws Exception If there's a general update error
     * @throws PDOException If there's a database error or if email already exists (error code 45000)
     */
    public function update_deliveryBoy(int $deliveryBoyId, string $deliveryBoyName, string $deliveryBoyMobile, string $deliveryBoyEmail, int $status, string $added_on): string
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
     * Retrieves all coupon codes
     * @return bool|string JSON encoded list of all coupon codes
     * @throws Exception If there's a general retrieval error
     * @throws PDOException If there's a database error during retrieval
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
     * Deletes a coupon code by ID
     * @param int $couponCodeId ID of the coupon to delete
     * @return bool True if deletion was successful
     * @throws Exception If there's a general deletion error
     * @throws PDOException If there's a database error during deletion
     */
    public function delete_couponCode(int $couponCodeId): bool
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
     * Adds a new coupon code
     * @param string $couponCodeName Name/Code of the coupon
     * @param int $status Active/inactive status
     * @param string $couponCodeBgColor Background color for display
     * @param string $couponCodeTxtColor Text color for display
     * @param string $couponCodeType Type of coupon (percentage/fixed)
     * @param string $couponCodeStartDate Valid from date
     * @param string $couponCodeEndDate Valid to date
     * @param int $couponCodeCartValue Discount value
     * @param int $couponCodeMinCartValue Minimum cart value to apply
     * @param string $added_on Creation timestamp
     * @return string Success or error message
     * @throws Exception If there's a general addition error
     * @throws PDOException If there's a database error or if coupon already exists (error code 45000)
     */
    public function add_couponCode(string $couponCodeName, int $status, string $couponCodeBgColor, string $couponCodeTxtColor, string $couponCodeType, string $couponCodeStartDate, string $couponCodeEndDate, int $couponCodeCartValue, int $couponCodeMinCartValue, string $added_on): string
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
     * Updates coupon code information
     * @param int $couponCodeId ID of the coupon to update
     * @param string $couponCodeName Updated name/code
     * @param int $status Updated status
     * @param string $couponCodeBgColor Updated background color
     * @param string $couponCodeTxtColor Updated text color
     * @param string $couponCodeType Updated coupon type
     * @param string $couponCodeStartDate Updated start date
     * @param string $couponCodeEndDate Updated end date
     * @param int $couponCodeCartValue Updated discount value
     * @param int $couponCodeMinCartValue Updated minimum cart value
     * @return string Success or error message
     * @throws Exception If there's a general update error
     * @throws PDOException If there's a database error or if coupon already exists (error code 45000)
     */
    public function update_couponCode(int $couponCodeId, string $couponCodeName, int $status, string $couponCodeBgColor, string $couponCodeTxtColor, string $couponCodeType, string $couponCodeStartDate, string $couponCodeEndDate, int $couponCodeCartValue, int $couponCodeMinCartValue): string
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

    /****************** Dish function start ****************/
    /**
     * Retrieves dish information
     * @param int|null $dishId Specific dish ID or null for all dishes
     * @return false|string JSON encoded dish details or false on failure
     * @throws Exception If there's a general retrieval error
     * @throws PDOException If there's a database error during retrieval
     */
    public function get_dish(int $dishId = null): false|string
    {
        try {
            $result = [];
            if ($dishId) {
                $strQuery = "CALL sp_getDishById(?)";
                $stmt = $this->db->prepare($strQuery);
                $stmt->bindParam(1, $dishId, PDO::PARAM_INT);
                $stmt->execute();
                $dishDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $transformedDishDetails = [];
                foreach ($dishDetails as $dish) {
                    $dishId = $dish['id'];
                    if (!isset($transformedDishDetails[$dishId])) {
                        $transformedDishDetails[$dishId] = [
                            'id' => $dish['id'],
                            'category_id' => $dish['category_id'],
                            'dish_name' => $dish['dish_name'],
                            'dish_detail' => $dish['dish_detail'],
                            'image' => $dish['image'],
                            'type' => $dish['type'],
                            'status' => $dish['status'],
                            'added_on' => $dish['added_on'],
                            'category_name' => $dish['category_name'],
                            'category_status' => $dish['category_status'],
                            'attributes' => []
                        ];
                    }
                    $transformedDishDetails[$dishId]['attributes'][] = [
                        'attribute' => $dish['attribute'],
                        'price' => $dish['price']
                    ];
                }
                $result['dish'] = array_values($transformedDishDetails);
            } else {
                $strQuery = "CALL sp_getAllDish()";
                $stmt = $this->db->prepare($strQuery);
                $stmt->execute();
                $dishDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($dishDetails as &$dish) {
                    unset($dish['attributes']);
                }
                $result['dish'] = $dishDetails;
            }
            return json_encode($result);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Deletes a dish by ID
     * @param int $dishId ID of the dish to delete
     * @return bool True if deletion was successful
     * @throws Exception If there's a general deletion error
     * @throws PDOException If there's a database error during deletion
     */

    public function delete_dish(int $dishId): bool
    {
        try {
            $strQuery = "CALL sp_deleteDish(?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $dishId, PDO::PARAM_INT);
            $stmt->execute();
            $this->delete_dish_attributes($dishId);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Adds a new dish
     * @param string $dishName Name of the dish
     * @param int $dishCategory Category ID
     * @param int $dishStatus Active/inactive status
     * @param string $dishType Type of dish (veg/non-veg)
     * @param string $dishDetail Description
     * @param string $added_on Creation timestamp
     * @param string $imagePath Path to dish image
     * @param array $dishAttributes Array of attributes with price
     * @return string Success or error message
     * @throws Exception If there's a general addition error
     * @throws PDOException If there's a database error or if dish already exists (error code 45000)
     */
    public function add_dish(string $dishName, int $dishCategory, int $dishStatus, string $dishType, string $dishDetail, string $added_on, string $imagePath, array $dishAttributes): string
    {
        try {
            $strQuery = "CALL sp_addDish(?, ?, ?, ?, ?, ?, ?, @newDishId)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $dishCategory, PDO::PARAM_INT);
            $stmt->bindParam(2, $dishName, PDO::PARAM_STR);
            $stmt->bindParam(3, $dishDetail, PDO::PARAM_STR);
            $stmt->bindParam(4, $imagePath, PDO::PARAM_STR);
            $stmt->bindParam(5, $dishType, PDO::PARAM_STR);
            $stmt->bindParam(6, $dishStatus, PDO::PARAM_INT);
            $stmt->bindParam(7, $added_on, PDO::PARAM_STR);
            $stmt->execute();

            // Retrieve the new dish ID
            $stmt = $this->db->query("SELECT @newDishId AS newDishId");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $newDishId = $result['newDishId'];

            // Add dish attributes
            foreach ($dishAttributes as $attribute) {
                $this->add_dish_attribute($newDishId, $attribute['attribute'], $attribute['price'], $added_on);
            }

            return "Dish added successfully";
        } catch (PDOException $e) {
            if ($e->errorInfo[0] === '45000') {
                return $e->errorInfo[2]; // Custom error message from stored procedure
            }
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Updates dish information
     * @param int $dishId ID of the dish to update
     * @param string $dishName Updated name
     * @param int $dishCategory Updated category ID
     * @param int $dishStatus Updated status
     * @param string $dishType Updated type
     * @param string $dishDetail Updated description
     * @param string $added_on Update timestamp
     * @param string $imagePath Updated image path
     * @param array $dishAttributes Updated array of attributes with price
     * @return string Success or error message
     * @throws Exception If there's a general update error
     * @throws PDOException If there's a database error or if dish already exists (error code 45000)
     */
    public function update_dish(int $dishId, string $dishName, int $dishCategory, int $dishStatus, string $dishType, string $dishDetail, string $added_on, string $imagePath, array $dishAttributes): string
    {
        try {
            $strQuery = "CALL sp_updateDish(?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $dishId, PDO::PARAM_INT);
            $stmt->bindParam(2, $dishCategory, PDO::PARAM_INT);
            $stmt->bindParam(3, $dishName, PDO::PARAM_STR);
            $stmt->bindParam(4, $dishDetail, PDO::PARAM_STR);
            $stmt->bindParam(5, $imagePath, PDO::PARAM_STR);
            $stmt->bindParam(6, $dishType, PDO::PARAM_STR);
            $stmt->bindParam(7, $dishStatus, PDO::PARAM_INT);
            $stmt->bindParam(8, $added_on, PDO::PARAM_STR);
            $stmt->execute();

            // Clear existing attributes
            $this->delete_dish_attributes($dishId);

            // Re-insert dish attributes
            foreach ($dishAttributes as $attribute) {
                $this->add_dish_attribute($dishId, $attribute['attribute'], $attribute['price'], $added_on);
            }

            return "Dish updated successfully";
        } catch (PDOException $e) {
            if ($e->errorInfo[0] === '45000') {
                return $e->errorInfo[2]; // Custom error message from stored procedure
            }
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    private function add_dish_attribute($dishId, $attribute, $price, $added_on): void
    {
        $strQuery = "CALL sp_addDishAttribute(?, ?, ?, ?)";
        $stmt = $this->db->prepare($strQuery);
        $stmt->bindParam(1, $dishId, PDO::PARAM_INT);
        $stmt->bindParam(2, $attribute, PDO::PARAM_STR);
        $stmt->bindParam(3, $price, PDO::PARAM_STR);
        $stmt->bindParam(4, $added_on, PDO::PARAM_STR);
        $stmt->execute();
    }

    private function delete_dish_attributes($dishId): void
    {
        $strQuery = "CALL sp_deleteDishAttributes(?)";
        $stmt = $this->db->prepare($strQuery);
        $stmt->bindParam(1, $dishId, PDO::PARAM_INT);
        $stmt->execute();
    }

    /****************** Banner function start *****************/
    /**
     * Retrieves all Banners from the database
     * @return bool|string JSON encoded list of all users
     * @throws Exception If there's a general retrieval error
     * @throws PDOException If there's a database error during retrieval
     */
    public function get_banner(): bool|string
    {
        try {
            $strQuery = "CALL sp_getAllBanner()";
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
     * Deletes a specific banner by ID
     * @param int $bannerId The ID of the banner to delete
     * @return bool True if deletion was successful
     * @throws Exception If there's a database error during deletion
     * @throws PDOException If there's a specific PDO database error
     */
    public function delete_banner(int $bannerId): bool
    {
        try {
            $strQuery = "CALL sp_deleteBanner(?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $bannerId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Adds a new banner
     * @param string $heading Heading of the banner
     * @param string $subHeading Subheading of the banner
     * @param string $link link of the banner
     * @param string $linkText Link text of the banner
     * @param int $orderNumber order number of the banner
     * @param int $status Status of the banner
     * @param string $added_on Creation timestamp
     * @param string $imageName Image name of the banner
     * @return string Success or error message
     * @throws Exception If there's a general addition error
     * @throws PDOException If there's a database error or if dish already exists (error code 45000)
     */
    public function add_banner($heading, $subHeading, $link, $linkText, $orderNumber, $status, $added_on, $imageName): string
    {
        try {
            $strQuery = "CALL sp_addBanner(?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $imageName, PDO::PARAM_STR);
            $stmt->bindParam(2, $heading, PDO::PARAM_STR);
            $stmt->bindParam(3, $subHeading, PDO::PARAM_STR);
            $stmt->bindParam(4, $link, PDO::PARAM_STR);
            $stmt->bindParam(5, $linkText, PDO::PARAM_STR);
            $stmt->bindParam(6, $orderNumber, PDO::PARAM_INT);
            $stmt->bindParam(7, $added_on, PDO::PARAM_STR);
            $stmt->bindParam(8, $status, PDO::PARAM_INT);
            $stmt->execute();
            return "Banner added successfully";
        } catch (PDOException $e) {
            if ($e->errorInfo[0] === '45000') {
                return $e->errorInfo[2]; // Custom error message from stored procedure
            }
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Update a banner
     * @param int $bannerId Banner ID to update
     * @param string $heading Heading of the banner
     * @param string $subHeading Subheading of the banner
     * @param string $link link of the banner
     * @param string $linkText Link text of the banner
     * @param int $orderNumber order number of the banner
     * @param int $status Status of the banner
     * @param string $added_on Creation timestamp
     * @param string $imageName Image name of the banner
     * @return string Success or error message
     * @throws Exception If there's a general addition error
     * @throws PDOException If there's a database error or if dish already exists (error code 45000)
     */
    public function update_banner($bannerId, $heading, $subHeading, $link, $linkText, $orderNumber, $status, $added_on, $imageName): string
    {
        try {
            $strQuery = "CALL sp_updateBanner(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(1, $bannerId, PDO::PARAM_INT);
            $stmt->bindParam(2, $imageName, PDO::PARAM_STR);
            $stmt->bindParam(3, $heading, PDO::PARAM_STR);
            $stmt->bindParam(4, $subHeading, PDO::PARAM_STR);
            $stmt->bindParam(5, $link, PDO::PARAM_STR);
            $stmt->bindParam(6, $linkText, PDO::PARAM_STR);
            $stmt->bindParam(7, $orderNumber, PDO::PARAM_INT);
            $stmt->bindParam(8, $added_on, PDO::PARAM_STR);
            $stmt->bindParam(9, $status, PDO::PARAM_INT);
            $stmt->execute();
            return "Banner updated successfully";
        } catch (PDOException $e) {
            if ($e->errorInfo[0] === '45000') {
                return $e->errorInfo[2]; // Custom error message from stored procedure
            }
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /****************** Setting function start *****************/
    /**
     * Retrieves all settings from the database
     * @return bool|string JSON encoded list of all settings
     * @throws Exception If there's a general retrieval error
     * @throws PDOException If there's a database error during retrieval
     */
    public function getSettingDetails(): bool|string
    {
        try {
            $strQuery = "CALL sp_getSettings()";
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

    public function getSeoSettings(): bool|string
    {
        try {
            $strQuery = "SELECT * FROM seo_settings";
            $stmt = $this->db->prepare($strQuery);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as &$row) {
                if (!empty($row['breadcrumbs'])) {
                    $row['breadcrumbs'] = json_decode((string) $row['breadcrumbs'], true);
                } else {
                    $row['breadcrumbs'] = [];
                }
            }

            return json_encode($result);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function getSeoSettingByPage(string $pageName): bool|string
    {
        try {
            $strQuery = "SELECT * FROM seo_settings WHERE page_name = :page_name LIMIT 1";
            $stmt = $this->db->prepare($strQuery);
            $stmt->bindParam(':page_name', $pageName);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                if (!empty($result['breadcrumbs'])) {
                    $decoded = json_decode((string) $result['breadcrumbs'], true);
                    $result['breadcrumbs'] = is_array($decoded) ? $decoded : [];
                } else {
                    $result['breadcrumbs'] = [];
                }
            } else {
                $result = [];
            }

            return json_encode($result);
        } catch (PDOException $e) {
            error_log("Database error in getSeoSettingByPage: " . $e->getMessage());
            return json_encode([]);
        }
    }

    public function saveSeoSettings(array $data): bool|string
    {
        try {
            $breadcrumbs = isset($data['breadcrumbs']) ? json_encode($data['breadcrumbs']) : '[]';

            if (empty($data['id'])) {
                $strQuery = "INSERT INTO seo_settings (
                page_name, page_title, meta_description, meta_keywords, 
                canonical_url, og_title, og_description, og_image, 
                breadcrumbs, sub_title
            ) VALUES (
                :page_name, :page_title, :meta_description, :meta_keywords, 
                :canonical_url, :og_title, :og_description, :og_image, 
                :breadcrumbs, :sub_title
            )";
            } else {
                $strQuery = "UPDATE seo_settings SET 
                page_title = :page_title,
                meta_description = :meta_description,
                meta_keywords = :meta_keywords,
                canonical_url = :canonical_url,
                og_title = :og_title,
                og_description = :og_description,
                og_image = :og_image,
                breadcrumbs = :breadcrumbs,
                sub_title = :sub_title
                WHERE id = :id";
            }

            $stmt = $this->db->prepare($strQuery);

            if (!empty($data['id'])) {
                $stmt->bindParam(':id', $data['id']);
            } else {
                $stmt->bindParam(':page_name', $data['page_name']);
            }

            $stmt->bindParam(':page_title', $data['page_title']);
            $stmt->bindParam(':meta_description', $data['meta_description']);
            $stmt->bindParam(':meta_keywords', $data['meta_keywords']);
            $stmt->bindParam(':canonical_url', $data['canonical_url']);
            $stmt->bindParam(':og_title', $data['og_title']);
            $stmt->bindParam(':og_description', $data['og_description']);
            $stmt->bindParam(':og_image', $data['og_image']);
            $stmt->bindParam(':breadcrumbs', $breadcrumbs);
            $stmt->bindParam(':sub_title', $data['sub_title']);

            $stmt->execute();

            return json_encode(['success' => true, 'message' => 'SEO settings saved successfully']);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function getAllPageNames(): bool|string
    {
        try {
            $strQuery = "SELECT DISTINCT page_name FROM seo_settings";
            $stmt = $this->db->prepare($strQuery);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return json_encode($result);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function getPageSettings(): bool|string
    {
        try {
            $strQuery = "SELECT page_name, page_title, sub_title, breadcrumbs FROM seo_settings";
            $stmt = $this->db->prepare($strQuery);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $pageSettings = [];
            foreach ($result as $row) {
                $pageSettings[$row['page_name']] = [
                    'title' => $row['page_title'],
                    'sub_title' => $row['sub_title'],
                    'breadcrumbs' => !empty($row['breadcrumbs']) ? json_decode((string) $row['breadcrumbs'], true) : []
                ];
            }

            return json_encode($pageSettings);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
}
