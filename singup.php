<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="src/css/style.css">
  <title>Sing Up Account</title>
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
        <label for="name">Full Name</label>
        <input id="name" type="text" placeholder="" />
        <label for="email">Email</label>
        <input id="email" type="email" placeholder="" />
        <label for="password">Password</label>
        <input id="password" type="password" placeholder="" /> <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
        <button class="btn">Sing Up</button>
        <p style="text-align: center;">Already have an account? <a href="login.php" style="color: black;">Log In</a></p>
      </div>
    </div>
  </div>
</body>
<script src="https://kit.fontawesome.com/47dcae39d3.js" crossorigin="anonymous"></script>
<script src="src/js/script.js"></script>
</html>