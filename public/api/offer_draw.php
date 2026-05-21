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

if (!$game_id) {
    die("Ingen match");
}

$stmt = $pdo->prepare("
    SELECT *
    FROM games
    WHERE id = ?
");

$stmt->execute([$game_id]);

$game = $stmt->fetch();

if (!$game) {
    die("Ingen match");
}

// cooldown för inget spamm
if (
    isset($_SESSION["last_draw_offer"]) &&
    time() - $_SESSION["last_draw_offer"] < 15
) {

    die("Cooldown");

}

$_SESSION["last_draw_offer"] = time();

$stmt = $pdo->prepare("
    UPDATE games
    SET draw_offer_by = ?
    WHERE id = ?
");

$stmt->execute([
    $_SESSION["user_id"],
    $game_id
]);

echo json_encode([
    "success" => true
]);