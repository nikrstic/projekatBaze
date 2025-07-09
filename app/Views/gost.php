<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Kafić Sistem - Početna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .category-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .cart-preview {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <?= view('partials/header') ?>
    
    <div class="container py-4">
        <h1 class="mb-4">Dobrodošli u naš kafić</h1>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= esc(session()->getFlashdata('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <h2 class="mb-3">Izaberite kategoriju:</h2>
        <div class="row">
            <?php foreach($kategorije as $kategorija): ?>
                <?php $id = isset($kategorija['id']) ? $kategorija['id'] : (string)$kategorija['_id']; ?>
                <div class="col-md-4">
                    <div class="card category-card">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= esc($kategorija['naziv']) ?></h5>
                            <a href="/meni/kategorija/<?= esc($id) ?>" class="btn btn-primary">Pogledaj ponudu</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>