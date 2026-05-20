<?php
$pageTitle = "Contact";
require_once __DIR__ . '/../includes/header.php';

$name = $email = $message = '';
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email is required.";
    }
    if (empty($message)) {
        $errors[] = "Message cannot be empty.";
    }

    // If no errors, you can send email or save to DB
    if (!$errors) {
        // Example: send email (requires proper mail setup)
        // mail("you@example.com", "Contact Form Message from $name", $message, "From:$email");

        $success = true;
        $name = $email = $message = ''; // clear form
    }
}
?>

<h2>Contact Us</h2>

<?php if ($success): ?>
    <p class="success-message">Thank you! Your message has been sent.</p>
<?php endif; ?>

<?php if ($errors): ?>
    <ul class="error-messages">
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="contact.php" method="post" class="contact-form">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" value="<?= htmlspecialchars($name) ?>">

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>">

    <label for="message">Message:</label>
    <textarea name="message" id="message"><?= htmlspecialchars($message) ?></textarea>

    <button type="submit">Send</button>
</form>

<?php
require_once __DIR__ . '/../includes/footer.php';
