<?php

require_once "../../config/config.php";
require_once "../../includes/functions.php";

requireLogin();

$data = json_decode(
    file_get_contents("php://input"),
    true
);

if (!$data) {
    die("Ingen data");
}

$game_id =
    $data["game_id"] ?? null;

$result =
    $data["result"] ?? null;

$winner =
    $data["winner"] ?? null;

if (!$game_id || !$result) {
    die("Ogiltig data");
}

$stmt = $pdo->prepare("
    SELECT *
    FROM games
    WHERE id = ?
");

$stmt->execute([$game_id]);

$game = $stmt->fetch();

if (!$game) {
    die("Match finns inte");
}

if ($game["status"] !== "active") {

    echo json_encode([
        "success" => true
    ]);

    exit;
}

$stmt = $pdo->prepare("
    UPDATE games
    SET
        status = ?,
        draw_offer_by = NULL
    WHERE id = ?
");

$stmt->execute([
    $result,
    $game_id
]);

if (!$winner) {

    echo json_encode([
        "success" => true
    ]);

    exit;
}

if ($winner === "white") {

    $winnerId =
        $game["player1_id"];

    $loserId =
        $game["player2_id"];

} elseif ($winner === "black") {

    $winnerId =
        $game["player2_id"];

    $loserId =
        $game["player1_id"];

} else {

    echo json_encode([
        "success" => true
    ]);

    exit;
}

$stmt = $pdo->prepare("
    UPDATE users
    SET wins = wins + 1
    WHERE id = ?
");

$stmt->execute([$winnerId]);

$stmt = $pdo->prepare("
    UPDATE users
    SET losses = losses + 1
    WHERE id = ?
");

$stmt->execute([$loserId]);

echo json_encode([
    "success" => true
]);