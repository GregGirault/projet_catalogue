<?php 
include 'header.php';
require_once 'connect.php';

$sql = "SELECT * FROM produits 
        INNER JOIN commentaires ON produits.id_commentaires = commentaires.id";
$query = $db->query($sql);
$produits = $query->fetchAll(PDO::FETCH_ASSOC);

$truncatedDescription = "Description non disponible";
$ingredients = "Ingrédients non disponibles";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql_produits = "SELECT * FROM produits WHERE id = :id";
    $query_produits = $db->prepare($sql_produits);
    $query_produits->bindParam(':id', $id);
    $query_produits->execute();
    $produit = $query_produits->fetch();

    if (isset($produit['description']) && isset($produit['ingredients'])) {
        $description = $produit['description'];
        $truncatedDescription = substr($description, 0, 400); // Tronque la description à 400 caractères
        $ingredients = $produit['ingredients'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["submit"])) {
    if (!empty($_POST["notes"]) && !empty($_POST["avis"])) {
        $notes = strip_tags($_POST["notes"]);
        $avis = strip_tags($_POST["avis"]);
        $id_produit = $produit['id']; // Récupère l'ID du produit associé au commentaire
        $sql = "INSERT INTO commentaires (id_produit, notes, avis) VALUES(:id_produit, :notes, :avis)";
        $query = $db->prepare($sql);
        $query->bindValue(":id_produit", $id_produit, PDO::PARAM_INT);
        $query->bindValue(":notes", $notes, PDO::PARAM_INT);
        $query->bindValue(":avis", $avis, PDO::PARAM_STR);
        $query->execute();
    }
}

// Récupérer tous les commentaires et les regrouper par produit
$sql_commentaires = "SELECT * FROM commentaires";
$query_commentaires = $db->query($sql_commentaires);
$commentaires_list = $query_commentaires->fetchAll(PDO::FETCH_ASSOC);

$commentaires_par_produit = array();

foreach ($commentaires_list as $commentaire) {
    $produit_id = $commentaire['id_produit'];

    if (!isset($commentaires_par_produit[$produit_id])) {
        $commentaires_par_produit[$produit_id] = array();
    }

    $commentaires_par_produit[$produit_id][] = $commentaire;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_page1.css">
    <title><?= $produit['objet'] ?></title>
    <style>
        .stars {
            display: inline-block;
            font-size: 24px;
            cursor: pointer;
        }

        .stars .star {
            color: #ddd;
        }

        .stars .star:hover,
        .stars .star.active {
            color: #ffcc00;
        }
    </style>
</head>

<body>
    <div class="card-container">
        <div class="card">
            <div class="card-image">
                <a href="<?php echo $produit['image']; ?>" data-lightbox="gallery">
                    <img src="<?php echo $produit['image']; ?>" alt="parfum rosalie">
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

    <div class="card-notes">
        <h2>AVIS</h2>
        <?php if (isset($produit['id'])) : ?>
            <?php $produit_id = $produit['id']; ?>
            <?php if (isset($commentaires_par_produit[$produit_id])) : ?>
                <?php foreach ($commentaires_par_produit[$produit_id] as $commentaire) : ?>
                    <p><?= $commentaire['avis'] ?></p>
                    <p>Note: <?= $commentaire['notes'] ?></p>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Aucun commentaire disponible pour ce produit.</p>
            <?php endif; ?>
        <?php else : ?>
            <p>Veuillez sélectionner un produit pour afficher les commentaires.</p>
        <?php endif; ?>
    </div>

    <div class="notes-contenaire">
        <div class="notes">
            <form method="post" action="">
                <div class="stars">
                    <span class="star" data-value="1">&#9733;</span>
                    <span class="star" data-value="2">&#9733;</span>
                    <span class="star" data-value="3">&#9733;</span>
                    <span class="star" data-value="4">&#9733;</span>
                    <span class="star" data-value="5">&#9733;</span>
                </div>
                <input type="hidden" id="rating" name="notes">
        </div>
        <div class="avis">
            <input type="text" name="avis" placeholder="Avis">
            <input type="submit" name="submit" value="Envoyer">
        </div>
        </form>
    </div>

    <script>
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const value = star.getAttribute('data-value');
                ratingInput.value = value;
                stars.forEach(star => star.classList.remove('active'));
                star.classList.add('active');
            });
        });
    </script>

    <?php include 'footer.php'; ?>
</body>

</html>
