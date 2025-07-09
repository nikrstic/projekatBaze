<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Aktuelne narudžbine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .status-new {
            background-color: #fff3cd;
        }
        .status-preparing {
            background-color: #cce5ff;
        }
        .status-delivered {
            background-color: #d4edda;
        }
        .product-list {
            max-height: 150px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <?= view('partials/header') ?>
    
    <div class="container py-4">
        <h1 class="mb-4">🍽️ Aktuelne narudžbine</h1>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Sto</th>
                        <th>Proizvodi</th>
                        <th>Status</th>
                        <th>Promeni status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($narudzbine as $n): ?>
                        <tr class="<?= 
                            $n['status'] === 'Novo' ? 'status-new' : 
                            ($n['status'] === 'U pripremi' ? 'status-preparing' : 'status-delivered') 
                        ?>">
                            <td class="fw-bold"><?= esc($n['sto']) ?></td>
                            <td>
                                <div class="product-list">
                                    <?php
                                        $proizvodi = [];
                                        // MySQL
                                        if (isset($n['proizvodi']) && is_string($n['proizvodi'])) {
                                            $proizvodi = explode(',', $n['proizvodi']);
                                            foreach ($proizvodi as $naziv) {
                                                echo '<div>'.esc(trim($naziv)).'</div>';
                                            }
                                        }
                                        // MongoDB
                                        elseif (isset($n['artikli'])) {
                                            $artikli = json_decode(json_encode($n['artikli']), true);
                                            foreach ($artikli as $artikal) {
                                                echo '<div>'.esc($artikal['naziv']).' — '.esc($artikal['kolicina']).' kom</div>';
                                            }
                                        }
                                    ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge <?= 
                                    $n['status'] === 'Novo' ? 'bg-warning text-dark' : 
                                    ($n['status'] === 'U pripremi' ? 'bg-primary' : 'bg-success')
                                ?>">
                                    <?= esc($n['status']) ?>
                                </span>
                            </td>
                            <td>
                                <form method="post" action="/konobar/promeni-status" class="d-flex">
                                    <?= csrf_field() ?>
                                    <?php $id = isset($n['narudzbina_id']) ? $n['narudzbina_id'] : (string)($n['_id'] ?? ''); ?>
                                    <input type="hidden" name="narudzbina_id" value="<?= esc($id) ?>">
                                    <select name="novi_status" class="form-select form-select-sm me-2">
                                        <option value="Novo" <?= $n['status'] === 'Novo' ? 'selected' : '' ?>>Novo</option>
                                        <option value="U pripremi" <?= $n['status'] === 'U pripremi' ? 'selected' : '' ?>>U pripremi</option>
                                        <option value="Dostavljeno" <?= $n['status'] === 'Dostavljeno' ? 'selected' : '' ?>>Dostavljeno</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-success">✔</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>