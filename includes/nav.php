<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="index.php">♟️ Chess</a>

<div class="ms-auto">

    <?php if (isset($_SESSION['user_id'])): ?>

        <a class="btn btn-sm btn-outline-light me-2" href="index.php">Home</a>
        <a class="btn btn-sm btn-outline-light me-2" href="profile.php">Profil</a>
        <a class="btn btn-sm btn-success me-2" href="create_game.php">Spel</a>
        <a class="btn btn-sm btn-danger" href="logout.php">Logga ut</a>

    <?php else: ?>

        <a class="btn btn-sm btn-outline-light me-2" href="login.php">Logga in</a>
        <a class="btn btn-sm btn-primary" href="register.php">Registera</a>

    <?php endif; ?>

</div>

</nav>
