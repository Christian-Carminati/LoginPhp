<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <script src="js/request.js"></script>
    <title>Change password</title>
</head>
<body>
    <div class="container" style="width: 360px;">
        <form onsubmit="changePassword();return false;">
            <h2>Cambia password</h2>
            <br>
            Inserisci l'email per recuperare la password 
            <br><br>
            <input type="email" placeholder="email" name="email" id="email" required>
            <input class="btn" type="submit" value="Invia nuova password">
            <br>
            <a href="index.php" style="color: blue;">Torna alla pagina di login</a>
        </form>
    </div>
</body>
</html>