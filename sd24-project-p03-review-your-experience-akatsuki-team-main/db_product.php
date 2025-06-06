<?php
//Plaats hier je database connectie
try {
    $db = new PDO("mysql:host=localhost;dbname=clothes", "root", "");
} catch (PDOException $e) {
    die('Error : ' . $e->getMessage());
}

$query = $db->prepare('SELECT * FROM `product`');
$query->execute();

$pants = $query->fetchAll(PDO::FETCH_ASSOC);
?>
