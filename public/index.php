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

    <div id="games-list" class="row"></div>

</div>

</div>

<div id="lobby-message" class="lobby-message">
    <div class="lobby-message-box">

        <h4 id="lobby-message-text"></h4>

        <button onclick="closeLobbyMessage()" class="btn btn-light mt-3">
            Stäng
        </button>

    </div>
</div>

<script>
    const CURRENT_USER_ID =
        <?= $_SESSION['user_id'] ?? 'null' ?>;
</script>

<script src="js/lobby.js"></script>

<?php require_once "../includes/footer.php"; ?>