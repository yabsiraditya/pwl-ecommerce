<?php 
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "pwl";
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>