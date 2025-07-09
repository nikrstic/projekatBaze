<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Izbor baze podataka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .database-card {
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
        }
        .database-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .database-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center mb-5">
                <h1 class="display-4 mb-4">Izaberite bazu podataka</h1>
                <p class="lead">Molimo vas izaberite koju bazu podataka želite da koristite</p>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-md-5 mb-4">
                <a href="<?= site_url('/load/mysql') ?>" class="text-decoration-none">
                    <div class="card database-card h-100">
                        <div class="card-body text-center p-5">
                            <div class="database-icon text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-database">
                                    <path d="M6 12.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5M3 8a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z"/>
                                    <path d="M0 6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1z"/>
                                </svg>
                            </div>
                            <h3 class="card-title">MySQL</h3>
                            <p class="card-text">Relaciona baza podataka sa SQL upitima</p>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-5 mb-4">
                <a href="<?= site_url('/load/mongodb') ?>" class="text-decoration-none">
                    <div class="card database-card h-100">
                        <div class="card-body text-center p-5">
                            <div class="database-icon text-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-file-earmark-binary">
                                    <path d="M7.05 11.885c0 1.415-.548 2.206-1.524 2.206C4.548 14.09 4 13.3 4 11.885c0-1.412.548-2.203 1.526-2.203.976 0 1.524.79 1.524 2.203m-1.524-1.612c-.542 0-.832.563-.832 1.612 0 .088.003.173.006.252l1.559-1.143c-.126-.474-.375-.72-.733-.72zm-.732 2.508c.126.472.372.718.732.718.54 0 .83-.563.83-1.614 0-.085-.003-.17-.006-.25z"/>
                                    <path d="M14 14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-9-1a1 1 0 1 0 0-2 1 1 0 0 0 0 2m9-1a1 1 0 1 0 0-2 1 1 0 0 0 0 2M9.5 1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5z"/>
                                </svg>
                            </div>
                            <h3 class="card-title">MongoDB</h3>
                            <p class="card-text">NoSQL baza podataka sa dokumentima</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="row justify-content-center mt-4">
            <div class="col-lg-8 text-center">
                <p class="text-muted">Sistem će automatski preći na izabranu bazu podataka</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>