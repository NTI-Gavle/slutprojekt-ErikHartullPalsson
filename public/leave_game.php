<?php

require_once "../config/config.php";
require_once "../includes/functions.php";

requireLogin();

if (!isset($_GET['id'])) {
    die("Ingen match vald");
}

$game_id = $_GET['id'];

$stmt = $pdo->prepare("
    UPDATE games
    SET status = 'finished'
    WHERE id = ?
");

$stmt->execute([$game_id]);

header("Location: index.php");
exit;