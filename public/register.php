<?php

session_set_cookie_params([
    'httponly' => true,
    'secure' => false,
    'samesite' => 'Lax'
]);

session_start();

require_once '../includes/csrf.php';
require_once '../config/database.php';

$username = '';
$email = '';

$errors = [];
$success = '';

$csrfToken = generateCsrfToken();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $submittedToken = $_POST['csrf_token'] ?? '';

    // CSRF validation

    if (!verifyCsrfToken($submittedToken)) {

        $errors[] = 'Invalid security token.';

    } else {

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validation

        if ($username === '') {
            $errors[] = 'Username is required.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }

        // Create account

        if (empty($errors)) {

            try {

                // Check duplicate email

                $checkEmail = $pdo->prepare(
                    "SELECT id FROM users WHERE email = :email LIMIT 1"
                );

                $checkEmail->execute([
                    ':email' => $email
                ]);

                if ($checkEmail->fetch()) {

                    $errors[] = 'This email is already registered.';

                } else {

                    // Check duplicate username

                    $checkUsername = $pdo->prepare(
                        "SELECT id FROM users WHERE username = :username LIMIT 1"
                    );

                    $checkUsername->execute([
                        ':username' => $username
                    ]);

                    if ($checkUsername->fetch()) {

                        $errors[] = 'This username is already taken.';

                    } else {

                        // Hash password

                        $hashedPassword = password_hash(
                            $password,
                            PASSWORD_DEFAULT
                        );

                        // Insert user

                        $sql = "
                            INSERT INTO users
                            (username, email, password)
                            VALUES
                            (:username, :email, :password)
                        ";

                        $stmt = $pdo->prepare($sql);

                        $stmt->execute([
                            ':username' => $username,
                            ':email' => $email,
                            ':password' => $hashedPassword
                        ]);

                        $success = 'Account created successfully.';

                        $username = '';
                        $email = '';
                    }
                }

            } catch (PDOException $e) {

                $errors[] = 'Unable to create account.';

            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Register - RootBit Auth</title>

    <link
        rel="stylesheet"
        href="assets/style.css"
    >

</head>

<body>

    <main class="auth-container">

        <section class="auth-card">

            <div class="logo">
                ROOTBIT
            </div>

            <h1 class="auth-title">
                Create Account
            </h1>

            <p class="auth-subtitle">
                Create your secure account
            </p>


            <?php if (!empty($errors)): ?>

                <div class="message message-error">

                    <?php foreach ($errors as $error): ?>

                        <div>
                            <?= htmlspecialchars($error) ?>
                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>


            <?php if ($success): ?>

                <div class="message message-success">
                    <?= htmlspecialchars($success) ?>
                </div>

            <?php endif; ?>


            <form method="POST">

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($csrfToken) ?>"
                >


                <div class="form-group">

                    <label for="username">
                        Username
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="<?= htmlspecialchars($username) ?>"
                        placeholder="Choose a username"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars($email) ?>"
                        placeholder="you@example.com"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="At least 8 characters"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="btn"
                >
                    Create Account
                </button>

            </form>


            <a
                href="login.php"
                class="auth-link"
            >
                Already have an account? Login
            </a>

        </section>

    </main>

</body>

</html>