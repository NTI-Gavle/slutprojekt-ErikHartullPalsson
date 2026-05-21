<?php

require_once "../../config/config.php";

if (!isset($_GET["game_id"])) {
    die("Ingen match");
}

$game_id = $_GET["game_id"];

$stmt = $pdo->prepare("
    SELECT
        messages.*,
        users.username
    FROM messages

    JOIN users
    ON messages.user_id = users.id

    WHERE game_id = ?

    ORDER BY messages.id ASC
");

$stmt->execute([$game_id]);

$messages = $stmt->fetchAll();

echo json_encode($messages);