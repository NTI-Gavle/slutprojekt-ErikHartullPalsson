<?php

require_once "../../config/config.php";

$game_id = $_GET['game_id'];

$stmt = $pdo->prepare("
    SELECT *
    FROM moves
    WHERE game_id = ?
    ORDER BY id DESC
    LIMIT 1
");

$stmt->execute([$game_id]);

$move = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');

echo json_encode($move);