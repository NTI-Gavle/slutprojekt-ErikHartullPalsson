<?php
require_once "../includes/header.php";
?>

<div class="container mt-5">

<div class="text-center mb-5">
    <h1>Chess</h1>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p class="text-success">inloggad</p>
    <?php else: ?>
        <p class="text-warning">logga in</p>
    <?php endif; ?>
</div>

<div class="d-flex justify-content-center gap-3 mb-5">

    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="create_game.php" class="btn btn-success">
            Skapa spel
        </a>

        <a href="profile.php" class="btn btn-dark">
            Profil
        </a>

        <a href="logout.php" class="btn btn-danger">
            Logga ut
        </a>
    <?php else: ?>
        <a href="login.php" class="btn btn-dark">
            Logga in
        </a>

        <a href="register.php" class="btn btn-outline-primary">
            Registrera
        </a>
    <?php endif; ?>

</div>

<h2 class="mb-4 text-center">Öppna spel</h2>

<div class="row">

    <?php
    $stmt = $pdo->query("
        SELECT posts.*, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        WHERE status = 'open'
    ");

    $posts = $stmt->fetchAll();

    if (empty($posts)):
    ?>

        <p class="text-center text-muted">Inga öppna spel just nu</p>

    <?php else: ?>

        <?php foreach ($posts as $post): ?>

            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white h-100">

                    <div class="card-body text-center">
                        <h5 class="card-title">
                            <?= htmlspecialchars($post['title']) ?>
                        </h5>

                        <p class="card-text">
                            Skapad av: <?= htmlspecialchars($post['username']) ?>
                        </p>

                        <a href="join_game.php?id=<?= $post['id'] ?>" 
                           class="btn btn-primary">
                            Gå med
                        </a>
                    </div>

                </div>
            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

</div>

<?php require_once "../includes/footer.php"; ?>
