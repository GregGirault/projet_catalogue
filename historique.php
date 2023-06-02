<?php
session_start();

require_once("connect.php");

if ($_POST) {
    if (
        !empty($_POST["objet"]) && !empty($_POST["description"])
        && !empty($_POST["image"]) && !empty($_POST["categorie_id"])
    ) {
        $objet = strip_tags($_POST["objet"]);
        $description = strip_tags($_POST["description"]);
        $image = strip_tags($_POST["image"]);
        $categorie_id = strip_tags($_POST["categorie_id"]);

        $sql = "INSERT INTO produits (objet, description, image, categorie_id) 
                VALUES (:objet, :description, :image, :categorie_id)";
        $query = $db->prepare($sql);
        $query->bindValue(":objet", $objet, PDO::PARAM_STR);
        $query->bindValue(":description", $description, PDO::PARAM_STR);
        $query->bindValue(":image", $image, PDO::PARAM_STR);
        $query->bindValue(":categorie_id", $categorie_id, PDO::PARAM_STR);
        $query->execute();

        $_SESSION["toast_message"] = "Ajouté avec succès";
        $_SESSION["toast_type"] = "success";

        header("Location: historique.php");
        exit();
    }
}

if (isset($_GET['page']) && !empty($_GET['page'])) {
    $currentPage = (int) strip_tags($_GET['page']);
} else {
    $currentPage = 1;
}

$sql = 'SELECT COUNT(*) AS nb_articles FROM `produits`;';
$query = $db->prepare($sql);
$query->execute();
$results = $query->fetch();
$nbArticles = (int) $results['nb_articles'];

$parPage = 5;
$pages = ceil($nbArticles / $parPage);
$premier = ($currentPage - 1) * $parPage;

$sql = 'SELECT * FROM `produits` ORDER BY `objet` ASC LIMIT :premier, :parpage;';
$query = $db->prepare($sql);
$query->bindValue(':premier', $premier, PDO::PARAM_INT);
$query->bindValue(':parpage', $parPage, PDO::PARAM_INT);
$query->execute();
$articles = $query->fetchAll(PDO::FETCH_ASSOC);

require_once("close.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <title>Historique des changements d'ampoules</title>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <?php if (isset($_SESSION["toast_message"]) && isset($_SESSION["toast_type"])) : ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toastify({
                    text: "<?php echo $_SESSION["toast_message"]; ?>",
                    duration: 3000,
                    destination: "https://github.com/apvarun/toastify-js",
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    position: "center",
                    image: "center",
                    stopOnFocus: true,
                    style: {
                        background: "linear-gradient(45deg, #555, #333)",
                        borderRadius: "10px",
                        textAlign: "center",
                        display: "flex",
                        alignItems: "center",
                        justifyContent: "center",
                        border: "1px solid white",
                        opacity: "0.95"
                    },
                    onClick: function() {}
                }).showToast();
            });
        </script>
    <?php
        unset($_SESSION["toast_message"]);
        unset($_SESSION["toast_type"]);
    endif;
    ?>
</head>

<body>
    <h1 class="titre-principal">Historique des ajouts</h1>
    <table class="w-full bg-white shadow-md rounded mb-6">
        <thead>
            <tr>
                <th class="px-4 py-2 text-left">Objet</th>
                <th class="px-4 py-2 text-left">Description</th>
                <th class="px-4 py-2 text-left">Image</th>
                <th class="px-4 py-2 text-left">Catégorie ID</th>
                <th class="px-4 py-2 text-left">Modifier / Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $produit) { ?>
                <tr>
                    <td class="px-4 py-2"><?= $produit['objet'] ?></td>
                    <td class="px-4 py-2"><?= $produit['description'] ?></td>
                    <td class="px-4 py-2"><?= $produit['image'] ?></td>
                    <td class="px-4 py-2"><?= $produit['categorie_id'] ?></td>
                    <td class="px-4 py-2">
                        <a class="modify-link btn-modif text-blue-500 hover:text-blue-700" href="modifier.php?id=<?= $produit['id'] ?>" onclick="modif(event)">Modifier</a>
                        <a class="delete-link btn-suppr text-red-500 hover:text-red-700" href="supprimer.php?id=<?= $produit['id'] ?>" onclick="supprimer(event)">Supprimer</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <nav class="flex justify-center mt-4">
        <ul class="pagination flex items-center space-x-4">
            <li class="pagination-item <?php if ($currentPage == 1) echo 'disabled'; ?>">
                <a href="./historique.php?page=<?php echo $currentPage - 1; ?>" class="pagination-link">&laquo; Précédente</a>
            </li>

            <?php for ($page = 1; $page <= $pages; $page++) { ?>
                <li class="pagination-item">
                    <a href="./historique.php?page=<?php echo $page; ?>" class="pagination-link <?php if ($currentPage == $page) echo 'active'; ?>"><?php echo $page; ?></a>
                </li>
            <?php } ?>

            <li class="pagination-item <?php if ($currentPage == $pages) echo 'disabled'; ?>">
                <a href="./historique.php?page=<?php echo $currentPage + 1; ?>" class="pagination-link">Suivante &raquo;</a>
            </li>
        </ul>
    </nav>

                <a href="ajout.php">Ajouter</a>


    <br><br>

    <br>

    <script>
        function modif(event) {
            event.preventDefault();

            const confirmationBox = document.createElement('div');
            confirmationBox.className = 'confirmation';

            const message = document.createElement('p');
            message.textContent = 'Êtes-vous sûr de vouloir modifier cet élément ?';

            const confirmButton = document.createElement('button');
            confirmButton.textContent = 'Oui, modifier';
            const cancelButton = document.createElement('button');
            cancelButton.textContent = 'Annuler';
            cancelButton.className = 'cancel';

            confirmButton.addEventListener('click', function() {
                window.location.href = event.target.href;
                confirmationBox.remove();
            });

            cancelButton.addEventListener('click', function() {
                confirmationBox.remove();
            });

            confirmationBox.appendChild(message);
            confirmationBox.appendChild(confirmButton);
            confirmationBox.appendChild(cancelButton);
            document.body.prepend(confirmationBox);
        }

        function supprimer(event) {
            event.preventDefault();

            const confirmationBox = document.createElement('div');
            confirmationBox.className = 'confirmation';

            const message = document.createElement('p');
            message.textContent = 'Êtes-vous sûr de vouloir supprimer cet élément ?';

            const confirmButton = document.createElement('button');
            confirmButton.textContent = 'Oui, supprimer';
            const cancelButton = document.createElement('button');
            cancelButton.textContent = 'Annuler';
            cancelButton.className = 'cancel';

            confirmButton.addEventListener('click', function() {
                window.location.href = event.target.href;
                confirmationBox.remove();
            });

            cancelButton.addEventListener('click', function() {
                confirmationBox.remove();
            });

            confirmationBox.appendChild(message);
            confirmationBox.appendChild(confirmButton);
            confirmationBox.appendChild(cancelButton);
            document.body.prepend(confirmationBox);
        }
    </script>

    <?php require_once("close.php"); ?>
</body>

</html>