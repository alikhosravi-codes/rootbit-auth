<?php

session_set_cookie_params([
    'httponly' => true,
    'secure' => false,
    'samesite' => 'Lax'
]);

session_start();

require_once '../includes/csrf.php';
require_once '../config/database.php';

$email = '';
$error = '';

$csrfToken = generateCsrfToken();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $submittedToken = $_POST['csrf_token'] ?? '';

    // CSRF validation
    if (!verifyCsrfToken($submittedToken)) {

        $error = 'Invalid security token.';

    } else {

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validation
        if ($email === '' || $password === '') {

            $error = 'Email and password are required.';

        } else {

            // Find user by email
            $sql = "
                SELECT id, username, email, password
                FROM users
                WHERE email = :email
                LIMIT 1
            ";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':email' => $email
            ]);

            $user = $stmt->fetch();

            if (
                $user === false ||
                !password_verify($password, $user['password'])
            ) {

                $error = 'Invalid email or password.';

            } else {

                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];

                header('Location: dashboard.php');
                exit;
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

    <title>Login - RootBit Auth</title>

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
                Welcome Back
            </h1>

            <p class="auth-subtitle">
                Sign in to your account
            </p>


            <?php if ($error): ?>

                <div class="message message-error">
                    <?= htmlspecialchars($error) ?>
                </div>

            <?php endif; ?>


            <form method="POST">

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($csrfToken) ?>"
                >


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
                        placeholder="Enter your password"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="btn"
                >
                    Login
                </button>

            </form>


            <a
                href="register.php"
                class="auth-link"
            >
                Don't have an account? Create one
            </a>

        </section>

    </main>

</body>

</html>