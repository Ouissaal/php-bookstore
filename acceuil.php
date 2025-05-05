<?php 
session_start();
require 'mysqlconnexion.php';
$pdo = connexion();

// Gérer le thème
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'dark';
}
if (isset($_POST['toggle_theme'])) {
    $_SESSION['theme'] = $_SESSION['theme'] === 'dark' ? 'light' : 'dark';
}
$theme = $_SESSION['theme'];

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
} else {
    header('Location: connexion.php');
    exit();
}

// Initialiser le panier si pas encore créé
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Ajouter un produit au panier
if (isset($_POST['Ajouter']) && isset($_POST['produit_nom']) && isset($_POST['produit_prix'])) {
    $_SESSION['panier'][] = [
        'nom' => $_POST['produit_nom'],
        'prix' => $_POST['produit_prix']
    ];
}

// Récupération des produits
$stmt = $pdo->query('SELECT * FROM produits');
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
$nombre_articles = count($_SESSION['panier']);

// Calculer le total
$total = 0;
foreach ($_SESSION['panier'] as $produit) {
    $total += $produit['prix'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link rel="shortcut icon" href="./products_img/logo.png" type="image/x-icon">
    <title>Accueil</title>
</head>
<body class="<?= $theme === 'dark' ? 'bg-dark text-light' : 'bg-light text-dark' ?>">

<div class="navbar navbar-expand-lg <?= $theme === 'dark' ? 'navbar-dark bg-dark' : 'navbar-light bg-light' ?> fixed-top">
    <h4 class="nav-brand mx-2">OB STORE</h4>
    <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <a class="nav-link <?= $theme === 'dark' ? 'text-light' : 'text-dark' ?>" href="commander.php">Commander</a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link <?= $theme === 'dark' ? 'text-light' : 'text-dark' ?>" data-toggle="modal" data-target="#cartModal">
                Panier <span class="badge bg-success"><?= $nombre_articles ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $theme === 'dark' ? 'text-light' : 'text-dark' ?>" href="#" data-toggle="modal" data-target="#accountModal">Mon compte</a>
        </li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link <?= $theme === 'dark' ? 'text-secondary' : 'text-dark'; ?>" href="logout.php">Déconnexion</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link <?= $theme === 'dark' ? 'text-secondary' : 'text-dark'; ?>" href="login.php">Connexion</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $theme === 'dark' ? 'text-secondary' : 'text-dark'; ?>" href="create_account.php">Inscription</a>
          </li>
        <?php endif; ?>

        <li class="nav-item ms-3">
            <form method="POST">
                <button type="submit" name="toggle_theme" class="btn btn-outline-<?= $theme === 'dark' ? 'light' : 'dark' ?>">
                    <?= $theme === 'dark' ? ' Mode clair' : ' Mode sombre' ?>
                </button>
            </form>
        </li>
    </ul>
</div>

<!-- Hero section -->
<div class="container text-center py-5 mt-5">
    <h1 class="typewriter-text">Welcome to OB Store</h1>
    <p class="lead <?= $theme === 'dark' ? 'text-light' : 'text-muted' ?> mt-3">
        OB Store — Read, learn, and dream.
    </p>
</div>

<!-- Panier Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content <?= $theme === 'dark' ? 'bg-dark text-light' : '' ?>">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel">Votre Panier</h5>
                <button type="button" class="btn btn-danger ms-auto" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if (empty($_SESSION['panier'])): ?>
                    <div class="alert alert-danger">Votre panier est vide.</div>
                <?php else: ?>
                    <ul class="list-group mb-4">
                        <?php foreach ($_SESSION['panier'] as $produit): ?>
                            <li class="list-group-item <?= $theme === 'dark' ? 'bg-dark text-light border-secondary' : '' ?>">
                                <?= htmlspecialchars($produit['nom']) ?> - <?= htmlspecialchars($produit['prix']) ?> DH
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <h4 class="mb-4">Total: <?= $total ?> DH</h4>

                    <form method="post" action="validation.php">
                        <div class="mb-3">
                            <label for="adresse" class="form-label">Adresse de livraison</label>
                            <input type="text" class="form-control" name="adresse" id="adresse" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Valider la commande</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Compte Modal -->
<div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content <?= $theme === 'dark' ? 'bg-dark text-light' : '' ?>">
            <div class="modal-header">
                
                <h5 class="modal-title text-center text-success" id="cartModalLabel">Votre Compte</h5>
                <button type="button" class="btn btn-outline-danger  ms-auto" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group mb-4">
                    <li class="list-group-item <?= $theme === 'dark' ? 'bg-dark text-light border-secondary' : '' ?>">
                        utilisateur : <?= htmlspecialchars($user['nom']) . "-" . htmlspecialchars($user['prenom']); ?>
                    </li>
                </ul>
              
            </div>
        </div>
    </div>
</div>

<div class="container p-5">
    <div class="row justify-content-center p-5 <?= $theme === 'dark' ? 'bg-secondary' : 'bg-light' ?>">
        <?php foreach ($produits as $produit): ?>
            <div class="col-md-4 d-flex justify-content-center gx-2 gy-3">
                <div class="card  shadow p-1 m-4 <?= $theme === 'dark' ? 'bg-dark text-light border-light' : '' ?>" style="width: 100%; max-width: 370px;">
                    <img src="<?= htmlspecialchars($produit['image'] ?? 'products_img/default.png') ?>" class="card-img-top" alt="<?= htmlspecialchars($produit['nom_produit']) ?>">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= htmlspecialchars($produit['nom_produit']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($produit['prix']) ?> DH</p>
                        <form method="post">
                            <input type="hidden" name="produit_nom" value="<?= htmlspecialchars($produit['nom_produit']) ?>">
                            <input type="hidden" name="produit_prix" value="<?= htmlspecialchars($produit['prix']) ?>">
                            <button type="submit" name="Ajouter" class="btn btn-success">Ajouter</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<footer class=" p-4 mt-4 <?php echo $theme === 'dark' ? 'bg-dark' : 'bg-light'; ?>">
    <p class="text-secondary text-center ">Créé par Bouamar -- Ouissal</p>
</footer>
</body>
</html>
