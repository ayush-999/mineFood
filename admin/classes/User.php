<?php

class User
{
    protected PDO $db;

    public function __construct(PDO $dbConnection)
    {
        $this->db = $dbConnection;
    }
}
