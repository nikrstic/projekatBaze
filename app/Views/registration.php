<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Registracija</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="<?= base_url('js/Validator.js') ?>"></script>
    <style>
        .registration-card {
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border: none;
        }
        .form-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #0d6efd;
        }
        .gender-icon {
            font-size: 1.5rem;
            margin-right: 8px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="registration-card card p-4">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="form-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-person-plus">
                            <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                            <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                        </svg>
                    </div>
                    <h2>Registracija</h2>
                    <p class="text-muted">Popunite formu za kreiranje naloga</p>
                </div>

                <form id="registration" action="/registration" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Korisničko ime</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="ime" class="form-label">Ime</label>
                            <input type="text" class="form-control" id="ime" name="ime" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="prezime" class="form-label">Prezime</label>
                            <input type="text" class="form-control" id="prezime" name="prezime" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="password" class="form-label">Šifra</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="repassword" class="form-label">Ponovi šifru</label>
                            <input type="password" class="form-control" id="repassword" name="repassword" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="contact" class="form-label">Kontakt telefon</label>
                            <input type="text" class="form-control" id="contact" name="contact" maxlength="20" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Pol</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pol" id="musko" value="1" required checked>
                                    <label class="form-check-label" for="musko">
                                        <span class="gender-icon">♂</span> Muško
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pol" id="zensko" value="0">
                                    <label class="form-check-label" for="zensko">
                                        <span class="gender-icon">♀</span> Žensko
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Kreiraj nalog</button>
                    </div>
                    
                    <div class="text-center mt-3">
                        <p class="mb-0">Već imate nalog? <a href="/login-form">Prijavite se</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    const config = {
        username: { required: true, minlength: 3, maxlength: 50 },
        email: { required: true, email: true },
        password: { required: true, minlength: 3, maxlength: 20, matching: 'repassword' },
        repassword: { required: true, minlength: 3, maxlength: 20 }
    };

    const formValidator = new Validator(config, '#registration');

    document.querySelector('#registration').addEventListener('submit', function (e) {
        if (!formValidator.validationPassed()) {
            e.preventDefault();
        }
    });

</script>

</body>
</html>