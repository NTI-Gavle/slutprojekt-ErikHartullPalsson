<a href="index.php">Home</a>
<a href="login.php">Login</a>
<a href="register.php">Register</a>
<a href="profile.php">Profil</a>
<a href="create_game.php">Skapa spel</a>

<nav class="p-3 bg-dark text-white">
    <a class="text-white me-3" href="/index.php">Home</a>

    <?php if (isset($_SESSION['user_id'])): ?>
        <a class="text-white me-3" href="/profile.php">Profil</a>
        <a class="text-white me-3" href="/create_game.php">Skapa spel</a>
        <a class="text-white" href="/logout.php">Logout</a>
    <?php else: ?>
        <a class="text-white me-3" href="/login.php">Login</a>
        <a class="text-white" href="/register.php">Register</a>
    <?php endif; ?>
</nav>