<?php
session_start();

if (isset($_POST['username']) && isset($_POST['password'])) {
    $db_host = 'localhost';
    $db_name = 'catalogue';
    $db_username = 'root';
    $db_password = '';

    try {
        $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_username, $db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Erreur de connexion à la base de données : ' . $e->getMessage());
    }

    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    if ($username !== "" && $password !== "") {
        $sql = "SELECT COUNT(*) FROM utilisateur WHERE username = :username AND password = :password";
        $query = $db->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();
        $count = $query->fetchColumn();

        if ($count != 0) {
            $_SESSION['authenticated'] = true;
            $_SESSION['username'] = $username;
            header('Location: historique.php'); // Page de redirection vers le tableau de bord
            exit();
        } else {
            header('Location: login.php?erreur=1');
            exit();
        }
    } else {
        header('Location: login.php?erreur=2');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
