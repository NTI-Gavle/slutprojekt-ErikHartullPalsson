<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">

    <a class="navbar-brand fw-bold" href="index.php">
        ♟ Chess
    </a>

    <div class="mx-auto position-relative" style="width: 400px;">

        <input
            type="text"
            id="search-input"
            class="form-control"
            placeholder="Sök"
        >

        <div
            id="search-results"
            class="list-group position-absolute w-100"
            style="z-index: 999;"
        ></div>

    </div>

    <div class="d-flex gap-2">

        <a href="index.php" class="btn btn-outline-light">
            Home
        </a>

        <?php if (isset($_SESSION['user_id'])): ?>

            <a href="profile.php" class="btn btn-outline-light">
                Profil
            </a>

            <a href="create_game.php" class="btn btn-success">
                Skapa spel
            </a>

            <a href="logout.php" class="btn btn-danger">
                Logga ut
            </a>

        <?php else: ?>

            <a href="login.php" class="btn btn-outline-light">
                Logga in
            </a>

        <?php endif; ?>

    </div>

</nav>

<script src="/SlutprojektWEBB/public/js/search.js"></script>