<?php require_once "../includes/header.php"; ?>
<?php require_once "../includes/nav.php"; ?>

<h1>Chess App</h1>

<?php if (isset($_SESSION['user_id'])): ?>
    <p>Du är inloggad!</p>
<?php else: ?>
    <p>Vänligen logga in.</p>
<?php endif; ?>

<?php require_once "../includes/footer.php"; ?>

<h2>Öppna spel</h2>

<?php
$stmt = $pdo->query("SELECT posts.*, users.username 
                     FROM posts 
                     JOIN users ON posts.user_id = users.id 
                     WHERE status = 'open'");

$posts = $stmt->fetchAll();

foreach ($posts as $post):
?>

<div>
    <strong><?= htmlspecialchars($post['title']) ?></strong>
    <p>Skapad av: <?= $post['username'] ?></p>
    <a href="join_game.php?id=<?= $post['id'] ?>">Gå med</a>
</div>

<hr>

<?php endforeach; ?>