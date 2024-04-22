<?php
require_once __DIR__ . "/../vendor/autoload.php";
use Dotenv\Dotenv as Dotenv;

ini_set('display_errors', '1');
ini_set('display_startup_error', '1');
error_reporting(E_ALL);

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$dbHost = $_ENV['DB_HOST'];
$dbUser = $_ENV['DB_USER'];
$dbPassword = $_ENV['DB_PASSWORD'];
$dbName = $_ENV['DB_NAME'];

try {
    $conn = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}