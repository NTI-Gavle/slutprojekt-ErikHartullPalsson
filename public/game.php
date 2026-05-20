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

$playerColor =
    $_SESSION['user_id'] == $game['player1_id']
    ? "white"
    : "black";
?>

<div class="container-fluid mt-4">

    <div class="row justify-content-center">

        <!-- bräd -->
        <div class="col-lg-7 d-flex justify-content-center mb-4">

            <div>

                <div id="board-container">

                    <div id="board"></div>

                </div>

            </div>

        </div>

        <!-- panel -->
        <div class="col-lg-3">

            <div class="card shadow-lg border-0 p-4 game-sidebar">

                <h1 class="mb-3">
                    ♟ Chess Game #<?= $game_id ?>
                </h1>

                <p id="turn" class="fw-bold fs-4 mb-4"></p>

                <div class="d-grid gap-3">

                    <button id="flip-board"
                            class="btn btn-dark">
                        Flippa brädet
                    </button>

                    <a href="leave_game.php?id=<?= $game['id'] ?>"
                       class="btn btn-danger">
                        Avsluta match
                    </a>

                </div>

                <hr class="my-4">

                <div>

                    <h5>Du spelar som</h5>

                    <p class="fs-5 fw-bold text-capitalize">
                        <?= $playerColor ?>
                    </p>

                    <p>
                        Det blir engelska för att det är så jag har kodad det 😭
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<style>

#board-container {

    background: white;
    padding: 20px;
    border-radius: 10px;

    box-shadow:
        0 5px 10px rgba(0,0,0,0.2);

}

#board {

    width: 560px;
    height: 560px;

}

.game-sidebar {

    border-radius: 10px;
}

@media (max-width: 991px) {

    #board {

        width: 100%;
        height: auto;
        aspect-ratio: 1 / 1;
    }

    #board-container {

        width: 100%;
    }
}

</style>

<script>
    const gameId = <?= $game_id ?>;
    const PLAYER_COLOR = "<?= $playerColor ?>";
</script>

<script src="/SlutprojektWEBB/public/js/chessboard.min.js"></script>

<script src="/SlutprojektWEBB/public/js/game.js"></script>

<?php require_once "../includes/footer.php"; ?>