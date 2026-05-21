<?php

require_once "../../config/config.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {

    echo json_encode(null);
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT *
    FROM games
    WHERE (
        player1_id = ?
        OR player2_id = ?
    )
    AND status = 'active'
    ORDER BY id DESC
    LIMIT 1
");

$stmt->execute([
    $user_id,
    $user_id
]);

$game = $stmt->fetch();

if (!$game) {

    echo json_encode(null);
    exit;
}

if (
    $game["status"] !== "active"
) {

    echo json_encode(null);
    exit;
}

echo json_encode([
    "id" => $game["id"]
]);