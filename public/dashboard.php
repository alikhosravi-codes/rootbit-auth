<?php

require_once '../includes/auth.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard - RootBit Auth</title>

    <link
        rel="stylesheet"
        href="assets/style.css"
    >

</head>

<body>

    <div class="dashboard">

        <header class="dashboard-header">

            <div class="dashboard-logo">
                ROOTBIT
            </div>

            <a
                href="logout.php"
                class="logout-btn"
            >
                Logout
            </a>

        </header>


        <main class="dashboard-content">

            <h1 class="dashboard-title">
                Welcome, <?= htmlspecialchars($_SESSION['username']) ?>
            </h1>

            <p class="dashboard-subtitle">
                Welcome to your secure dashboard.
            </p>


            <section class="dashboard-grid">

                <div class="dashboard-card">

                    <h2>
                        Username
                    </h2>

                    <p>
                        <?= htmlspecialchars($_SESSION['username']) ?>
                    </p>

                </div>


                <div class="dashboard-card">

                    <h2>
                        Email
                    </h2>

                    <p>
                        <?= htmlspecialchars($_SESSION['email']) ?>
                    </p>

                </div>


                <div class="dashboard-card">

                    <h2>
                        Account Status
                    </h2>

                    <p class="status">
                        ● Active
                    </p>

                </div>


                <div class="dashboard-card">

                    <h2>
                        Authentication
                    </h2>

                    <p class="status">
                        ● Secured
                    </p>

                </div>

            </section>

        </main>

    </div>

</body>

</html>