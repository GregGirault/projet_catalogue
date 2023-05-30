<?php
try {
    $server_name = "localhost";
    $db_name = "catalogue";
    $user_name = "root";
    $password = "";

    $db = new PDO("mysql:host=$server_name; dbname=$dbname;charset=utf8mb4", 
    $user_name, $password);
} catch (PDOException $e) {
    echo "echec de connexion" . 
    $e->getMessage();
}