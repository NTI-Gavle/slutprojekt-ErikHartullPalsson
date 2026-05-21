<?php
require_once "../includes/header.php";
require_once "../includes/functions.php";
require_once "../config/config.php";

requireLogin();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    rateLimit("create_game", 3);

    $title =
        trim($_POST["title"] ?? "");

    $color =
        $_POST["color"] ?? "random";

    if (strlen($title) < 3 || strlen($title) > 40) {
        die("Spelnamn måste vara mellan 3 och 40 tecken.");
    }

    if (!in_array($color, ["white", "black", "random"])) {
        die("Ogiltig färg.");
    }

    // random tror inte den ens fungerar men kanske. 
    if ($color === "random") {

        $color =
            rand(0, 1)
                ? "white"
                : "black";
    }

    if ($color === "white") {

        $player1_id = $_SESSION["user_id"];
        $preferred_color = "white";

    } else {

        $player1_id = $_SESSION["user_id"];
        $preferred_color = "black";
    }

    $stmt = $pdo->prepare("
        INSERT INTO posts (
            user_id,
            title,
            preferred_color,
            status
        )
        VALUES (?, ?, ?, 'open')
    ");

    $stmt->execute([
        $player1_id,
        htmlspecialchars($title),
        $preferred_color
    ]);

    header("Location: index.php");
    exit;
}
?>

<div class="container mt-5">

    <div class="card shadow-lg border-0 p-5 bg-dark text-white">

        <h1 class="mb-5 text-center">
            Skapa nytt spel
        </h1>

        <form method="POST">

            <div class="mb-5">

                <label class="form-label fs-5 mb-3">
                    Spelnamn
                </label>

                <input
                    type="text"
                    name="title"
                    class="form-control form-control-lg bg-dark text-white border-secondary"
                    placeholder="Skapa en match, inte vet jag. Bara gör något."
                    maxlength="40"
                    required
                >

            </div>

            <div class="mb-5">

                <label class="form-label fs-5 mb-4 d-block">
                    Välj färg
                </label>

                <div class="color-options">

                    <label class="color-card">

                        <input
                            type="radio"
                            name="color"
                            value="white"
                            required
                        >

                        <div class="color-content">

                            <div class="piece-preview white-piece">
                                ♔
                            </div>

                            <span>Vit</span>

                        </div>

                    </label>

                    <label class="color-card">

                        <input
                            type="radio"
                            name="color"
                            value="black"
                        >

                        <div class="color-content">

                            <div class="piece-preview black-piece">
                                ♚
                            </div>

                            <span>Svart</span>

                        </div>

                    </label>

                    <label class="color-card">

                        <input
                            type="radio"
                            name="color"
                            value="random"
                            checked
                        >

                        <div class="color-content">

                            <div class="piece-preview">
                                🎲
                            </div>

                            <span>Random</span>

                        </div>

                    </label>

                </div>

            </div>

            <div class="d-grid">

                <button
                    type="submit"
                    class="btn btn-success btn-lg"
                >
                    Skapa spel
                </button>

            </div>

        </form>

    </div>

</div>

<style>

.color-options {

    display: grid;

    grid-template-columns:
        repeat(auto-fit, minmax(160px, 1fr));

    gap: 20px;
}

.color-card {

    position: relative;
    cursor: pointer;
}

.color-card input {

    display: none;
}

.color-content {

    background: #1e2530;

    border: 2px solid transparent;

    border-radius: 16px;

    padding: 30px 20px;

    text-align: center;

    transition: 0.2s ease;

    user-select: none;
}

.color-content:hover {

    transform: translateY(-3px);

    border-color: #3b82f6;

    background: #273142;
}

.color-card input:checked + .color-content {

    border-color: #22c55e;

    background: #1f3a2b;

    box-shadow:
        0 0 15px rgba(34,197,94,0.4);
}

.piece-preview {

    font-size: 52px;

    margin-bottom: 12px;
}

.white-piece {

    color: white;

    text-shadow:
        0 0 6px rgba(255,255,255,0.6);
}

.black-piece {

    color: black;

    text-shadow:
        0 0 4px rgba(255,255,255,0.2);
}

.color-content span {

    font-size: 22px;
    font-weight: 600;
}

</style>

<?php require_once "../includes/footer.php"; ?>