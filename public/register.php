<?php
require_once "../includes/header.php";
require_once "../includes/functions.php";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if (!rateLimit("register", 5)) {
    $error = "För snabb i sängen där";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !$error) {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // anti jonte och nicky validation
    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {

        $error =
            "Användarnamn får ENDAST innehålla bokstäver, siffror och underscore (nicky och jonte specifikt) och måste vara 3-20 tecken.";

    }

    elseif (strlen($password) < 6) {

        $error =
            "Lösenordet måste vara minst 6 tecken.";

    }

    if (!$error) {

        $stmt = $pdo->prepare("
            SELECT id
            FROM users
            WHERE username = ?
        ");

        $stmt->execute([$username]);

        if ($stmt->fetch()) {

            $error =
                "Användarnamnet används redan.";

        }
    }

    if (!$error) {

        $hashed =
            password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO users (
                username,
                password
            )
            VALUES (?, ?)
        ");

        $stmt->execute([
            $username,
            $hashed
        ]);

        header("Location: login.php");
        exit;
    }
}
?>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-body p-4">

                    <h1 class="text-center mb-4">
                        Registrera
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
                                maxlength="20"
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
                                minlength="6"
                                required
                            >

                        </div>

                        <button class="btn btn-success w-100">
                            Skapa konto
                        </button>

                    </form>

                    <div class="text-center mt-3">

                        <a href="login.php">
                            Har du redan konto?
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php require_once "../includes/footer.php"; ?>