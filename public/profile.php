<?php require_once "../includes/header.php"; ?>
<?php require_once "../includes/functions.php"; ?>

<?php requireLogin(); ?>

<h1>Din profil</h1>

<p>User ID: <?= $_SESSION['user_id'] ?></p>

<?php require_once "../includes/footer.php"; ?>