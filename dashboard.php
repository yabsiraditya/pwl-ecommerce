<?php
session_start();
require 'koneksi.php';

//cek user admin login
if (isset($_SESSION['user'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    } else {
        header('Location: index.php'); // Redirect to the index page or any other page
        exit();
    }
} else {
    echo 'HTTP/1.1 401 Unauthorized';
    // header('Location: login.php');
    exit();
}


//pagination
$limit = 5;  // Jumlah item per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;
$total = $db->query("SELECT COUNT(*) FROM produk")->fetchColumn();
$pages = ceil($total / $limit);

//show data product
$stmt = $db->prepare("SELECT * FROM produk ORDER BY updated_at DESC  LIMIT :start, :limit  ");
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();


//show data customer
$sqlcust = "SELECT * from user ORDER BY user_id ASC ";
$stmt = $db->prepare($sqlcust);
$stmt->execute();
$user = $stmt->fetchAll();

//show data order
$sqlorder = "SELECT * from order_produk  ORDER BY order_date DESC";
$stmt = $db->prepare($sqlorder);
$stmt->execute();
$order = $stmt->fetchAll();

//change password admin
if(isset($_POST["change_password"])) {
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
      session_destroy();
      header('Location: login.php');
    } else {
      $error = "Password tidak cocok";
    }
  }
}


//submit data baru
if(isset($_POST['formsubmit'])) {
  // Ambil data dari formulir
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  $desc = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING);
  $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_STRING);
  $randangka = rand(100,999);
  $id_produk = "ALB" . $randangka;
  $target_dir = "img/";
  $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  $check = getimagesize($_FILES["gambar"]["tmp_name"]);
  if($check !== false) {
      $uploadOk = 1;
  } else {
      echo "File ini bukan gambar!";
      $uploadOk = 0;
  }

  // Check file size
  if ($_FILES["gambar"]["size"] > 500000) {
      echo "File anda terlalu besar!";
      $uploadOk = 0;
  }

  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
      echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  } else {
      if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
        $gambarBase64 = base64_encode(file_get_contents($target_file));
        $gambarData = 'data:' . mime_content_type($target_file) . ';charset=utf-8' . ';base64,' . $gambarBase64;
          $sql = ("INSERT INTO produk (id_produk, nama, desc_produk, harga, gambar, created_at) VALUES (:id_produk, :nama, :deskripsi, :harga, :gambar, now())");
          $stmt = $db->prepare($sql);
          $stmt->bindParam(':id_produk', $id_produk);
          $stmt->bindParam(':nama', $name);
          $stmt->bindParam(':deskripsi', $desc);
          $stmt->bindParam(':harga', $price);
          $stmt->bindParam(':gambar', $gambarData);

          if ($stmt->execute()) {
              $msg = "Data berhasil disimpan.";
              header("Refresh: 0.1");
          } else {
              $msg ="Terjadi kesalahan saat menyimpan data.";
          }
      } else {
          $msg = "Terjadi kesalahan saat menyimpan data";
      }
  }
}

