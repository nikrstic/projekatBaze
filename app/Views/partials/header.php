<!-- app/Views/partials/header.php -->
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Kafić Sistem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="/">☕ Kafić Sistem</a>

    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
            <?php $session = session(); ?>

            <?php if ($session->has('isLoggedIn')): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/logout-user">Logout (Korisnik)</a>
                </li>
            <?php endif; ?>

            <?php if ($session->has('database')): ?>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="/logout-all">Logout (Baza)</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="container mt-4">
