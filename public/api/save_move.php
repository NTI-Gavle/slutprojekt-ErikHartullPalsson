<?php

require_once "../../config/config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode([
        "success" => false
    ]);
    exit;
}

$game_id = $data["game_id"];
$user_id = $_SESSION["user_id"];

$board_state = json_encode($data["board_state"]);

$stmt = $pdo->prepare("
    INSERT INTO moves (
        game_id,
        user_id,
        board_state
    )
    VALUES (?, ?, ?)
");

$stmt->execute([
    $game_id,
    $user_id,
    $board_state
]);

echo json_encode([
    "success" => true
]);