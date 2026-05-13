<?php
require_once "../includes/header.php";
require_once "../includes/functions.php";
require_once "../config/config.php";

requireLogin();

if (!isset($_GET['id'])) {
    die("Ingen match vald");
}

$game_id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT * FROM games
    WHERE id = ?
");

$stmt->execute([$game_id]);

$game = $stmt->fetch();

if (!$game) {
    die("Matchen finns inte");
}

if (
    $_SESSION['user_id'] != $game['player1_id'] &&
    $_SESSION['user_id'] != $game['player2_id']
) {
    die("Du är inte med i matchen");
}
?>

<a href="leave_game.php?id=<?= $game['id'] ?>" class="btn btn-danger mb-3">
    Avsluta match
</a>

<div class="container mt-4">

    <h1 class="mb-4">
        ♟ Chess Game #<?= $game_id ?>
    </h1>

    <p id="turn" class="fw-bold mb-3"></p>

    <div id="board"></div>

</div>

<?php

$playerColor =
    $_SESSION['user_id'] == $game['player1_id']
    ? "white"
    : "black";

?>

<script>
    const gameId = <?= $game_id ?>;
    const PLAYER_COLOR = "<?= $playerColor ?>";
</script>

<script src="/SlutprojektWEBB/public/js/chessboard.min.js"></script>

<script src="/SlutprojektWEBB/public/js/game.js"></script>

<?php require_once "../includes/footer.php"; ?>