<?php

require 'databaseTemplate.php';

$make_id = $_GET["carMake_id"];
$getModels = $pdo->prepare("SELECT model FROM cars WHERE make='".$make_id."' ANd purchased=0 GROUP BY model");
$getModels->execute();
while($row = $getModels->fetch()){ // Get all of the car models from the query and return them
    $carModel = $row['model'];
    echo "<option value='$carModel'>$carModel</option>";
}