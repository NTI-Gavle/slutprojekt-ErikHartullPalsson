<?php
require_once "../../config/config.php";

$data = json_decode(file_get_contents("php://input"), true);

$game_id = $data['game_id'] ?? null;
$from = $data['from'] ?? null;
$to = $data['to'] ?? null;
$fen = $data['fen'] ?? null;

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id || !$game_id) {
    exit("Error");
}

// Sparar
$stmt = $pdo->prepare("
    INSERT INTO moves (game_id, user_id, from_square, to_square, fen)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([$game_id, $user_id, $from, $to, $fen]);

echo "OK";