<?php
require_once("koneksi.php");

if(isset($_POST['register'])){

    // filter data yang diinputkan
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    // enkripsi password
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);


    // menyiapkan query
    $sql = "INSERT INTO user (name, username, email, password) 
            VALUES (:name, :username, :email, :password)";
    $stmt = $db->prepare($sql);

    // bind parameter ke query
    $params = array(
        ":name" => $name,
        ":username" => $username,
        ":password" => $password,
        ":email" => $email
    );

    // eksekusi query untuk menyimpan ke database
    $saved = $stmt->execute($params);

    // jika query simpan berhasil, maka user sudah terdaftar
    // maka alihkan ke halaman login
    if($saved) header("Location: login.php");
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="src/css/style.css">
  <title>Sign Up Account</title>
</head>
<body class="login">
  <div class="container">  
    <div class="splitdiv" id="leftdiv">
      <div id="leftdivcard">

      </div>
    </div>
    <div class="splitdiv" id="rightdiv">
      <div id="rightdivcard">
        <h1 style="text-align: center; margin-bottom: 20px;">Sign Up</h1>
        <form action="" method="POST">
        <label for="name">Full Name</label>
        <input id="name" type="text" name="name" placeholder="" />
        <label for="email">Email</label>
        <input id="email" type="email" name= "email" placeholder="" />
        <label for="username">Username</label>
        <input id="username" type="text" name="username" placeholder="" />
        <label for="password">Password</label>
        <input id="password" type="password" placeholder="" name="password"  /> <span onclick="showPassword()" class="fa fa-fw fa-eye field-icon toggle-password"></span>
        <button class="btn" name="register">Sign Up</button>
        </form>
        <p style="text-align: center;">Already have an account? <a href="login.php" style="color: black;">Log In</a></p>
      </div>
    </div>
  </div>
</body>
<script src="https://kit.fontawesome.com/47dcae39d3.js" crossorigin="anonymous"></script>
<script src="src/js/script.js"></script>
</html>