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
}

?>