<?php
require_once "../../config/config.php";

$data = json_decode(file_get_contents("php://input"), true);

$game_id = $data['game_id'];
$from = $data['from'];
$to = $data['to'];
$fen = $data['fen'];

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    exit("Not logged in");
}

// validera sen kanske

// Sparar
$stmt = $pdo->prepare("
    INSERT INTO moves (game_id, user_id, from_square, to_square, fen)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([$game_id, $user_id, $from, $to, $fen]);