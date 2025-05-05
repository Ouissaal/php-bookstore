<?php 

$conn = new PDO('mysql:host=localhost', 'root','');
$sql = 'CREATE DATABASE IF NOT EXISTS mystore';
$conn->exec($sql);
echo "database create succesful ";

?>
