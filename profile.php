<?php
session_start();
require('koneksi.php');
if (isset($_SESSION['user'])) {
  if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header('Location: index.php');
  }
  else {
  $user_id = $_SESSION['user_id'];
  $sql = "SELECT * from user where user_id = $user_id";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  }
} else {
  header('Location: login.php');
}

//show data order
$sqlorder = "SELECT * from order_produk where order_name = :name ORDER BY order_date DESC";
$name = $_SESSION['user_name'];
$stmt = $db->prepare($sqlorder);
$params = array(
  ":name" => $name,
);
$stmt->execute($params);
$order = $stmt->fetchAll();

//change data user
if (isset($_POST['change_data'])) {
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
  $user_id = $_SESSION['user_id'];
  $sql = "UPDATE user set `name` = :nama, `tanggal_lahir` = :date, `updated_at` = now() where user_id = $user_id";
  $stmt = $db->prepare($sql);
  $params = array(
    ":nama" => $name,
    ":date" => $date
);
  $stmt->execute($params);
  $sqlorder = "SELECT * from order_produk where order_name = :name ORDER BY order_date DESC";
$name = $_SESSION['user_name'];
$stmt = $db->prepare($sqlorder);
$params = array(
  ":name" => $name,
);
$stmt->execute($params);
$order = $stmt->fetchAll();
  header("Refresh: 0");
}

if(isset($_POST['change_password'])) {
  if(strlen($_POST['password1']) <8) {
    $error = "Password kurang dari 8";
    echo $error;
  } else {
    if($_POST['password1'] === $_POST['password2']) {
    $password1 = password_hash($_POST["password1"], PASSWORD_DEFAULT);
    $password2 = password_hash($_POST["password2"], PASSWORD_DEFAULT);
    $user_id = $_SESSION['user_id'];
      $sql = "UPDATE user set `password` = :password, `updated_at` = now() where user_id = $user_id";
      $stmt = $db->prepare($sql);
      $params = array(
        ":password" => $password1
    );
      $stmt->execute($params);
    } else {
      $error = "Password tidak cocok";
      echo $error;
      echo $password1, $password2;
    }
  }
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
  <title>Profile | Toko Roti Alta Bakery</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="css/style.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js" integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg shadow-sm mb-5" style="background-color: palegoldenrod;">
    <div class="container">
      <a class="navbar-brand fw-bold" style="color: #C15440;" href="index.php">
        <img src="img/logo.png"  alt="Logo" width="50" class="d-inline-block align-text-center me-2">
        ALTA BAKERY
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link fw-medium" href="cart.php"><i class="fa-solid fa-cart-shopping"></i> Cart <span class="badge rounded-circle text-bg-danger"><?= $total_product ?></span></a>
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

  <!-- Box -->
  <div class="container">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex align-items-start">
          <div class="nav flex-column nav-underline me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a class="nav-link active" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="true">Profile</a>
            <a class="nav-link" id="v-pills-transaction-tab" data-bs-toggle="pill" data-bs-target="#v-pills-transaction" type="button" role="tab" aria-controls="v-pills-transaction" aria-selected="false">Transaction</a>
            <a class="nav-link" id="v-pills-password-tab" data-bs-toggle="pill" data-bs-target="#v-pills-password" type="button" role="tab" aria-controls="v-pills-password" aria-selected="false">Change Password</a>
          </div>
          <div class="tab-content w-100" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">
                <form action="" method="POST">
                  <fieldset disabled>
                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username" value="<?php echo $user['username'] ?>">
                  </div>
                  </fieldset>
                  <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="<?php echo $user['name'] ?>" required>
                  </div>
                  <fieldset disabled>
                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value=" <?php echo $user['email'] ?>" >
                  </div>
                  </fieldset>
                  <div class="mb-3 form-group">
                    <label for="date" class="form-label">Date of Birth</label>
                    <div class="input-group date" id="datepicker">
                      <span class="input-group-append">
                        <span class="input-group-text bg-white d-block">
                            <i class="fa fa-calendar"></i>
                        </span>
                      </span>
                      <input type="text" class="form-control" name="date" id="date" value = "<?php echo $user['tanggal_lahir'] ?>" readonly>
                    </div>          
                  </div>
                  <button type="submit" class="btn btn-primary mb-3" name="change_data" id="change_data">Save</button>
                </form>  
            </div>
            <div class="tab-pane fade" id="v-pills-transaction" role="tabpanel" aria-labelledby="v-pills-transaction-tab" tabindex="0">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Order Id</th>
                    <th scope="col">Order Time</th>
                    <th scope="col">Total</th>
                  </tr> 
                </thead>
                <tbody>
                <?php
                             $no = 0 + 1;
                            foreach ($order as $row): ?>
                  <tr>
                    <th scope="row"><?= $no?></th>
                    <td><?= $row['order_id']; ?></td>
                                <td><?= $row['order_date'] ?></td>
                                <td><?= $fmt->format($row['order_total']); ?></td>
                            </tr>
                            <?php $no++; endforeach; ?>
                </tbody>
              </table>
            </div>
            <div class="tab-pane fade" id="v-pills-password" role="tabpanel" aria-labelledby="v-pills-password-tab" tabindex="0">
              <form action="" method="POST">
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password1" id="password1" required />
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="password2" id="password2" required />
              </div>
              <button name="change_password" id="change_password" type="submit" class="btn btn-primary mb-3">Confirm</button>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
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

  <script>
    $(function() {
      $('#datepicker').datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true,
        orientation: "top left"
      });
    });
  </script>
</body>
<script src="https://kit.fontawesome.com/47dcae39d3.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="js/script.js"></script>
</html>