<?php

require 'databaseTemplate.php';
require 'functions.php';
$carIndexToDelete=$_GET['index'];
$deletionQuery = "DELETE FROM cars WHERE carIndex=?";
$stmt = $pdo->prepare($deletionQuery);
$stmt->execute([$carIndexToDelete]);
if(isset($_GET['redirectadmin'])==true) 
{
    redirect($_SERVER["HTTP_REFERER"]);// Put the user back to the page they were on
}
else
{
    redirect('account.php');

}
