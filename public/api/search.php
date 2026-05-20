<?php

require_once "../../config/config.php";

$query =
    trim($_GET['q'] ?? '');

if ($query === '') {

    echo json_encode([]);

    exit;
}

$results = [];

$stmt = $pdo->prepare("
    SELECT id, username
    FROM users
    WHERE username LIKE ?
    LIMIT 5
");

$stmt->execute(["%$query%"]);

$users = $stmt->fetchAll();

foreach ($users as $user) {

    $results[] = [
        "type" => "user",
        "id" => $user["id"],
        "username" => $user["username"]
    ];
}

$stmt = $pdo->prepare("
    SELECT id, title
    FROM posts
    WHERE status = 'open'
    AND title LIKE ?
    LIMIT 5
");

$stmt->execute(["%$query%"]);

$games = $stmt->fetchAll();

foreach ($games as $game) {

    $results[] = [
        "type" => "game",
        "id" => $game["id"],
        "title" => $game["title"]
    ];
}

header("Content-Type: application/json");

echo json_encode($results);