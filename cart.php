<?php 
session_start();
include 'koneksi.php';

//cek data user login dan cek session cart
if (isset($_SESSION['user'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
        $total = 0;
    }
    $cart = $_SESSION['cart'];
} else {
   header('Location: login.php');
}

if(isset($_POST['checkout'])) {
    // header("Location: index.php");
    if (!isset($_SESSION['cart'])) {
      // $total = 1;
      // echo $total;
      // header("Location: checkout.html");
  } else {
    $randangka = rand(1,9999999);
    $order_id = "TRX" . $randangka;
    $order_name = $_SESSION['user_name'];
    $order_total = $_POST['total'];
    $sql = "INSERT INTO order_produk (order_id, order_name, order_total) 
    VALUES (:id, :name, :total)";
     $stmt = $db->prepare($sql);
     // bind parameter ke query
     $params = array(
        "id" => $order_id,
        "name" => $order_name,
        "total" => $order_total
     );
     $saved = $stmt->execute($params);
     if (!isset($_SESSION['order'])) {
      $_SESSION['order'] = [];
  } else {
    $_SESSION['order'][$id] = [
      'id' => $order_id,
      'name'=> $order_name,
      'total' => $order_total
  ];
  //$cart.unset();
  $_SESSION['cart'] = [];
  }
     
    header("Location: checkout.php");
  }
  // $total = 1;
  // echo $total;
  }

//update quantity
if (isset($_POST['action']) && isset($_POST['id'])) {
        $id = $_POST['id'];
        if (isset($_SESSION['cart'][$id])) {
            if ($_POST['action'] == 'increase') {
                $_SESSION['cart'][$id]['quantity']++;
            } elseif ($_POST['action'] == 'decrease') {
              $_SESSION['cart'][$id]['quantity']--;
              if($_SESSION['cart'][$id]['quantity'] === 0) {
                  unset($_SESSION['cart'][$id]);
              }
          }
        }
    } 
$total = 0;
//total item dalam keranjang
$total_product = 0;  
$total_product += isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
$fmt = new NumberFormatter($locale = 'id_ID', NumberFormatter::CURRENCY);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart | Toko Roti Alta Bakery</title>
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
            <a class="nav-link fw-medium" href="cart.php"><i class="fa-solid fa-cart-shopping"></i> Cart <span class="badge rounded-circle text-bg-danger"><?php echo $total_product; ?></span></a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropbtn fw-medium" href="<?php if(!isset($_SESSION['user'])) {echo 'login.php';} else {echo '#';} ?> ">
            <i class="fa-solid fa-user"></i> Account
            </a>
            <?php if(!isset($_SESSION['user'])): ?>
            <?php else: ?>
            <ul class="dropdown-menu">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
              <?php else: ?>
              <li><a class="dropdown-item fw-medium" href="profile.php">Profile</a></li>
              <?php endif; ?>
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
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-body bg-light rounded-3 p-3">
            <h1>Shopping Cart</h1>
            <hr>
            <?php if(empty($cart)): ?>
                <div class="mt-3 d-flex justify-content-around align-items-center">
                <h5 class="text-truncate" style="width: 250px;">Keranjang Kosong</h5>
                </div>
            <?php else: ?>
            <?php
                 foreach ($cart as $id => $product):
                    ?>
            <div class="mt-3 d-flex justify-content-around align-items-center">
              <img src="<?php   echo $product['gambar']; ?>" style="width: 80px; height: 80px; object-fit: cover" alt="">
              <div class="ms-3 text-center">
                <h5 class="text-truncate" style="width: 250px;"><?php   echo $product['nama']; ?></h5>
                <h6 class="text-truncate" style="width: 250px;"><?php   echo $product['desc_produk']; ?></h6>
              </div>
              <div class="ms-3">
                <h5>Harga</h5>
                <h6><?php   echo $fmt->format($product['harga']); ?></h6>
              </div>
              <div class="ms-3 text-center">
                <h5>Jumlah</h5>
                <div class="d-flex">
                <form method="post" style="display:flex;">
                <input type="hidden" name="id" value="<?= $id ?>">
                  <button type="submit" name="action" class="btn btn-primary" value="decrease" ><i class="fas fa-minus"></i></button>
                  <input id="quantity" name="kuantiti" type="number" class="form-control text-center" style="width: 50px;" value="<?php echo $product['quantity']; ?>" />
                  <button type="submit" name="action"  class="btn btn-primary" value="increase" ><i class="fas fa-plus"></i></button>
                  </form>
                </div>
              </div>
            </div>
            <hr>
            <?php
                 $subtotal = $product['harga'] * $product['quantity'];
                 $total += $subtotal;  endforeach;
                 endif; ?>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body bg-light rounded-3 p-3">
          <form action="" method="POST">
            <h1>Summary</h1>
            <hr>
            <div class="d-flex justify-content-between">
              <h5>Total</h5>
              <h5><?php echo $fmt->format($total); ?></h5>
              <input type="text" style="display: none" name="total" value="<?php echo $total?>">
            </div>
            <button name="checkout" id="checkout" type="submit" class="btn mt-3 mb-2 btn-primary w-100">Checkout</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
<script>
   function increment() {
      document.getElementById('quantity').stepUp(1);
   }
   function decrement() {
      document.getElementById('quantity').stepDown(1);
   }
   
</script>
<script src="https://kit.fontawesome.com/47dcae39d3.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="js/script.js"></script>
</html>