<?php
require 'koneksi.php';
if(isset($_POST['submit'])){

  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  $sql = "SELECT * FROM user WHERE username=:username";
  $stmt = $db->prepare($sql);
  
  // bind parameter ke query
  $params = array(
      ":username" => $username,
  );

  $stmt->execute($params);

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // jika user terdaftar
  if($user){
      // verifikasi password
      if(password_verify($password, $user["password"])){
          // buat Session
          session_start();
          $_SESSION["user"] = $user;
          // login sukses, alihkan ke halaman timeline
          header("Location: index.php");
      }
  }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="src/css/style.css">
  <title>Login Account</title>
</head>
<body class="login">
  <div class="container">  
    <div class="splitdiv" id="leftdiv">
      <div id="leftdivcard">

      </div>
    </div>
    <div class="splitdiv" id="rightdiv">
      <div id="rightdivcard">
        <h1 style="text-align: center; margin-bottom: 20px;">Sign In</h1>
        <form action="" method="POST">
        <label for="username">Username</label>
        <input id="username" type="text" name="username" placeholder="" />
        <label for="password">Password</label>
        <input id="password" type="password"  name= "password"placeholder="" /> <span onclick="showPassword()" class="fa fa-fw fa-eye field-icon toggle-password"></span>
        <button class="btn" name="submit">Login</button>
        <p style="text-align: center;">Dont have an account? <a href="signup.php" style="color: black;">Sign Up</a></p>
        </form>
      </div>
      <?php 
      if (isset($error)) {
        echo "<p style='color: red;'>$error</p>";
      }
      ?>
    </div>
  </div>
</body>
<script src="https://kit.fontawesome.com/47dcae39d3.js" crossorigin="anonymous"></script>
<script src="src/js/script.js"></script>
</html>