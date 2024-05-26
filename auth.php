<?php 
 function checkLogin() {
session_start();
if(isset($_SESSION["user"])) {
    header("Location: index.php");
}  else {
    header("Location: login.php");
}
 }
?>