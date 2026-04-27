<?php require_once "../config/config.php"; ?>

<form method="POST">
    <input type="text" maxlength="50" name="username" placeholder="Användarnamn">
    <input type="password" maxlength="255" name="password" placeholder="Lösenord">
    <button>Registera</button>
</form>

<?php
if ($_POST) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);

    echo "User created!";
}
?>