<?php

require_once "../includes/header.php";
require_once "../config/config.php";
require_once "../includes/functions.php";

requireLogin();

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT *
    FROM users
    WHERE id = ?
");

$stmt->execute([$user_id]);

$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $bio = trim($_POST['bio']);

    $stmt = $pdo->prepare("
        UPDATE users
        SET bio = ?
        WHERE id = ?
    ");

    $stmt->execute([$bio, $user_id]);

    header("Location: profile.php");

    exit;
}

?>

<div class="container mt-5">

    <div class="card shadow p-4">

        <h1 class="mb-4">
            Redigera profil
        </h1>

        <form method="POST">

            <div class="mb-3">

                <label class="form-label">
                    Beskrivning
                </label>

                <textarea
                    name="bio"
                    class="form-control"
                    rows="5"
                ><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>

            </div>

            <button class="btn btn-success">
                Spara
            </button>

        </form>

    </div>

</div>

<?php require_once "../includes/footer.php"; ?>