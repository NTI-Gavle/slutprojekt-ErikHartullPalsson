<?php
require_once "../includes/header.php";
require_once "../includes/functions.php";

requireLogin();


if (!isset($_GET['id'])) {
    die("Ingen match vald");
}

$game_id = $_GET['id'];



// Hämtar 
$stmt = $pdo->prepare("SELECT * FROM games WHERE id = ?");
$stmt->execute([$game_id]);
$game = $stmt->fetch();

if (!$game) {
    die("Game finns inte");
}

// Kollar  user i matchen
if ($_SESSION['user_id'] != $game['player1_id'] &&
    $_SESSION['user_id'] != $game['player2_id']) {
    die("Du är inte med i denna match");
}
?>

<h1>Chess Game #<?= $game_id ?></h1>

<div id="board" style="width: 400px;"></div>

<div id="turn"></div>


<h3>Chat</h3>
<div id="chat-box"></div>

<input type="text" id="chat-input">
<button onclick="sendChat()">Skicka</button>

<script src="/SlutprojektWEBB/public/js/chessboard.min.js"></script>
<script src="/SlutprojektWEBB/public/js/game.js"></script>

<script src="js/game.js"></script>

<?php require_once "../includes/footer.php"; ?>