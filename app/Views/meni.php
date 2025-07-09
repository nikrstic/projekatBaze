<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Meni - <?= esc($kategorija['naziv']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-card {
            transition: all 0.3s ease;
            margin-bottom: 20px;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .product-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        .category-nav {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 30px;
        }
        .cart-preview {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 350px;
            z-index: 1000;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            display: none;
        }
    </style>
</head>
<body>
    <?= view('partials/header') ?>
    
    <div class="container py-4">
        <h1 class="mb-4">Meni — Kategorija: <?= esc($kategorija['naziv']) ?></h1>
        
        <div class="category-nav mb-4">
            <h4>Kategorije:</h4>
            <div class="d-flex flex-wrap gap-2">
                <?php foreach ($kategorije as $kat): ?>
                    <?php $id = isset($kat['id']) ? $kat['id'] : (string)$kat['_id']; ?>
                    <a href="/meni/kategorija/<?= esc($id) ?>" 
                       class="btn btn-outline-primary btn-sm <?= $kat['naziv'] === $kategorija['naziv'] ? 'active' : '' ?>">
                        <?= esc($kat['naziv']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <?php if (!empty($proizvodi)): ?>
            <div class="row">
                <?php foreach ($proizvodi as $p): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card product-card h-100">
                            <?php if (!empty($p['slika'])): ?>
                                <img src="/uploads/<?= esc($p['slika']) ?>" class="card-img-top product-img" alt="<?= esc($p['naziv']) ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= esc($p['naziv']) ?></h5>
                                <p class="card-text"><?= esc($p['opis']) ?></p>
                                <p class="h5 text-primary"><?= number_format($p['cena'], 2, ',', '.') ?> €</p>
                                <form method="post" action="/korpa/dodaj" class="mt-3">
                                    <?= csrf_field() ?>
                                    <?php $id = isset($p['id']) ? $p['id'] : (string)$p['_id']; ?>
                                    <input type="hidden" name="proizvod_id" value="<?= esc($id) ?>">
                                    <button type="submit" class="btn btn-primary w-100">Dodaj u korpu</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Nema dostupnih proizvoda u ovoj kategoriji.
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Cart Preview -->
    <div class="cart-preview bg-white p-3 rounded shadow" id="korpa">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">🛒 Tvoja korpa</h5>
            <button class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('korpa').style.display='none'">
                ×
            </button>
        </div>
        
        <?php if (!empty($korpa)): ?>
            <div class="table-responsive mb-3">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Proizvod</th>
                            <th>Cena</th>
                            <th>Količina</th>
                            <th>Ukupno</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $ukupna_cena = 0; ?>
                        <?php foreach ($korpa as $k): ?>
                            <?php $ukupna_cena += $k['cena'] * $k['kolicina']; ?>
                            <tr>
                                <td><?= esc($k['naziv']) ?></td>
                                <td><?= number_format($k['cena'], 2, ',', '.') ?> €</td>
                                <td><?= esc($k['kolicina']) ?></td>
                                <td><?= number_format($k['cena'] * $k['kolicina'], 2, ',', '.') ?> €</td>
                                <td class="text-end">
                                    <form method="post" action="/korpa/obrisi" class="d-inline">
                                        <?= csrf_field() ?>
                                        <?php $id = isset($k['stavka_id']) ? $k['stavka_id'] : (string)$k['_id']; ?>
                                        <input type="hidden" name="stavka_id" value="<?= esc($id) ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr>
                            <th colspan="3">Ukupno:</th>
                            <th colspan="2"><?= number_format($ukupna_cena, 2, ',', '.') ?> €</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <form action="/naruci" method="post" class="d-grid">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-success">Naruči</button>
            </form>
        <?php else: ?>
            <div class="alert alert-info mb-0">
                Korpa je prazna.
            </div>
        <?php endif; ?>
    </div>
    
    <div class="position-fixed bottom-0 end-0 p-3">
        <button class="btn btn-primary rounded-pill shadow-lg" 
                onclick="document.getElementById('korpa').style.display = 
                    document.getElementById('korpa').style.display === 'none' ? 'block' : 'none'">
            🛍️ Korpa
            <?php if (!empty($korpa)): ?>
                <span class="badge bg-danger ms-1"><?= count($korpa) ?></span>
            <?php endif; ?>
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>