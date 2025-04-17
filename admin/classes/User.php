<?php

class User
{
    protected PDO $db;

    public function __construct(PDO $dbConnection)
    {
        $this->db = $dbConnection;
    }

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
}
