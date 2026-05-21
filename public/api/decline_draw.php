<?php

require_once "../../config/config.php";
require_once "../../includes/functions.php";

requireLogin();

$data =
    json_decode(
        file_get_contents("php://input"),
        true
    );

$game_id =
    $data["game_id"] ?? null;

$stmt = $pdo->prepare("
    UPDATE games
    SET draw_offer_by = NULL
    WHERE id = ?
");

$stmt->execute([$game_id]);

echo json_encode([
    "success" => true
]);