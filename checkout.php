<?php
session_start();
include 'koneksi.php';
if (isset($_SESSION['user'])) {
  if (!isset($_SESSION['order'])) {
      $_SESSION['order'] = [];
      $total = 0;
  }
  $order = $_SESSION['order'];
} else {
  header('Location: login.php');
}

$total_product = 0;  
$total_product += isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout | Toko Roti Alta Bakery</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="css/style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg shadow-sm" style="background-color: palegoldenrod;">
    <div class="container">
      <a class="navbar-brand fw-bold" style="color: #C15440;" href="index.php">
        <img src="img/logo.png" alt="Logo" width="50" class="d-inline-block align-text-center me-2">
        ALTA BAKERY
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link fw-medium" href="#"><i class="fa-solid fa-cart-shopping"></i> Cart <span class="badge rounded-circle text-bg-danger"><?php echo $total_product; ?></span></a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropbtn fw-medium" href="<?php if(!isset($_SESSION['user'])) {echo 'login.php';} else {echo '#';} ?> ">
            <i class="fa-solid fa-user"></i> Account
            </a>
            <?php if(!isset($_SESSION['user'])): ?>
            <?php else: ?>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item fw-medium" href="profile.php">Profile</a></li>
              <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
              <li><a class="dropdown-item fw-medium" href="dashboard.php">Dashboard</a></li>
              <?php endif; ?>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item fw-medium" href="logout.php">Logout</a></li>
            </ul>
            <?php endif; ?>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Transaction -->
  <div class="container d-flex justify-content-center mt-5">
    <div class="text-center">
    <?php
                 foreach ($order as $id => $orders):
                    ?>
      <h6 class="mb-2">Order Id : <?php echo $orders['id']; ?></h6>
      <h1 class="mb-5">Your Order Has Been Placed</h1>
      <p class="mb-5">Thank you for ordering with us!</p>
      <?php endforeach; ?>
    </div>
  </div>

</body>
<script src="https://kit.fontawesome.com/47dcae39d3.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="js/script.js"></script>
</html>