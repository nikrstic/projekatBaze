<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pocetna</title>
</head>
<body>
  <?= view('partials/header') ?>

     <div class="main">
          <h1>Dobrodosli u sistem kafica</h1>
        <h3>Unesite informacije za logovanje: </h3>

        <form action="/login" method="post">
            <?= csrf_field() ?>
            <label for="first">
                Username:
            </label>
            <input type="text" id="username" name="username" 
                placeholder="Enter your Username" required>
</br>
            <label for="password">
                Password:
            </label>

            <input type="password" id="password" name="password" 
                placeholder="Enter your Password" required>

            <div class="wrap">
                <button type="submit">
                    Submit
                </button>
            </div>
        </form>
        <?php if (isset($error)) : ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>
        <p>Nemate nalog?
            <a href="/registration" style="text-decoration: none;">
                Napravi nalog
            </a>
        </p>
    </div>
</body>
</html>