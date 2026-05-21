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

    <div class="row g-4 justify-content-center">

        <div class="col-xl-8">

            <div id="board-container">

                <div id="board"></div>

            </div>

        </div>

        <div class="col-xl-3">

            <div class="card shadow-lg border-0 p-4 game-sidebar">

                <h2 class="mb-3">
                    ♟ Match #<?= $game_id ?>
                </h2>

                <p id="turn" class="fw-bold fs-4 mb-4"></p>

                <div class="d-grid gap-3">

                    <button id="flip-board" class="btn btn-dark">
                        Flippa brädet
                    </button>

                    <button id="resign-btn" class="btn btn-danger">
                        Ge upp
                    </button>

                    <button id="draw-btn" class="btn btn-warning">
                        Föreslå remi
                    </button>
                    <div id="draw-notification"></div>

                </div>

                <hr class="my-4">

                <h5>Du spelar som</h5>

                <p class="fs-5 fw-bold text-capitalize">
                    <?= $playerColor ?>
                </p>

            </div>

        </div>

    </div>

    <div class="row justify-content-center mt-4">

        <div class="col-xl-11">

            <div class="card border-0 shadow-lg p-4 chat-card">

                <h3 class="mb-4">
                    Chat
                </h3>

                <div id="chat-box"></div>

                <div class="input-group mt-3">

                    <input type="text" id="chat-input" class="form-control" placeholder="Skriv ett meddelande...">

                    <button id="send-chat" class="btn btn-success">
                        Skicka
                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

<style>
    #board-container {

        background: white;
        padding: 20px;
        border-radius: 14px;

        box-shadow:
            0 10px 25px rgba(0, 0, 0, 0.35);

        width: fit-content;
        margin: auto;
    }

    #board {

        width: 560px;
        height: 560px;

    }

    .game-sidebar,
    .chat-card {

        background:
            linear-gradient(145deg,
                #1b2230,
                #151b26);

        color: white;

        border-radius: 14px;
    }

    #chat-box {

        height: 300px;
        overflow-y: auto;

        background: rgba(255, 255, 255, 0.03);

        border-radius: 10px;

        padding: 15px;
    }

    .chat-message {

        margin-bottom: 12px;
        padding: 10px 14px;

        border-radius: 10px;

        background: rgba(255, 255, 255, 0.06);
    }

    .chat-user {

        font-weight: bold;
        color: #4dabff;
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

    #game-over-overlay {

        position: fixed;

        inset: 0;

        background:
            rgba(0, 0, 0, 0.7);

        display: none;

        justify-content: center;
        align-items: center;

        z-index: 9999;
    }

    #game-over-modal {

        background: #1f2937;

        color: white;

        padding: 40px;

        border-radius: 20px;

        width: 400px;

        max-width: 90%;

        text-align: center;

        box-shadow:
            0 10px 30px rgba(0, 0, 0, 0.5);
    }

    #game-over-title {

        font-size: 42px;

        margin-bottom: 20px;
    }

    #game-over-text {

        font-size: 20px;

        margin-bottom: 30px;

        color: #d1d5db;
    }

    .game-over-buttons {

        display: flex;

        gap: 15px;

        justify-content: center;
    }
</style>

<script>

    const gameId = <?= $game_id ?>;
    const PLAYER_COLOR = "<?= $playerColor ?>";
    const CURRENT_USER_ID = <?= $_SESSION['user_id'] ?>;

</script>

<script src="/SlutprojektWEBB/public/js/chessboard.min.js"></script>

<script src="/SlutprojektWEBB/public/js/game.js"></script>

<div id="game-over-overlay">

    <div id="game-over-modal">

        <h1 id="game-over-title">
            Game Over
        </h1>

        <p id="game-over-text">
            Någon vann kanske
        </p>

        <div class="game-over-buttons">

            <button id="close-game-over" class="btn btn-secondary">
                Stäng
            </button>

            <a href="index.php" class="btn btn-success">
                Till lobby
            </a>

        </div>

    </div>

</div>

<?php require_once "../includes/footer.php"; ?>