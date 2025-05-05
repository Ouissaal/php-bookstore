<?php
require 'mysqlconnexion.php';
$pdo = connexion();

session_start();

// Récupération des cookies si existants
$saved_email = $_COOKIE['email_user'] ?? '';
$saved_password = $_COOKIE['password_user'] ?? '';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    $sql = "SELECT * FROM clients WHERE email = ? AND psw = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $pass]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];

        if (isset($_POST['donner_stocker'])) {
            // Stocker email et mot de passe dans les cookies pendant 1 heure
            setcookie("email_user", $email, time() + 3600, "/");
            setcookie("password_user", $pass, time() + 3600, "/");
        } else {
            setcookie("email_user", "", time() - 3600, "/");
            setcookie("password_user", "", time() - 3600, "/");
        }

        header("Location: acceuil.php");
        exit();
    } else {
        echo "<p style='color:red;'><b>Email ou Mot de passe incorrect !</b></p>";
    }
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

<body class="bg-light">
<div class="container p-5" style="max-width: 600px;">

    <form method="POST" action="connexion.php" class="p-5 bg-dark text-secondary rounded">
        <label for="email" class="form-label mt-3">Email :</label>
        <input type="email" id="email" name="email" placeholder="Email" class="form-control"
               value="<?= htmlspecialchars($saved_email) ?>" required>

        <label for="pass" class="form-label mt-3">Mot de passe :</label>
        <input type="password" id="pass" name="pass" placeholder="Mot de passe" class="form-control"
               value="<?= htmlspecialchars($saved_password) ?>" required>

        <div class="form-check mt-3">
            <input type="checkbox" class="form-check-input" name="donner_stocker" id="rememberMe"
                   <?= $saved_email ? 'checked' : '' ?>>
            <label class="form-check-label text-white" for="rememberMe">Mémoriser mes identifiants</label>
        </div>

        <div class="text-center">
            <input type="submit" name="login" value="Connexion" class="btn btn-info mt-5 mb-2">
            <p class="text-white">Ou</p>
            <p class="text-white">Je n'ai pas de compte <a href="create_account.php">Cliquez ici</a></p>
        </div>
    </form>

</div>
</body>
</html>
