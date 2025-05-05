<?php
session_start();

if (isset($_POST['adresse'])) {
    $adresse = htmlspecialchars($_POST['adresse']);

    // Clear the cart
    $_SESSION['panier'] = [];
} else {
    header('Location: acceuil.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Commande</title>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" >
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="alert alert-success text-center" role="alert">
            <h2 class="display-4">Merci pour votre commande !</h2>
            <p>Votre commande sera livrée à : <strong><?php echo $adresse; ?></strong></p>
            <a href="acceuil.php" class="btn btn-primary">Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>
