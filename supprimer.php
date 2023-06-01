<?php
session_start();
require_once('connect.php');

if ($_GET && isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM `produits` WHERE `id` = :id";
    $query = $db->prepare($sql);
    $query->bindValue(":id", $id, PDO::PARAM_INT);
    $query->execute();
    $produits = $query->fetch(PDO::FETCH_ASSOC);

    if ($produit) {
        $sql = "DELETE FROM `produits` WHERE `id` = :id";
        $query = $db->prepare($sql);
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();


    }
}

header("Location:    .php");
exit();
?>