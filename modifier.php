<?php
session_start();
require_once("connect.php");

if ($_POST) {
    if (isset($_POST["id"]) && isset($_POST["objet"]) && isset($_POST["titre"]) && isset($_POST["description"]) && isset($_POST["ingredients"]) && isset($_POST["image"]) && isset($_POST["categorie_id"])) {
        $id = strip_tags($_POST["id"]);
        $objet = strip_tags($_POST["objet"]);
        $titre = strip_tags($_POST["titre"]);
        $description = strip_tags($_POST["description"]);
        $ingredients = strip_tags($_POST["ingredients"]);
        $image = strip_tags($_POST["image"]);
        $categorie_id = strip_tags($_POST["categorie_id"]);

        // Modifier le produit dans la table des produits
        $sql = "UPDATE produits SET objet=:objet, titre=:titre, description=:description, ingredients=:ingredients, image=:image, categorie_id=:categorie_id WHERE id = :id";
        $query = $db->prepare($sql);
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->bindValue(":objet", $objet, PDO::PARAM_STR);
        $query->bindValue(":titre", $titre, PDO::PARAM_STR);
        $query->bindValue(":description", $description, PDO::PARAM_STR);
        $query->bindValue(":ingredients", $ingredients, PDO::PARAM_STR);
        $query->bindValue(":image", $image, PDO::PARAM_STR);
        $query->bindValue(":categorie_id", $categorie_id, PDO::PARAM_INT);
        $query->execute();

        $_SESSION["toast_message"] = "Produit $id modifié avec succès";
        $_SESSION["toast_type"] = "success";

        header("Location: historique.php");
        exit();
    }
}

// Sert à récupérer les informations du produit
$id = "";
if (isset($_GET["id"]) && !empty($_GET['id'])) {
    $id = strip_tags($_GET['id']);

// Récupérer les informations du produit et de sa catégorie depuis les tables produits et categorie
$sql = "SELECT p.*, c.objet AS categorie_objet 
        FROM produits p
        JOIN categorie c ON p.categorie_id = c.id
        WHERE p.id = :id";
$query = $db->prepare($sql);
$query->bindValue(":id", $id, PDO::PARAM_INT);
$query->execute();
$produit = $query->fetch();

} else {
    header("Location: login.php");
    exit();
}

require_once("close.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <title>Modification du produit</title>
</head>

<body class="bg-gray-100">
    <h1 class="text-3xl font-bold text-center mt-8">Modification du produit n° <?= $id ?></h1>
    <div class="max-w-md mx-auto mt-8 bg-white p-6 rounded-lg shadow-md">
        <form method="post">
            <div class="mb-4">
                <label for="objet" class="block font-bold text-gray-700">Nom</label>
                <input type="text" name="objet" required value="<?= $produit['objet'] ?>" class="form-input mt-1">
            </div>
            <div class="mb-4">
                <label for="titre" class="block font-bold text-gray-700">Titre</label>
                <input type="text" name="titre" id="titre" required value="<?= $produit['titre'] ?>" class="form-input mt-1">
            </div>
            <div class="mb-4">
                <label for="description" class="block font-bold text-gray-700">Description</label>
                <textarea name="description" required class="form-textarea mt-1"><?= $produit['description'] ?></textarea>
            </div>
            <div class="mb-4">
                <label for="ingredients" class="block font-bold text-gray-700">ingredients</label>
                <textarea name="ingredients" required class="form-textarea mt-1"><?= $produit['ingredients'] ?></textarea>
            </div>
            <div class="mb-4">
                <label for="image" class="block font-bold text-gray-700">Image</label>
                <input type="text" name="image" required value="<?= $produit['image'] ?>" class="form-input mt-1">
            </div>
            <div class="mb-4">
                <label for="image" class="block font-bold text-gray-700">Image</label>
                <input type="file" name="image" required value="<?= $produit['image'] ?>" class="form-input mt-1">
            </div>

            <div class="mb-4">
    <label for="categorie_id" class="block font-bold text-gray-700">Catégorie</label>
    <select name="categorie_id" class="form-select mt-1">
        <option value="<?= $produit['categorie_id'] ?>" selected><?= $produit['categorie_objet'] ?></option>
    </select>
</div>


            <input type="hidden" value="<?= $produit["id"] ?>" name="id">
            <div class="flex justify-center">
                <input type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700 cursor-pointer" value="Enregistrer">

               