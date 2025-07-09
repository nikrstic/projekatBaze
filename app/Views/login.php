<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-card {
            max-width: 500px;
            margin: 0 auto;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border: none;
        }
        .login-icon {
            font-size: 3rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }
        .welcome-text {
            font-size: 1.2rem;
            color: #6c757d;
        }
    </style>
</head>
<body class="bg-light">
    <?= view('partials/header') ?>
    
    <div class="container py-5">
        <div class="login-card card p-4">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="login-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-door-open">
                            <path d="M8.5 10c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1"/>
                            <path d="M10.828.122A.5.5 0 0 1 11 .5V1h.5A1.5 1.5 0 0 1 13 2.5V15h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V1.5a.5.5 0 0 1 .43-.495l7-1a.5.5 0 0 1 .398.117M9.5 2H4v13h5.5zM9 15h4v-1H9zm0-7v1h4V8z"/>
                        </svg>
                    </div>
                    <h1 class="h3 mb-2">Dobrodošli u sistem kafića</h1>
                    <p class="welcome-text">Unesite informacije za prijavu</p>
                </div>

                <form action="/login" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Korisničko ime</label>
                        <input type="text" class="form-control" id="username" name="username" 
                               placeholder="Unesite korisničko ime" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">Šifra</label>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Unesite šifru" required>
                    </div>
                    
                    <?php if (isset($error)) : ?>
                        <div class="alert alert-danger mb-4">
                            <?= esc($error) ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            Prijavi se
                        </button>
                    </div>
                    
                    <div class="text-center">
                        <p class="mb-0">Nemate nalog?
                            <a href="/registration" class="text-decoration-none">
                                Napravite nalog
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>