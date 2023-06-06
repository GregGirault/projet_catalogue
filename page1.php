<?php include 'header.php'; ?>
<?php require_once 'connect.php'; ?>

<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql_produits = "SELECT * FROM produits WHERE id = :id";
    $query_produits = $db->prepare($sql_produits);
    $query_produits->bindParam(':id', $id);
    $query_produits->execute();
    $produits = $query_produits->fetch();

    if (isset($produits['description']) && isset($produits['ingredients'])) {
        $description = $produits['description'];
        $truncatedDescription = substr($description, 0, 400); // Tronque la description à 400 caractères
        $ingredients = $produits['ingredients'];
    } else {
        // Gérer le cas où le produit n'a pas de description ou d'ingrédients
        $description = "Description non disponible";
        $ingredients = "Ingrédients non disponibles";
    }
} else {
    // Gérer le cas où l'ID du produit n'est pas défini
    $description = "Description non disponible";
    $ingredients = "Ingrédients non disponibles";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_page1.css">
    <title><?= $produits['objet'] ?></title>
</head>

<body>
    <div class="card-container">
        <div class="card">
            <div class="card-image">
                <a href="<?php echo $produits['image']; ?>" data-lightbox="gallery">
                    <img src="<?php echo $produits['image']; ?>" alt="parfum rosalie">
                </a>

            </div>
        </div>

        <div class="slide-content">
            <div class="card-description">
                <h2>Description</h2>
                <p id="myText"><?= $truncatedDescription ?></p>
            </div>

            <div class="card-description">
                <h2>Ingrédients</h2>
                <p><?= $ingredients ?></p>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>