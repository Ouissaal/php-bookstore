<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <title>Commander</title>
</head>
<body>

<div class="container p-5 " style="width:600px;">

<?php
require 'mysqlconnexion.php';
session_start();
$pdo = connexion();
$theme = $_SESSION['theme'] ?? 'dark';

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT nom, prenom, adresse FROM clients WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $nom = htmlspecialchars($user['nom']);
        $prenom = htmlspecialchars($user['prenom']);
        $adresse_initiale = $user['adresse'];  
    }
} 

else {
    header('Location: connexion.php');
    exit();
}

// Vérifier si le formulaire a été soumis
if (isset($_POST['commander'])) {
    $id_produit = $_POST['id_produit'];
    $quantite = $_POST['quantite'];
    $adresse = $_POST['address'];


    var_dump($_POST); // This will show the values being submitted :)


    // Vérifier si les données nécessaires sont présentes
    if (empty($id_produit) || empty($quantite) || empty($adresse)) {
        echo "<div class='alert alert-danger'>Erreur: Veuillez remplir tous les champs du formulaire.</div>";
    } else {
        try {

            //  Mettre à jour l'adresse du client dans la table "clients"
            if ($adresse != $adresse_initiale) {  // Si l'adresse est différente
                $updateAdresse = "UPDATE clients SET adresse = ? WHERE id = ?";
                $stmtAdresse = $pdo->prepare($updateAdresse);
                $stmtAdresse->execute([$adresse, $user_id]);
            }

            //  Insérer la commande dans la table "commandes"
            $sqlCommande = "INSERT INTO commandes (client_id, produit_id, quantite) VALUES (?, ?, ?)";
            $stmtCommande = $pdo->prepare($sqlCommande);
            $stmtCommande->execute([$user_id, $id_produit, $quantite]);

            header('Location: read_commandes.php');
            exit();
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Erreur: " . $e->getMessage() . "</div>";
        }
    }
}

// Charger les produits
$sqlProduits = "SELECT * FROM produits";
$stmtProduits = $pdo->query($sqlProduits);
$produits = $stmtProduits->fetchAll(PDO::FETCH_ASSOC);
?>

<form method="POST" action="commander.php" class="p-5  <?= $theme === 'dark' ? 'bg-dark text-light border-light' : '' ?>">


  <label class="form-label">Nom :</label>
  <input type="text" name="nom" value="<?= $nom ?>" class="form-control" disabled>


  <label class="form-label mt-3">Prénom :</label>
  <input type="text" name="prenom" value="<?= $prenom ?>" class="form-control" disabled>

  <!-- Produit -->
  <label class="form-label mt-3">Produit :</label>
  <select name="id_produit" class="form-select" required>
    <?php foreach ($produits as $produit): ?>
      <option value="<?= $produit['id'] ?>">
        <?= htmlspecialchars($produit['nom_produit']) ?> - <?= $produit['prix'] ?> DH
      </option>
    <?php endforeach; ?>
  </select>


  <label class="form-label mt-3">Quantité :</label>
  <input type="number" name="quantite" min="1" class="form-control" required>


  <label class="form-label mt-3">Adresse de livraison :</label>
  <input type="text" name="address" placeholder="Votre adresse" class="form-control" required>


<div >
  <button type="submit" name="commander" class="btn btn-warning mt-5 mb-2">Commander</button>
  </div>
  <a href="acceuil.php" class='btn btn-outline-info'> <--</a>
</form>
</div>

</body>
</html>

