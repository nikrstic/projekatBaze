<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija</title>
</head>
<body>
    <div class="main">
        <h2>Registracija</h2>
        <form action="/registration" method="post">
            <?= csrf_field() ?>

            <label for="username">Korisničko ime:</label>
            <input type="text" id="username" name="username" required />

            <label for="ime">Ime:</label>
            <input type="text" id="ime" name="ime" required />

            <label for="prezime">Prezime:</label>
            <input type="text" id="prezime" name="prezime" required />

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required />

            <label for="password">Šifra:</label>
            <input type="password" id="password" name="password" required />

            <label for="repassword">Ponovi šifru:</label>
            <input type="password" id="repassword" name="repassword" required />

            <label for="contact">Kontakt telefon:</label>
            <input type="text" id="contact" name="contact" maxlength="20" required />

            <label for="pol">Pol:</label>
            <select id="pol" name="pol" required>
                <option value="1">Muško</option>
                <option value="0">Žensko</option>
            </select>
<!--
            <label for="role">Uloga:</label>
            <select id="role" name="role_id" required>
                <option value="1">Admin</option>
                <option value="2">Konobar</option>
                <option value="3" selected>Gost</option>
            </select>
-->
            <button type="submit">Pošalji</button>
        </form>
    </div>
</body>
</html>