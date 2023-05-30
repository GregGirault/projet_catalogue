<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Connexion</h2>
    <form action=""  method="POST">
    <div>
    <label for="username">Nom utilisateur</label>
    <input type="text" name="username" id="username" placeholder="Entrez le nom d'utilisateur">
    </div>
    <div>
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" placeholder="Entrez le mot de passe">
    </div>
    <input type="submit" value="Connexion">
    <?php
        if (isset($_GET['erreur'])) {
            $err = $_GET['erreur'];
            if ($err == 1 || $err == 2) {
                echo "<p style='color:red'>Utilisateur ou mot de passe incorrect</p>";
            }
        }
    ?>
    </form>
</body>
</html>