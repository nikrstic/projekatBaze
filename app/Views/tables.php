<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h3>Koji sto zelite?</h3>
    
    <form method="post" action="/izabranSto" >
    <?= csrf_field() ?>
    <select name="sto_id" required>
    <?php foreach ($stolovi as $sto): ?>
        <option value="<?= esc($sto['id']) ?>" <?= $sto['aktivan'] ? '' : 'disabled' ?>>
            <?= esc($sto['oznaka']) ?> <?= $sto['aktivan'] ? '' : '(nedostupan)' ?>
        </option>
        
    <?php endforeach; ?>
    </select>
    <button type="submit">Izaberi</button>
    </form>

</body>
</html>