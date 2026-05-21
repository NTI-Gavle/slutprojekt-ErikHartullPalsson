<?php
require_once "../../config/config.php";

$stmt = $pdo->query("
    SELECT posts.*, users.username 
    FROM posts 
    JOIN users ON posts.user_id = users.id 
    WHERE status = 'open'
");

echo json_encode($stmt->fetchAll());