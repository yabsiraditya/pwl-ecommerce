<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="src/css/style.css">
  <title>Toko Roti </title>
</head>
<body>
  <!-- Navbar -->
  <header>
    <div class="navbar">
      <ul class="nav">
        <li class="item">
            <a href="index.html" class="dropbtn"><i data-count="3" class="fa-md fa-solid fa-cart-shopping"></i> Cart  <span class="badge">0</span></a>
        </li>
        <li class="item">
          <div class="dropdown">
            <a class="nav-link dropbtn" href="<?php echo isset($_SESSION['user']) && $_SESSION['user'] ? '#' : 'login.php' ?>"></i> Account</a>
            <div class="dropdown-content">
              <a href="#" class="nav-link">Profile</a>
              <a href="#" class="nav-link">Dashboard</a>
              <a href="logout.php" class="nav-link">Logout</a>
            </div>
          </div>
        </li>
      </ul>

      <div class="logo">
        <img src="https://source.unsplash.com/random/20%C3%9720/?fruit">
      </div>
    </div>
  </header>
  
  <!-- Slideshow  -->
  <section>
  <div class="slideshow-container">
    <div class="mySlides fade">
      <img src="https://source.unsplash.com/random/?Bread&1" style="width:100%; height: 600px; object-fit: cover;">
    </div>
    <div class="mySlides fade">
      <img src="https://source.unsplash.com/random/?Bread&2" style="width:100%; height: 600px; object-fit: cover;">
    </div>
    <div class="mySlides fade">
      <img src="https://source.unsplash.com/random/?Bread&3" style="width:100%; height: 600px; object-fit: cover;">
    </div>

    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>
  </div>

  <div style="text-align:center">
    <span class="dot" onclick="currentSlide(1)"></span>
    <span class="dot" onclick="currentSlide(2)"></span>
    <span class="dot" onclick="currentSlide(3)"></span>
  </div>
  </section>

  <!-- Search Bar -->
  <div class="search-bar">
    <form id="searchthis" action="#" method="get">
      <input class="search-box" name="search" type="text" placeholder="Search"/>
      <button class="search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
  </div>

</body>
<script src="https://kit.fontawesome.com/47dcae39d3.js" crossorigin="anonymous"></script>
<script src="src/js/script.js"></script>
</html>