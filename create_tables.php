<?php
require 'mysqlconnexion.php';
$pdo = connexion();

$sql = "CREATE TABLE IF NOT EXISTS clients (
    id INT(6) AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(60) NOT NULL,
    prenom VARCHAR(60) NOT NULL,
    email VARCHAR(60) NOT NULL UNIQUE,
    telephone INT(10) NOT NULL,
    psw VARCHAR(60) NOT NULL,
    adresse VARCHAR(60) NULL
)";

$pdo->exec($sql);


$sql = "CREATE TABLE IF NOT EXISTS produits (
    id INT(6) AUTO_INCREMENT PRIMARY KEY,
    nom_produit VARCHAR(100) NOT NULL,
    prix DECIMAL(10,2) NOT NULL
)";
$pdo->exec($sql);


$sql = "CREATE TABLE IF NOT EXISTS commandes (
    id INT(6) AUTO_INCREMENT PRIMARY KEY,
    client_id INT(6) NOT NULL,
    produit_id INT(6) NOT NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (produit_id) REFERENCES produits(id),
    quantite INT(3) NOT NULL
)";
$pdo->exec($sql);

echo "Les trois tables ont été créées avec succès !";
?>
