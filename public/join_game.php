<?php

require_once "../config/config.php";
require_once "../includes/functions.php";

requireLogin();

if (!isset($_GET['id'])) {
    die("Ingen post vald");
}

$post_id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT * 
    FROM posts 
    WHERE id = ?
");

$stmt->execute([$post_id]);

$post = $stmt->fetch();

if (!$post || $post['status'] !== 'open') {
    die("Spelet är inte tillgängligt");
}

if ($post['user_id'] == $_SESSION['user_id']) {
    die("Du kan inte joina ditt eget spel");
}

$pdo->beginTransaction();

$stmt = $pdo->prepare("
    SELECT status 
    FROM posts 
    WHERE id = ? 
    FOR UPDATE
");

$stmt->execute([$post_id]);

$current = $stmt->fetch();

if (!$current || $current['status'] !== 'open') {
    $pdo->rollBack();
    die("Spelet blev precis taget av någon annan");
}

$stmt = $pdo->prepare("
    INSERT INTO games (
        player1_id,
        player2_id,
        status,
        turn
    )
    VALUES (?, ?, 'active', 'player1')
");

$stmt->execute([
    $post['user_id'],
    $_SESSION['user_id']
]);

$game_id = $pdo->lastInsertId();

$initialBoardState = [

    "boardState" => [

        "r","n","b","q","k","b","n","r",
        "p","p","p","p","p","p","p","p",

        "","","","","","","","",
        "","","","","","","","",
        "","","","","","","","",
        "","","","","","","","",

        "P","P","P","P","P","P","P","P",
        "R","N","B","Q","K","B","N","R"
    ],

    "currentTurn" => "white",

    "moved" => [
        "K" => false,
        "R1" => false,
        "R2" => false,
        "k" => false,
        "r1" => false,
        "r2" => false
    ],

    "enPassantTarget" => null,

    "lastMove" => [
        "from" => null,
        "to" => null
    ]
];

$stmt = $pdo->prepare("
    INSERT INTO moves (
        game_id,
        user_id,
        board_state
    )
    VALUES (?, ?, ?)
");

$stmt->execute([
    $game_id,
    $_SESSION['user_id'],
    json_encode($initialBoardState)
]);

$stmt = $pdo->prepare("
    UPDATE posts
    SET status = 'closed'
    WHERE id = ?
");

$stmt->execute([$post_id]);

$pdo->commit();

header("Location: game.php?id=" . $game_id);
exit;