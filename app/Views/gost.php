<!DOCTYPE html>
<html>
<head>
    <title>Prikaz iz baze</title>
</head>
<body>
    <?= view('partials/header') ?>
    <h1>Gost</h1>
     <?php if (session()->getFlashdata('success')): ?>
    <div style="background-color: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb;">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>
    <?php foreach($kategorije as $kategorija): ?>
       <a href="/meni/kategorija/<?= esc($kategorija['id']) ?>">
        <?= esc($kategorija['naziv']) ?>
    </a><br>

    <?php endforeach; ?>


</body>

</html>