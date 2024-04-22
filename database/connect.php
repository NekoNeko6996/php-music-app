<?php
include __DIR__ . '/../config/config.php';

try {
    $connect = new PDO("mysql:host=$hostname;port=$port;dbname=$database", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connect->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    die('[sql] Error connect' . $e->getMessage());
}
