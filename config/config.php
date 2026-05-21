<?php
session_start();

$host = "127.0.0.1";
$db   = "chess_app";
$user = "root";
$pass = "root";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}