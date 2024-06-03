<?php
require 'koneksi.php';
session_start();
//cek sesi login, jika ada sesi akan diarahkan ke halaman index
if (isset($_SESSION['user']) && $_SESSION['user'] === true) {
  header('Location: index.php'); 
  exit();
}

//submit data login
if(isset($_POST['submit'])){

  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  $sql = "SELECT * FROM user WHERE username=:username";
  $sql2 = "UPDATE `user` set last_login := now() where username=:username";
  $stmt = $db->prepare($sql);
  $stmt2 = $db->prepare($sql2);
  // bind parameter ke query
  $params = array(
    ":username" => $username,
  );
  $stmt->execute($params);
  $stmt2->execute($params);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // jika user terdaftar
  if($user){
    if($user["username"] == "admin") {
      if(password_verify($password, $user["password"])){
        // buat Session
        session_start();
        $_SESSION["user_id"] = $user['user_id'];
        $_SESSION['user_name'] = $user['username'];
        $_SESSION["user"] = true;
        $_SESSION['role'] = $user['role'];
        // login sukses, alihkan ke halaman index
        header("Location: index.php");
    } else {
      $error = 'Password yang anda masukkan salah!';
    }
    } else {
      // verifikasi password
      if(password_verify($password, $user["password"])){
          // buat Session
          session_start();
          $_SESSION["user_id"] = $user['user_id'];
          $_SESSION['user_name'] = $user['username'];
          $_SESSION['user'] = true;
          $_SESSION['role'] = $user['role'];
          // login sukses, alihkan ke halaman index
          header("Location: index.php");
      }
      else {
        $error = 'Password yang anda masukkan salah!';
         }
    } 
  } else {
    $error = "Username tidak ditemukan!";
  }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In | Toko Roti Alta Bakery</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="css/style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
  <div class="container-fluid">
    <div class="row min-vh-100 d-flex justify-content-center align-items-center">
      <div class="col-md-6 d-none d-md-block"  style="background-color: palegoldenrod;">
        <div class="min-vh-100 d-flex justify-content-center align-items-center">
          <img class="img-fluid" style="width: 60%;" src="img/logo.png" alt="">
        </div>
      </div>
      <div class="col-md-6">
        <div class="container p-5">
            <h1 style="text-align: center; margin-bottom: 20px; font-weight: bold;">Sign In</h1>
            <form action="" method="POST">
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control rounded-pill" name="username" id="username" required />
              </div>
              <div class="mb-2">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control rounded-pill" name="password" id="password" required />
              </div>
              <p class="mb-3" style="cursor:pointer;" onclick="showPassword()"><span class="field-icon fa fa-fw fa-eye toggle-password"></span> Show Password</p>
              <button name="submit" type="submit" class="btn btn-primary rounded-pill mb-3 w-100">Sign In</button>
              <p style="text-align: center;">Dont have an account? <a href="signup.php" style="color: black; font-weight: 500;">Sign Up</a></p>
            </form>  
          <?php 
          if (isset($error)) {
            echo "<p style='color: red;'>$error</p>";
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</body>
<script src="https://kit.fontawesome.com/47dcae39d3.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="js/script.js"></script>
</html>