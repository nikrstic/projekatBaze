<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8" />
    <title>Admin Panel - Kafić Sistem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   
</head>
<body>
    <?= view('partials/header') ?>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 sidebar">
                <nav class="nav flex-column">
                    <h4 class="px-3 mb-3">Admin Panel</h4>
                    <a class="nav-link" href="#dashboard">📊 Dashboard</a>
                    <a class="nav-link" href="#korisnici">👥 Korisnici</a>
                    <a class="nav-link" href="#kategorije">🍱 Kategorije</a>
                    <a class="nav-link" href="#proizvodi">🥤 Proizvodi</a>
                    <a class="nav-link" href="#stolovi">🪑 Stolovi</a>
                    <a class="nav-link" href="#narudzbine">🛒 Narudžbine</a>
                    <a class="nav-link" href="#poruke">📬 Poruke</a>
                    <a class="nav-link" href="#statistika">💸 Statistika</a>
                    <a class="nav-link" href="#logovi">🕵️ Logovi</a>
                </nav>
            </div>
            
            <div class="col-md-9 col-lg-10 py-4">
                <section id="dashboard" class="section">
                    <h2>📊 Dashboard</h2>
                    <p class="lead">Pregled sistema, aktivni korisnici, nove porudžbine, zauzeti stolovi itd.</p>
                </section>
                
                <section id="korisnici" class="section">
                    <h2>👥 Korisnici</h2>
                    <?php foreach ($korisnici as $korisnik): ?>
                        <form method="post" action="/admin/change-role" class="mb-3 p-3 border rounded">
                            <?= csrf_field() ?>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h5 class="mb-1"><?= esc($korisnik['ime']) ?> <?= esc($korisnik['prezime']) ?></h5>
                                    <small class="text-muted"><?= esc($korisnik['email']) ?></small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <input type="hidden" name="username" value="<?= esc($korisnik['username']) ?>">
                                    <?php 
                                        $trenutniRole = isset($korisnik['role_id']) ? $korisnik['role_id'] : $korisnik['role'];
                                        if($trenutniRole=='admin' || $trenutniRole==1) $num=1;
                                        else if($trenutniRole=='konobar' || $trenutniRole==2) $num=2;
                                        else $num=3;
                                    ?>
                                    <select name="role_id" onchange="this.form.submit()" class="form-select form-select-sm ms-2" style="width: auto;">
                                        <option value="2" <?= $num == 2 ? 'selected' : '' ?>>Konobar</option>
                                        <option value="1" <?= $num == 1 ? 'selected' : '' ?>>Admin</option>
                                        <option value="3" <?= $num == 3 ? 'selected' : '' ?>>Gost</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    <?php endforeach; ?>
                </section>
                
                <section id="kategorije" class="section">
                    <h2>🍱 Kategorije</h2>
                    <form action="/admin/add-category" method="post" class="mb-4">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">Naziv kategorije:</label>
                            <input type="text" name="ime" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Opis:</label>
                            <textarea name="opis" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Dodaj kategoriju</button>
                    </form>
                </section>
                
                <section id="proizvodi" class="section">
                    <h2>🥤 Proizvodi</h2>
                    <?= form_open_multipart('/admin/add-product') ?>
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">Naziv proizvoda:</label>
                            <input type="text" name="naziv" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cena:</label>
                            <input type="number" step="0.01" name="cena" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Opis:</label>
                            <textarea name="opis" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategorija:</label>
                            <select name="kategorija_id" class="form-select">
                                <?php foreach ($kategorije as $kategorija): ?>
                                    <?php $id = isset($kategorija['id']) ? $kategorija['id'] : (string)$kategorija['_id']; ?>
                                    <option value="<?= esc($id) ?>"><?= esc($kategorija['naziv']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dostupno:</label>
                            <select name="dostupno" class="form-select">
                                <option value="1">Da</option>
                                <option value="0">Ne</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slika:</label>
                            <input type="file" name="slika" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Dodaj proizvod</button>
                    </form>
                </section>
                
                <section id="stolovi" class="section">
                    <h2>🪑 Stolovi</h2>
                    <form action="/admin/add-table" method="post" class="mb-4">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">Oznaka stola:</label>
                            <input type="text" name="oznaka" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Aktivan:</label>
                            <select name="aktivan" class="form-select">
                                <option value="1">Aktivan</option>
                                <option value="0">Neaktivan</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Dodaj sto</button>
                    </form>
                </section>
                
                <section id="narudzbine" class="section">
                    <h2>🛒 Narudžbine</h2>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Korisnik</th>
                                    <th>Sto</th>
                                    <th>Status</th>
                                    <th>Vreme</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($narudzbine as $n): ?>
                                    <tr>
                                        <td><?= esc($n['id']) ?></td>
                                        <td><?= esc($n['username']) ?></td>
                                        <td><?= esc($n['sto']) ?></td>
                                        <td><?= esc($n['status']) ?></td>
                                        <td><?= esc($n['vreme']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
                
                <section id="poruke" class="section">
                    <h2>📬 Poruke</h2>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Korisnik</th>
                                    <th>Narudžbina</th>
                                    <th>Tekst</th>
                                    <th>Vreme</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($poruke as $p): ?>
                                    <tr>
                                        <td><?= esc($p['id']) ?></td>
                                        <td><?= esc($p['username']) ?></td>
                                        <td>#<?= esc($p['narudzbina_id']) ?></td>
                                        <td><?= esc($p['tekst']) ?></td>
                                        <td><?= esc($p['vreme']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
                
                <section id="statistika" class="section">
                    <h2>💸 Statistika — Top 5 Kupaca</h2>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Korisnik</th>
                                    <th>Ukupan iznos (€)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($statistika as $s): ?>
                                    <tr>
                                        <td><?= esc($s['username']) ?></td>
                                        <td><?= number_format($s['iznos'], 2, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
                
                <section id="logovi" class="section">
                    <h2>🕵️ Logovi</h2>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Korisnik</th>
                                    <th>Radnja</th>
                                    <th>Vreme</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($logovi as $log): ?>
                                    <tr>
                                        <td><?= esc($log['id']) ?></td>
                                        <td><?= esc($log['username']) ?></td>
                                        <td><?= esc($log['radnja']) ?></td>
                                        <td><?= esc($log['vreme']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white py-3 mt-4">
        <div class="container">
            <p class="mb-0 text-center">&copy; <?= date('Y') ?> KaficSoft — Admin Sistem</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>