<?php
require 'mysqlconnexion.php';
session_start();
$pdo = connexion();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les commandes de l'utilisateur
$sqlCommandes = "SELECT c.id AS commande_id, c.quantite, p.nom_produit, p.prix 
                 FROM commandes c
                 JOIN produits p ON c.produit_id = p.id
                 WHERE c.client_id = ?";
                 
$stmtCommandes = $pdo->prepare($sqlCommandes);
$stmtCommandes->execute([$user_id]);
$commandes = $stmtCommandes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Acceuil</title>
</head>

<body class="bg-secondary">

<div class="container mt-5 p-5">
    <h1 class="text-center text-dark">-- Mes Commandes -- </h1>
    
    <?php if (count($commandes) > 0): ?>
        <table class="table table-striped mt-5">
            <thead>
                <tr>
                    <th>ID Commande</th>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire (DH)</th>
                    <th>Total (DH)</th>
                   
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commandes as $commande): ?>
                    <tr>
                        <td><?= htmlspecialchars($commande['commande_id']) ?></td>
                        <td><?= htmlspecialchars($commande['nom_produit']) ?></td>
                        <td><?= htmlspecialchars($commande['quantite']) ?></td>
                        <td><?= number_format($commande['prix'], 2) ?></td>
                        <td><?= number_format($commande['prix'] * $commande['quantite'], 2) ?></td>
                    </tr>
                    
                
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="admin.php" class='btn btn-outline-warning'> <-- return</a>
    <?php else: ?>
        <div class="alert alert-info">Vous n'avez pas encore passé de commande.</div>
        
    <?php endif; ?>
</div>

</body>
</html>
