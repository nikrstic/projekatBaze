<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Izbor stola</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-card {
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
        }
        .table-card:hover {
            transform: scale(1.05);
        }
        .table-card.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .table-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .available {
            color: #198754;
        }
        .unavailable {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <?= view('partials/header') ?>
    
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-5 mb-3">Izaberite svoj sto</h1>
            <p class="lead">Molimo vas izaberite sto za koji želite da naručite</p>
        </div>
        
        <form method="post" action="/izabranSto">
            <?= csrf_field() ?>
            
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                <?php foreach ($stolovi as $sto): ?>
                    <?php $id = isset($sto['id']) ? $sto['id'] : (string)$sto['_id']; ?>
                    <div class="col">
                        <input type="radio" class="btn-check" name="sto_id" 
                               id="sto<?= esc($id) ?>" value="<?= esc($id) ?>" 
                               autocomplete="off" <?= $sto['aktivan'] ? '' : 'disabled' ?>>
                        <label class="card table-card text-center p-4 <?= $sto['aktivan'] ? '' : 'disabled' ?>" 
                               for="sto<?= esc($id) ?>">
                            <div class="table-icon <?= $sto['aktivan'] ? 'available' : 'unavailable' ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-cup">
                                    <path d="M1 2a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v1h.5A1.5 1.5 0 0 1 16 4.5v7a1.5 1.5 0 0 1-1.5 1.5h-.55a2.5 2.5 0 0 1-2.45 2h-8A2.5 2.5 0 0 1 1 12.5zm2 0v1h12V2a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0-.5.5m1.5 3a.5.5 0 0 0-.5.5v7a1.5 1.5 0 0 0 1.5 1.5h8a1.5 1.5 0 0 0 1.5-1.5v-7a.5.5 0 0 0-.5-.5z"/>
                                </svg>
                            </div>
                            <h3 class="card-title"><?= esc($sto['oznaka']) ?></h3>
                            <div class="badge bg-<?= $sto['aktivan'] ? 'success' : 'danger' ?>">
                                <?= $sto['aktivan'] ? 'Slobodan' : 'Zauzet' ?>
                            </div>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="d-grid gap-2 col-md-4 mx-auto mt-5">
                <button type="submit" class="btn btn-primary btn-lg">Potvrdi izbor</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>