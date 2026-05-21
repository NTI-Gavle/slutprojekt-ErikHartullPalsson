<?php

require_once "../../config/config.php";
require_once "../../includes/functions.php";

requireLogin();

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    die("Ingen data");
}

$game_id = $data["game_id"] ?? null;
$message = trim($data["message"] ?? "");

if (!$game_id || $message === "") {
    die("Ogiltigt meddelande");
}

if (mb_strlen($message) > 300) {
    die("Meddelandet är för långt");
}

$stmt = $pdo->prepare("
    INSERT INTO messages (
        game_id,
        user_id,
        message
    )
    VALUES (?, ?, ?)
");

$stmt->execute([
    $game_id,
    $_SESSION['user_id'],
    $message
]);

echo json_encode([
    "success" => true
]);