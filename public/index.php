<?php require_once "../config/config.php"; ?>

<h1>Chess App</h1>

<?php
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();

foreach ($users as $user) {
    echo $user['username'] . "<br>";
}
?>