<?php

require_once "../includes/header.php";
require_once "../includes/functions.php";

requireLogin();

$user_id = $_SESSION['user_id'];


$stmt = $pdo->prepare("
    SELECT id FROM posts
    WHERE user_id = ?
    AND status = 'open'
");

$stmt->execute([$user_id]);

$openPost = $stmt->fetch();

if ($openPost) {
    die("Du har redan ett öppet spel.");
}


$stmt = $pdo->prepare("
    SELECT id FROM games
    WHERE (
        player1_id = ?
        OR player2_id = ?
    )
    AND status = 'active'
");

$stmt->execute([$user_id, $user_id]);

$activeGame = $stmt->fetch();

if ($activeGame) {
    die("Du är redan i ett aktivt spel.");
}


$stmt = $pdo->prepare("
    INSERT INTO posts (user_id, title, status)
    VALUES (?, ?, 'open')
");

$stmt->execute([
    $user_id,
    "Chess Game"
]);

header("Location: index.php");
exit;