<?php
require_once "../includes/header.php";
require_once "../includes/functions.php";

requireLogin();

if (!isAdmin()) {
    die("Ingen åtkomst");
}

if (!isset($_GET['id'])) {
    die("Ingen användare vald");
}

$user_id = $_GET['id'];

if ($user_id == $_SESSION['user_id']) {
    die("Du kan inte ta bort dig själv");
}



$stmt = $pdo->prepare("
    DELETE FROM users
    WHERE id = ?
");

$stmt->execute([$user_id]);

header("Location: index.php");
exit;