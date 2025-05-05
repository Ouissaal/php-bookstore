<?php 
function connexion(){
    try{
        $conn = new PDO('mysql:local=localhost; dbname=mystore','root','');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } 
    catch(PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}


?>

