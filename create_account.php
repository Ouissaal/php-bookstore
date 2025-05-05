<?php
require 'mysqlconnexion.php'; 
$pdo = connexion();

if (isset($_POST['submit'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $pass = $_POST['pass']; 

    $sql = "INSERT INTO clients (nom, prenom, email, telephone, psw, adresse)  VALUES (?, ?, ?, ?, ?, NULL)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $prenom, $email, $tel, $pass]);

    setcookie("nom", $nom, time() + 3600, "/");     
    setcookie("prenom", $prenom, time() + 3600, "/");

    header("Location: connexion.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <title>Create Account</title>
</head>
<body class='bg-light'>
<div class="container p-5 " style="width:600px;">
<form method="POST" action="create_account.php" class=" p-5 bg-dark text-secondary">
  <label for="" class="form-label mt-3">NOM:</label>
  <input type="text" name="nom" placeholder="Nom" class="form-control" required>

  <label for="" class="form-label mt-3">PRENOM :</label>
  <input type="text" name="prenom" placeholder="Prénom" class="form-control" required>

  <label for="" class="form-label mt-3">EMAIL :</label>
  <input type="email" name="email" placeholder="Email" class="form-control" required>

  <label for="" class="form-label mt-3">TELEPHONE :</label>
  <input type="text" name="tel" placeholder="Téléphone" class="form-control" required>

  <label for="" class="form-label mt-3">Mot de Passe :</label>
  <input type="password" name="pass" placeholder="Mot de passe" class="form-control" required>

  <div class="m-4 text-center">
  <button type="submit" name="submit" class="btn bg-danger mb-2 ">Créer un compte</button>
  <p>Ou</p>
<p>J'ai déjà un compte <a href="connexion.php">Cliquez ici</a></p>

  </div>
</form>
</div>


</body>
</html>