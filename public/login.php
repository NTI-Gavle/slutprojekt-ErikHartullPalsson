<?php require_once "../config/config.php"; ?>

<form method="POST">
    <input type="text" name="username">
    <input type="password" name="password">
    <button>Login</button>
</form>

<?php
if ($_POST) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit;
    } else {
        echo "Fel login";
    }
}
?>