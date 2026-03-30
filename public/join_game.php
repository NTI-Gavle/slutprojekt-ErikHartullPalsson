<?php
require_once "../includes/header.php";
require_once "../includes/functions.php";

requireLogin();

if (!isset($_GET['id'])) {
    die("Ingen post vald");
}

$post_id = $_GET['id'];

// Hämtar
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post || $post['status'] !== 'open') {
    die("Spelet är inte tillgängligt");
}


$stmt = $pdo->prepare("
    INSERT INTO games (player1_id, player2_id, status, turn)
    VALUES (?, ?, 'active', 'player1')
");

$stmt->execute([
    $post['user_id'],      
    $_SESSION['user_id']   
]);

$game_id = $pdo->lastInsertId();

$startFen = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1";

$stmt = $pdo->prepare("
    INSERT INTO moves (game_id, user_id, fen)
    VALUES (?, ?, ?)
");

$stmt->execute([$game_id, $_SESSION['user_id'], $startFen]);

$stmt = $pdo->prepare("UPDATE posts SET status = 'closed' WHERE id = ?");
$stmt->execute([$post_id]);

header("Location: game.php?id=" . $game_id);
exit;