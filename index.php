<?php
session_start();
require 'koneksi.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
if ($search) {
  $sql = "SELECT * from produk where nama like :search";
  $stmt = $db->prepare($sql);
  $stmt->execute(['search' => "%$search%"]);
  $products = $stmt->fetchAll();
} else {
  $limit = 9;  // Jumlah item per halaman
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $start = ($page > 1) ? ($page * $limit) - $limit : 0;

  $total = $db->query("SELECT COUNT(*) FROM produk")->fetchColumn();
  $pages = ceil($total / $limit);

  $stmt = $db->prepare("SELECT * FROM produk ORDER BY updated_at DESC LIMIT :start, :limit  ");
  $stmt->bindValue(':start', $start, PDO::PARAM_INT);
  $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
  $stmt->execute();
  $products = $stmt->fetchAll();
}

$total_product = 0;  
$total_product += isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
$fmt = new NumberFormatter($locale = 'id_ID', NumberFormatter::CURRENCY);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home | Toko Roti Alta Bakery</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="css/style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar fixed-top navbar-expand-lg shadow-sm" style="background-color: palegoldenrod;">
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
            <a class="nav-link fw-medium" href="cart.php"><i class="fa-solid fa-cart-shopping"></i> Cart <span class="badge rounded-circle text-bg-danger"><?= $total_product; ?></span></a>
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
  
  <!-- Slideshow  -->
  <div class="container">
    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active" data-bs-interval="10000">
          <img src="img/logo.png" class="d-block w-100 slider" alt="...">
        </div>
        <div class="carousel-item" data-bs-interval="2000">
          <img src="img/logo.png" class="d-block w-100 slider" alt="...">
        </div>
        <div class="carousel-item">
          <img src="img/logo.png" class="d-block w-100 slider" alt="...">
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div> 
  </div>

  <!-- Search Bar -->
  <div class="d-flex justify-content-center align-items-center"> 
    <form action= "" method="GET">
    <div class="input-group mb-4 mt-5">
      <input type="text" class="form-control" placeholder="Search" aria-describedby="button-addon2" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : '' ?>">
      <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
    </div>
    </form>
  </div>
  <!-- Product -->
  <div class="container">
    <div class="row d-flex justify-content-start align-items-center">
    <?php
      foreach ($products as $row):  ?>
      <div class="col-6 col-md-4 mt-3">
        <div class="card">
          <img src="<?php echo $row['gambar']?>" class="card-img-top" style="height: 300px; object-fit: cover;" alt="...">
          <div class="card-body">
            <h5 class="card-title"><?php echo $row['nama'];?></h5>
            <p class="card-text"><?php echo $fmt->format($row['harga']);?></p>
            <a href="detail.php?id=<?= $row['id_produk'] ?>" class="btn btn-primary">Show Product</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <!-- Pagination -->
    <?php if($search): ?>
    <?php else: ?>
    <nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center mt-3">
    <?php if($page > 1): ?>
    <li class="page-item">
 
    <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
        </a>
      </li>
      <?php endif; ?>
    <?php for ($i = 1; $i <= $pages; $i++): ?>
      <li class="page-item <?= $page == $i ? 'active' : '' ?> "><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
      <?php endfor; ?>
      <?php if($page < $pages): ?>
        <li class="page-item">
        <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
        <?php endif; ?>
    </ul>
    </nav>
    <?php endif; ?>
  </div>

  <!-- Footer -->
  <div class="container">
  <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
    <div class="col-md-4 d-flex align-items-center">
      <a href="/" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1">
        <img class="me-2" src="img/logo.png" width="50">
      </a>
      <span class="mb-3 mb-md-0 fw-medium">© 2024 Alta Bakery, Inc</span>
    </div>

    <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
      <li class="ms-3"><a class="text-body-secondary" href="#"><i class="fa-brands fa-twitter fa-2xl"></i></a></li>
      <li class="ms-3"><a class="text-body-secondary" href="#"><i class="fa-brands fa-instagram fa-2xl"></i></a></li>
      <li class="ms-3"><a class="text-body-secondary" href="#"><i class="fa-brands fa-facebook fa-2xl"></i></use></svg></a></li>
    </ul>
  </footer>
  </div>
</body>
<script src="https://kit.fontawesome.com/47dcae39d3.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="js/script.js"></script>
</html>