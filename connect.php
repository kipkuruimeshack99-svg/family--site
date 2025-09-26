<?php
$host = "localhost";
$db   = "familysite";
$user = "root";
$pass = "1234";  

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connected successfully!";
} catch (PDOException $e) {
    die("❌ DB connection failed: " . $e->getMessage());
}
?>
