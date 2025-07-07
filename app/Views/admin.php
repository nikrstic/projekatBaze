<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8" />
    <title>Admin Panel - Kafić Sistem</title>
    <link rel="stylesheet" href="admin.css" />
</head>
<body>
    <header>
        <h1>🧁 Admin Panel - Dobrodošao</h1>
    </header>
    <?= view('partials/header') ?>
    <nav>
        <ul>
            <li><a href="#dashboard">📊 Dashboard</a></li>
            <li><a href="#korisnici">👥 Korisnici</a></li>
            <li><a href="#kategorije">🍱 Kategorije</a></li>
            <li><a href="#proizvodi">🥤 Proizvodi</a></li>
            <li><a href="#stolovi">🪑 Stolovi</a></li>
            <li><a href="#narudzbine">🛒 Narudžbine</a></li>
            <li><a href="#poruke">📬 Poruke</a></li>
            <li><a href="#statistika">💸 Statistika</a></li>
            <li><a href="#logovi">🕵️ Logovi</a></li>
            <li><a href="/logout">🚪 Odjava</a></li>
        </ul>
    </nav>

    <main>
        <section id="dashboard">
            <h2>📊 Dashboard</h2>
            <p>Pregled sistema, aktivni korisnici, nove porudžbine, zauzeti stolovi itd.</p>
        </section>
        <form action="/admin/change-role" method="post" >
        <?= csrf_field() ?>
        <section id="korisnici">
            <h2>👥 Korisnici</h2>
            <?php foreach ($korisnici as $korisnik): ?>
    <form method="post" action="/admin/change-role">
        <?= csrf_field() ?>

        <p><?= esc($korisnik['ime']) ?> <?= esc($korisnik['prezime']) ?> – <?= esc($korisnik['email']) ?></p>

        <input type="hidden" name="username" value="<?= esc($korisnik['username']) ?>">
        <?php $trenutniRole = isset($korisnik['role_id']) ? $korisnik['role_id'] : $korisnik['role'];
                if($trenutniRole=='admin' || $trenutniRole==1)
                    $num=1;
                else if($trenutniRole=='konobar' || $trenutniRole==2)
                    $num=2;
                else
                    $num=3;
        ?>
        <select name="role_id" onchange="this.form.submit()">
            <option value="2" <?= $num == 2 ? 'selected' : '' ?>>Konobar</option>
            <option value="1" <?= $num == 1 ? 'selected' : '' ?>>Admin</option>
            <option value="3" <?= $num == 3 ? 'selected' : '' ?>>Gost</option>
        </select>
    </form>
<?php endforeach; ?>
        </form>
        <section id="kategorije">
            <h2>🍱 Kategorije</h2>
            <form action="/admin/add-category" method="post">
                <?= csrf_field() ?>
                <label>Naziv kategorije:</label>
                <input type="text" name="ime" required>

                <label>Opis:</label>
                <textarea name="opis"></textarea>

                <button type="submit">Dodaj kategoriju</button>
            </form>
        </section>

        <section id="proizvodi">
            <h2>🥤 Proizvodi</h2>
            <!--<form action="/admin/add-product" method="post">-->
                
            <?= form_open_multipart('/admin/add-product') ?>
                <?= csrf_field() ?>
                <label>Naziv proizvoda:</label>
                <input type="text" name="naziv" required>

                <label>Cena:</label>
                <input type="number" step="0.01" name="cena" required>

                <label>Opis:</label>
                <textarea name="opis"></textarea>

                <label>Kategorija:</label>
                <select name="kategorija_id">
                 <?php foreach ($kategorije as $kategorija): ?>
                  <?= 
                  
                   
                    $id = isset($kategorija['id']) ? $kategorija['id'] : (string)$kategorija['_id'];
                    
                   ?>
                   <option value="<?= esc($id) ?>"><?= esc($kategorija['naziv']) ?></option>
            <?php endforeach; ?>
                </select>

                <label>Dostupno:</label>
                <select name="dostupno">
                    <option value="1">Da</option>
                    <option value="0">Ne</option>
                </select>
                <label>Slika: </label>
                <input type="file" name="slika" size="20">
                <button type="submit">Dodaj proizvod</button>
            </form>
        </section>
        <section id="stolovi">
            <h2>🪑 Stolovi</h2>
            <form action="/admin/add-table" method="post">
                <?= csrf_field() ?>
                <label>Oznaka stola:</label>
                <input type="text" name="oznaka" required>

                <label>Aktivan:</label>
                <select name="aktivan">
                    <option value="1">Aktivan</option>
                    <option value="0">Neaktivan</option>
                </select>

                <button type="submit">Dodaj sto</button>
            </form>
        </section>

       <section id="narudzbine">
    <h2>🛒 Narudžbine</h2>
    <table>
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
</section>

<section id="poruke">
    <h2>📬 Poruke</h2>
    <table>
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
</section>

<section id="statistika">
    <h2>💸 Statistika — Top 5 Kupaca</h2>
    <table>
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
</section>

<section id="logovi">
    <h2>🕵️ Logovi</h2>
    <table>
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
</section>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> KaficSoft — Admin Sistem</p>
    </footer>
</body>
</html>