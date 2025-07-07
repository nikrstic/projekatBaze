<!DOCTYPE html>
<html>
<head>
    <title>Meni - <?= esc($kategorija['naziv']) ?></title>
</head>
<body>
    <?= view('partials/header') ?>
    <h1>Meni — Kategorija: <?= esc($kategorija['naziv']) ?></h1>

    <nav>
        <ul>
            <?php foreach ($kategorije as $kat): ?>
                <li>
                    <a href="/meni/kategorija/<?= esc($kat['id']) ?>">
                        <?= esc($kat['naziv']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <section>
        <?php if (!empty($proizvodi)): ?>
            <ul>
                <?php foreach ($proizvodi as $p): ?>
                    <li>
                        <strong><?= esc($p['naziv']) ?></strong> —
                        <?= esc($p['opis']) ?> —
                        <?= number_format($p['cena'], 2, ',', '.') ?> €
                       <img src="/uploads/<?= esc($p['slika']) ?>" style="max-width: 200px; height: auto;">
                        <form method="post" action="/korpa/dodaj">
                            <?= csrf_field() ?>
                            <input type="hidden" name="proizvod_id" value="<?= esc($p['id']) ?>">
                            <button type="submit">Dodaj u korpu</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nema dostupnih proizvoda u ovoj kategoriji.</p>
        <?php endif; ?>
    </section>
    <div>
        <button onclick="document.getElementById('korpa').style.display = 
    document.getElementById('korpa').style.display === 'none' ? 'block' : 'none'">
    🛍️ Prikaži / sakrij korpu
</button>
    </div>
    <div id="korpa" style="display: none;">
    <h2>🛒 Tvoja korpa</h2>

    <?php if (!empty($korpa)): ?>
        <table>
            <tr><th>Proizvod</th><th>Cena</th><th>Količina</th><th>Ukupno</th><th>Akcija</th></tr>
            <?php foreach ($korpa as $k): ?>
                <tr>
                    <td><?= esc($k['naziv']) ?></td>
                    <td><?= number_format($k['cena'], 2, ',', '.') ?> €</td>
                    <td><?= esc($k['kolicina']) ?></td>
                    <td><?= number_format($k['cena'] * $k['kolicina'], 2, ',', '.') ?> €</td>
                    <td>
                        <form method="post" action="/korpa/obrisi">
                            <?= csrf_field() ?>
                            <input type="hidden" name="stavka_id" value="<?= esc($k['stavka_id']) ?>">
                            <button type="submit">🗑️</button>
                        </form>
                        
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <form action="/naruci" method="post">
                            <?= csrf_field() ?>
                            <button type="submit">Naruci</button>
                        </form>
    <?php else: ?>
        <p>Korpa je prazna.</p>
    <?php endif; ?>
</div>
</body>
</html>