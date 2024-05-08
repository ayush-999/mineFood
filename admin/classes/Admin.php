<?php
class Admin
{
    protected $db;

    public function __construct(PDO $dbConnection)
    {
        $this->db = $dbConnection;
    }

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

    public function get_all_categories()
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

    public function delete_category($categoryId)
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

    public function add_category($categoryName, $orderNumber, $status, $added_on)
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

    public function update_category($categoryId, $categoryName, $orderNumber, $status, $added_on)
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

}

?>