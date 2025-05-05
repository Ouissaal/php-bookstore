<?php
require 'mysqlconnexion.php';
session_start();
$pdo = connexion();


if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

if (isset($_POST['ajouter_produit'])) {
    $nom_produit = $_POST['nom_produit'];
    $prix = $_POST['prix'];

    if (empty($nom_produit) || empty($prix) || !is_numeric($prix)) {
        $erreur = "Le nom du produit et le prix sont obligatoires et le prix doit être un nombre valide.";
    } else {
        // Gestion du fichier image
        $image_path = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = basename($_FILES['image']['name']);
            $uploadFileDir = 'products_img/';
            $dest_path = $uploadFileDir . $fileName;

            // Déplace l'image vers le dossier uploads
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $image_path = $dest_path;
            } else {
                $erreur = "Erreur lors du téléchargement de l'image.";
            }
        }

        if (!isset($erreur)) {
            $sql = "INSERT INTO produits (nom_produit, prix, image) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom_produit, $prix, $image_path]);

            header("Location: acceuil.php");
            exit();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <title>Ajouter un produit</title>
</head>
<body class='bg-secondary text-primary'>

<div class="container mt-5 text-center" style="width:600px;">

    <h2>Ajouter un nouveau produit</h2>

    <?php if (isset($erreur)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($erreur) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($succes)): ?>
        <div class="alert alert-success" role="alert">
            <?= htmlspecialchars($succes) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="ajouter_produit.php" enctype="multipart/form-data" class="p-4 bg-light border rounded" >
        <div class="mb-3">
            <label for="nom_produit" class="form-label">Nom du produit</label>
            <input type="text" class="form-control" id="nom_produit" name="nom_produit" required>
        </div>

        <div class="mb-3">
        <label for="image" class="form-label">Image du produit</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*">
        </div>

        <div class="mb-3">
            <label for="prix" class="form-label">Prix du produit (en DH)</label>
            <input type="number" class="form-control" id="prix" name="prix" min="0" step="0.01" required>
        </div>

        <button type="submit" name="ajouter_produit" class="btn btn-primary">Ajouter le produit</button>
       
    </form>
</div>
<a href="admin.php" class='btn btn-outline-warning'> <-- return</a>
</body>
</html>
