<?php
header('Content-Type: application/json');
require_once "../../config/config.php";

$game_id = $_GET['game_id'];
$last_id = $_GET['last_id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT * FROM moves 
    WHERE game_id = ? AND id > ?
    ORDER BY id ASC
");

$stmt->execute([$game_id, $last_id]);

$moves = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($moves);