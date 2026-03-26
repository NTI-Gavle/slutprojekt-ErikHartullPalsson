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

// Skapar 
$stmt = $pdo->prepare("
    INSERT INTO games (player1_id, player2_id, status, turn)
    VALUES (?, ?, 'active', 'player1')
");

$stmt->execute([
    $post['user_id'],      
    $_SESSION['user_id']   
]);

$game_id = $pdo->lastInsertId();

// Stänger 
$stmt = $pdo->prepare("UPDATE posts SET status = 'closed' WHERE id = ?");
$stmt->execute([$post_id]);

// Redirect 
header("Location: game.php?id=" . $game_id);
exit;