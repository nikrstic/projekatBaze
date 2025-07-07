<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?= view('partials/header') ?>
    <h2>🍽️ Aktuelne narudžbine</h2>

<?php if (session()->getFlashdata('success')): ?>
    <div style="background: #d4edda; padding: 10px; border: 1px solid #c3e6cb;">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Sto</th>
            <th>Proizvodi</th>
            <th>Status</th>
            <th>Promeni status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($narudzbine as $n): ?>
            <tr>
                <td><?= esc($n['sto']) ?></td>
                <td><?= esc($n['proizvodi']) ?></td>
                <td><?= esc($n['status']) ?></td>
                <td>
                    <form method="post" action="/konobar/promeni-status">
                        <?= csrf_field() ?>
                        <input type="hidden" name="narudzbina_id" value="<?= esc($n['narudzbina_id']) ?>">
                        <select name="novi_status">
                            <option value="Novo" <?= $n['status'] === 'Novo' ? 'selected' : '' ?>>Novo</option>
                            <option value="U pripremi" <?= $n['status'] === 'U pripremi' ? 'selected' : '' ?>>U pripremi</option>
                            <option value="Dostavljeno" <?= $n['status'] === 'Dostavljeno' ? 'selected' : '' ?>>Dostavljeno</option>
                        </select>
                        <button type="submit">✔</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>