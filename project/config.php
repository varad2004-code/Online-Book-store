<?php
// PostgreSQL database credentials
$host = 'localhost'; 
$db = 'shop_db';
$user = 'postgres';
$password = 'varad2004';

try {
    $conn = new PDO("pgsql:host=$host;dbname=$db", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>