//submit  edit
if(isset($_POST['formedit'])) {
    $id_produk  = filter_input(INPUT_POST, 'id_produk', FILTER_SANITIZE_STRING);
    $name = filter_input(INPUT_POST, 'nameedit', FILTER_SANITIZE_STRING);
    $desc = filter_input(INPUT_POST, 'descedit', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'priceedit', FILTER_SANITIZE_STRING);

    $target_dir = "img/";
    $target_file = $target_dir . basename($_FILES["gambaredit"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = filesize($_FILES["gambaredit"]["tmp_name"]);
    if($check) {
        $check = getimagesize($_FILES["gambaredit"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
      
        // Check file size
        if ($_FILES["gambaredit"]["size"] > 500000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
      
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
    
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["gambaredit"]["tmp_name"], $target_file)) {
              $gambarBase64 = base64_encode(file_get_contents($target_file));
              $gambarData = 'data:' . mime_content_type($target_file) . ';charset=utf-8' . ';base64,' . $gambarBase64;
                $sql = ("UPDATE produk set nama = :nama, desc_produk = :deskripsi, harga = :harga, gambar = :gambar, updated_at = now() where id_produk = :id") ;
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id',$id_produk);
                $stmt->bindParam(':nama', $name);
                $stmt->bindParam(':deskripsi', $desc);
                $stmt->bindParam(':harga', $price);
                $stmt->bindParam(':gambar', $gambarData);
                if ($stmt->execute()) {
                    $msg= "Data berhasil disimpan.";
                    header("Refresh: 0.1");
                } else {
                    $msg ="Terjadi kesalahan saat menyimpan data.";
                }
            } else {
                $msg = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
                $sql = ("UPDATE produk set `nama` = :nama, `desc_produk`= :deskripsi, `harga` = :harga, updated_at = now() where `id_produk` = :id");
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id',$id_produk);
                $stmt->bindParam(':nama', $name);
                $stmt->bindParam(':deskripsi', $desc);
                $stmt->bindParam(':harga', $price);
                if ($stmt->execute()) {
                    $msg = "Data berhasil disimpan.";
                    header("Refresh: 0.1");
                } else {
                    $msg ="Terjadi kesalahan saat menyimpan data.";
                }
    }
    
}
//submit delete
if(isset($_POST['submitdelete']) ) {
    $id_produk  = filter_input(INPUT_POST, 'id_produk', FILTER_SANITIZE_STRING);
    $sql = ("DELETE from produk where id_produk = :id");
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id',$id_produk);
    $stmt->execute();
    header("Refresh: 0.1");
}
$fmt = new NumberFormatter($locale = 'id_ID', NumberFormatter::CURRENCY);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Alta Bakery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar" class="js-sidebar">
            <div class="h-100">
                <div class="sidebar-logo">
                    <h1 href="index.php" class="mb-4">Alta Bakery</h1>
                </div>
                <ul class="sidebar-nav">
                    <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                      <a class="nav-link active" id="v-pills-dashboard-tab" data-bs-toggle="pill" href="#home" data-bs-target="#v-pills-dashboard" type="button" role="tab" aria-controls="v-pills-dashboard" aria-selected="true">Dashboard</a>
                      <a class="nav-link" id="v-pills-product-tab" data-bs-toggle="pill" data-bs-target="#v-pills-product" href="#products" type="button" role="tab" aria-controls="v-pills-product" aria-selected="false">Products</a>
                      <a class="nav-link" id="v-pills-order-tab" data-bs-toggle="pill" data-bs-target="#v-pills-order" type="button" role="tab" aria-controls="v-pills-order" aria-selected="false">Orders</a>
                      <a class="nav-link" id="v-pills-customer-tab" data-bs-toggle="pill" data-bs-target="#v-pills-customer" type="button" role="tab" aria-controls="v-pills-customer" aria-selected="false">Customers</a>
                      <hr>
                      <a class="nav-link" id="v-pills-setting-tab" data-bs-toggle="pill" data-bs-target="#v-pills-setting" type="button" role="tab" aria-controls="v-pills-setting" aria-selected="false">Settings</a>
                      <a class="nav-link" href="index.php" type="button" aria-selected="false">Home</a>
                    </div>
                </ul>
            </div>
        </aside>
        <div class="main">
            <nav class="navbar navbar-expand px-3 border-bottom">
                <button class="btn" id="sidebar-toggle" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </nav>
            <main class="content px-3 py-2">    
            <div class="container-fluid">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane show active" id="v-pills-dashboard" role="tabpanel" aria-labelledby="v-pills-dashboard-tab" tabindex="0">
                        <div class="mb-3">
                            <h4>Dashboard</h4>
                        </div>
                            <h6>Welcome Back, Admin</h6>
                    </div>
                    <div class="tab-pane  " id="v-pills-product" role="tabpanel" aria-labelledby="v-pills-product-tab" tabindex="0" >
                        <div class="mb-3">
                            <h4>Product</h4>
                        </div>
                        <!-- Add Product -->
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProduct">
                            + Add Product
                        </button>                    
                        <div class="modal fade" id="addProduct" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h1 class="modal-title fs-5" id="addProductLabel">Add Product</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div> 
                                <form action="" method="POST" enctype="multipart/form-data">
                                <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label">Image Product</label>
                                            <input class="form-control" type="file" id="formFile" name="gambar" required>
                                          </div>
                                          <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                          </div>
                                          <div class="mb-3">
                                            <label for="desc" class="form-label">Description</label>
                                            <input type="text" class="form-control" id="desc" name="desc" required>
                                          </div>
                                          <div class="mb-3">
                                            <label for="price" class="form-label">Price</label>
                                            <input type="text" class="form-control" id="price" name="price" required>
                                          </div>
                                        </div>
                                <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="formsubmit" class="btn btn-primary" id="formsubmit">Save</button>
                                </div>
                            
                            </div>
                                    </form> 
                            </div>
                        </div>
                        <table class="table">
                            <thead>
                              <tr>
                                <th scope="col">No</th>
                                <th scope="col">Product Code</th>
                                <th scope="col">Picture</th>
                                <th scope="col">Name</th>
                                <th scope="col">Description</th>
                                <th scope="col">Price</th>
                                <th scope="col">Action</th>
                              </tr> 
                            </thead>
                            <tbody>
                            <?php 
                             $no = $start + 1;
                             foreach ($products as $row):  ?>
                            <tr>
                            <td><?= $no ?></td>
                                <th scope="row"><?php echo $row['id_produk']; ?></th>
                                <td><img src="<?php echo $row['gambar'];?>"style="width: 80px; height: 80px; object-fit: cover" alt=""></td>
                                <td><?php echo $row['nama']; ?></td>
                                <td>
                                    <span class="description text-truncate"><?php echo $row['desc_produk']; ?></span>
                                </td>
                                <td><?php echo $fmt->format($row['harga']); ?></td>
                                <td>
                                    <!-- Edit Product -->
                                    <button type="button" class="btn btn-warning text-black" data-bs-toggle="modal" data-bs-target="#editProduct<?php echo $row['id_produk'] ?>"><i class="fa-solid fa-pen"></i></button>
                                    <div class="modal fade" id="editProduct<?php echo $row['id_produk'] ?>" tabindex="-1" aria-labelledby="editProductLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="editProductLabel">Edit Product</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="" method="POST"  enctype="multipart/form-data" id="edit" name="edit">
                                            <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="id_produk" class="form-label">ID Produk</label>
                                                        <input type="text" class="form-control" id="id_produk" name="id_produk" value="<?php echo $row['id_produk']; ?>" required readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="formFile" class="form-label">Image Product</label>
                                                        <input class="form-control" type="file" id="formFile" name="gambaredit" value="<?php echo $row['gambar'];?>">
                                                      </div>
                                                      <div class="mb-3">
                                                        <label for="name" class="form-label">Name</label>
                                                        <input type="text" class="form-control" id="name" name="nameedit" value="<?php echo $row['nama']; ?>" required>
                                                      </div>
                                                      <div class="mb-3">
                                                        <label for="description" class="form-label">Description</label>
                                                        <input type="text" class="form-control" id="description" name="descedit" value="<?php echo $row['desc_produk']; ?>" required>
                                                      </div>
                                                      <div class="mb-3">
                                                        <label for="price" class="form-label">Price</label>
                                                        <input type="text" class="form-control" id="price" name="priceedit" value="<?php echo  $row['harga']; ?>"  required>
                                                      </div>
                                            </div>
                                            <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" id="formedit" name="formedit" class="btn btn-primary">Save</button>
                                            </div>
                                            </form>
                                        </div>
                                </div>  
                                    </div>

                                    </div>
                                    <!-- Delete Product -->
                                    <button type="button" class="btn btn-danger text-black" data-bs-toggle="modal" data-bs-target="#deleteProduct<?php echo $row['id_produk'] ?>"><i class="fa-solid fa-trash"></i></button>
                                    <div class="modal fade" id="deleteProduct<?php echo $row['id_produk'] ?>" tabindex="-1" aria-labelledby="deleteProductLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="deleteProductLabel">Delete Product</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h6>Are you sure want to delete this product?</h6>
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                            <form action= "dashboard.php" method="POST">
                                            <input type="text" class="form-control" style="display: none" id="id_produk" name="id_produk" value="<?php echo $row['id_produk'] ?>" required readonly>
                                            <button type="submit" class="btn btn-primary" id="submitdelete" name="submitdelete">Delete</button>
                                            </form>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </td>
                             
                              </tr>
                              <?php
      $no++;
    endforeach;
            ?>
                            </tbody>
                        </table>
                            <!-- Pagination -->
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
                    </div>
                    <div class="tab-pane fade" id="v-pills-order" role="tabpanel" aria-labelledby="v-pills-order-tab" tabindex="0">
                        <div class="mb-3">
                            <h4>Orders</h4>
                        </div>
                        <table class="table">
                            <thead>
                              <tr>
                                <th scope="col">No</th>
                                <th scope="col">Order Id</th>
                                <th scope="col">Order Time</th>
                                <th scope="col">Name</th>
                                <th scope="col">Total</th>
                              </tr> 
                            </thead>
                            <tbody>
                            <?php
                             $no = 0 + 1;
                            foreach ($order as $row): ?>
                            <tr>
                                <th scope="row"><?= $no ?></th>
                                <td><?= $row['order_id']; ?></td>
                                <td><?= $row['order_date'] ?></td>
                                <td><?= $row['order_name'] ?></td>
                                <td><?= $fmt->format($row['order_total']); ?></td>
                            </tr>
                            <?php $no++; endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="v-pills-customer" role="tabpanel" aria-labelledby="v-pills-customer-tab" tabindex="0">
                        <div class="mb-3">
                            <h4>Customers</h4>
                        </div>
                        <table class="table">
                            <thead>
        
                              <tr>
                                <th scope="col">No</th>
                                <th scope="col">USER ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Username</th>
                                <th scope="col">Email</th>
                                <th scope="col">Date Of Birth</th>
                                <th scope="col">Last Login</th>
                              </tr> 
                            </thead>
                            <tbody>
                            <?php
                             $no = 0 + 1;
                            foreach ($user as $row): ?>
                            <tr>
                                <th scope="row"><?=  $no ?></th>
                                <td><?php echo $row['user_id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['tanggal_lahir']; ?></td>
                                <td><?php echo $row['last_login']; ?></td>
                            </tr>
                            <?php $no++; endforeach; ?>
                            </tbody>
                        </table>
                   
                    </div>
                    <div class="tab-pane fade" id="v-pills-setting" role="tabpanel" aria-labelledby="v-pills-setting-tab" tabindex="0">
                        <div class="mb-3">
                            <h4>Setting</h4>
                        </div>
                        <div class="container">
                            <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                <div class="nav flex-column nav-underline me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Change Password</a>
                                </div>
                                <div class="tab-content w-100" id="v-pills-tabContent">
                                    <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">
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
                    </div>
                </div>
            </div>
            </main>
        </div>
    </div>
<script>
    const sidebarToggle = document.querySelector("#sidebar-toggle");
    sidebarToggle.addEventListener("click",function(){
        document.querySelector("#sidebar").classList.toggle("collapsed");
    });
</script>
</body>
<script src="https://kit.fontawesome.com/47dcae39d3.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="js/script.js"></script>
</html