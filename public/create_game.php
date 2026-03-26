<?php require_once "../includes/header.php"; ?>
<?php require_once "../includes/functions.php"; ?>
<?php requireLogin(); ?>

<h2>Skapa spel</h2>

<form method="POST">
    <input type="text" name="title" placeholder="Game title">
    <button>Skapa</button>
</form>

<?php
if ($_POST) {
    $stmt = $pdo->prepare("INSERT INTO posts (user_id, title) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $_POST['title']]);

    echo "Spel skapad!";
}
?>

<?php require_once "../includes/footer.php"; ?>