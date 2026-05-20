<?php
require_once "../includes/header.php";
require_once "../includes/functions.php";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if (!rateLimit("login", 3)) {
    $error = "För många försök, skill issue ;-;.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !$error) {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $pdo->prepare("
        SELECT * FROM users
        WHERE username = ?
    ");

    $stmt->execute([$username]);

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];

        header("Location: index.php");
        exit;

    } else {
        $error = "Fel användarnamn eller lösenord";
    }
}
?>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-body p-4">

                    <h1 class="text-center mb-4">
                        Logga in
                    </h1>

                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">
                                Användarnamn
                            </label>

                            <input
                                type="text"
                                name="username"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label class="form-label">
                                Lösenord
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required
                            >
                        </div>

                        <button class="btn btn-dark w-100">
                            Logga in
                        </button>

                    </form>

                    <div class="text-center mt-3">

                        <a href="register.php">
                            Har du inget konto?
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php require_once "../includes/footer.php"; ?>