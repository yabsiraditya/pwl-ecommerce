<?php 
session_start();
require 'koneksi.php';

//cek valid id produk
if (!isset($_GET['id']) ) {
  echo "ID produk tidak valid.";
  exit;
}

//cek id produk dari database
$id = $_GET['id'];
$sql = "SELECT * FROM produk WHERE id_produk = :id";
$stmt = $db->prepare($sql);
$params = array(
  ":id" => $id,
);
$stmt->execute($params);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

//jika id produk tidak ditemukan
if (!$product) {
  echo "Produk tidak ditemukan.";
  exit;
}

//menambahkan produk ke cart dimasukkan ke dalam session cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
  if(isset($_SESSION['user'])) {
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
  }
  if (isset($_SESSION['cart'][$id])) {
      $_SESSION['cart'][$id]['quantity']++;
  } else {
      $_SESSION['cart'][$id] = [
          'nama' => $product['nama'],
          'desc_produk' => $product['desc_produk'],
          'harga' => $product['harga'],
          'gambar' => $product['gambar'],
          'quantity' => 1
      ];
  }
  // Redirect ke halaman keranjang setelah menambahkan produk
  header("Location: cart.php");
  exit;
  } else {
    header("Location: login.php");
    exit;
  }
}

//jumlah item di keranjang
$total_product = 0;  
$total_product += isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
$fmt = new NumberFormatter($locale = 'id_ID', NumberFormatter::CURRENCY);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $product['nama']; ?> | Toko Roti Alta Bakery</title>
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
        <?php if(!isset($_SESSION['user'])): ?>
          <?php else: ?>
          <li class="nav-item">
            <a class="nav-link fw-medium" href="#"><i class="fa-solid fa-cart-shopping"></i> Cart <span class="badge rounded-circle text-bg-danger"><?= $total_product; ?></span></a>
          </li>
          <?php endif; ?>
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
  
  <div class="container mt-5">
    <div class="row align-items-center">
      <div class="col-md-6 text-end">
        <img src="<?php echo $product['gambar'];?>" alt="" style="width: 450px; height: 450px; object-fit: cover;">
      </div>
      <div class="col-md-6">
        <h1><?php echo $product["nama"]; ?></h1>
        <p><?php echo $product["desc_produk"]; ?></p>
        <h5><?php echo $fmt->format($product["harga"]); ?></h5>
        <?php if(!isset($_SESSION['user'])): ?>
          <h5><a href="login.php" style="color:red " >Login</a> untuk melakukan transaksi!</h5>
            <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
              <?php else: ?>
        <form method="post">
        <button name="add" type="submit" class="btn mt-2 mb-5 btn-primary w-100">Add Cart</button>
        </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
<script src="https://kit.fontawesome.com/47dcae39d3.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="js/script.js"></script>
</html>