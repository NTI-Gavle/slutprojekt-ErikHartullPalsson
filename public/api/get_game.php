<?php

require_once "../../config/config.php";

if (!isset($_GET["id"])) {
    die("Ingen match");
}

$game_id = $_GET["id"];

$stmt = $pdo->prepare("
    SELECT *
    FROM games
    WHERE id = ?
");

$stmt->execute([$game_id]);

$game = $stmt->fetch();

echo json_encode($game);