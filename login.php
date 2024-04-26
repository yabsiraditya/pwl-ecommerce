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
        <label for="email">Email</label>
        <input id="email" type="email" placeholder="" />
        <label for="password">Password</label>
        <input id="password" type="password" placeholder="" /> <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
        <button class="btn">Login</button>
        <p style="text-align: center;">Dont have an account? <a href="singup.php" style="color: black;">Sing Up</a></p>
      </div>
    </div>
  </div>
</body>
<script src="https://kit.fontawesome.com/47dcae39d3.js" crossorigin="anonymous"></script>
<script src="src/js/script.js"></script>
</html>