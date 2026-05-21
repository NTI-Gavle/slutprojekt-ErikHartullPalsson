<?php

require_once "../includes/header.php";
require_once "../config/config.php";
require_once "../includes/functions.php";

requireLogin();

$profile_id =
    $_GET['id'] ?? $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT *
    FROM users
    WHERE id = ?
");

$stmt->execute([$profile_id]);

$user = $stmt->fetch();

if (!$user) {
    die("Användaren finns inte");
}

// senaste matcher
$stmt = $pdo->prepare("
    SELECT *
    FROM games
    WHERE player1_id = ?
    OR player2_id = ?
    ORDER BY id DESC
    LIMIT 10
");

$stmt->execute([$profile_id, $profile_id]);

$games = $stmt->fetchAll();

?>

<div class="container mt-5">

    <div class="card shadow p-4">

        <div class="d-flex justify-content-between align-items-start">

            <div>

                <h1 class="mb-2">

                    👤 <?= htmlspecialchars($user['username']) ?>

                    <?php if ($user['role'] === 'admin'): ?>
                        <span class="badge bg-danger">
                            ADMIN
                        </span>
                    <?php endif; ?>

                </h1>

                <p class="text-muted">
                    Konto skapades:
                    <?= date("Y-m-d", strtotime($user['created_at'])) ?>
                </p>

            </div>

            <div class="d-flex gap-2">

                <?php if ($profile_id == $_SESSION['user_id']): ?>

                    <a href="edit_profile.php" class="btn btn-dark">
                        Redigera profil
                    </a>

                <?php endif; ?>

                <?php if (
                    isAdmin() &&
                    $profile_id != $_SESSION['user_id']
                ): ?>

                    <a
                        href="delete_user.php?id=<?= $user['id'] ?>"
                        class="btn btn-danger"
                        onclick="return confirm('Ta bort användaren?')"
                    >
                        Ta bort användare
                    </a>

                <?php endif; ?>

            </div>

        </div>

        <hr>

        <h4>Beskrivning</h4>

        <p>
            <?= nl2br(htmlspecialchars($user['bio'] ?? 'Ingen beskrivning än.')) ?>
        </p>

        <hr>

        <div class="row text-center">

            <div class="col">
                <h3><?= $user['wins'] ?></h3>
                <p class="text-success">W´s</p>
            </div>

            <div class="col">
                <h3><?= $user['losses'] ?></h3>
                <p class="text-danger">L´s</p>
            </div>

            <div class="col">
                <h3><?= count($games) ?></h3>
                <p>Matcher</p>
            </div>

        </div>

    </div>

    <div class="card shadow p-4 mt-4">

        <h3 class="mb-4">
            Senaste matcher
        </h3>

        <?php if (empty($games)): ?>

            <p class="text-muted">
                Inga matcher ännu.
            </p>

        <?php else: ?>

            <div class="list-group">

                <?php foreach ($games as $game): ?>

                    <a
                        href="game.php?id=<?= $game['id'] ?>"
                        class="list-group-item list-group-item-action"
                    >

                        Match #<?= $game['id'] ?>

                        —
                        <?= htmlspecialchars($game['status']) ?>

                    </a>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

</div>

<?php require_once "../includes/footer.php"; ?>