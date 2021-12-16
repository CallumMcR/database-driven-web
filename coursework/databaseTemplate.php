<?php


// Variables for DB connection
$host = 'localhost';
$db = 'cars';
$admin = 'root';
$password = '';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE        => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// Try establish connection to db, if not display error

try
{
    $pdo = new PDO($dsn,$admin, $password, $options);
}
catch(\PDOException $e)
{
    throw new \PDOException($e->getMessage(),(int)$e->getCode());
}


?>