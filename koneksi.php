<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "pwl";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass, $options);

} catch(PDOException $e) {
    die("terjadi masalah " + $e->getMessage());
}


?>