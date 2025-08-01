<?php
session_start();
//Deteksi hanya bisa diinclude, tidak bisa langsung dibuka (direct open)
if(count(get_included_files())==1)
{
    echo "<meta http-equiv='refresh' content='0; url=http://$_SERVER[HTTP_HOST]'>";
    exit("Direct access not permitted.");
}
// Warning Error To Login Admin Page
// $error_login = "Maaf, Username & Password Salah! Atau ID Anda Sedang Di Blokir Oleh Admin.";

// View Error Message To Browser
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Error</title>
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- Font Awesome -->
     <link href="plugin/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css-landing/bootstrap.min.css">
    <link rel="stylesheet" href="dist/css-landing/icomoon.css">
    <link rel="stylesheet" href="dist/css-landing/style.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
</head>
<body>
<br><br><br>
    <center>
      <div class="col-md-6">
      <div class="alert alert-danger alert-error">
        <?php 

         $rb = mysqli_fetch_array(mysqli_query($koneksi,"SELECT batas_log FROM user WHERE username = '$username'"));

          //echo "SELECT batas_log FROM user WHERE username = '$username'";
          $batas_log = $rb['batas_log']+1;
          
          if($batas_log <= 3) {
          
           ?>
             <a href="#" class="close" data-dismiss="alert">&times;</a>
                <strong>Error!</strong> Maaf, Username atau Password Salah!
                <p><a href="beranda.html#login" class="btn btn-sm btn-danger rounded"><i class="fas fa-info-circle fa-1x"></i> Silahkan Login Ulang</a>
           <?php 
          }
          else {
            
            ?>
             <a href="#" class="close" data-dismiss="alert">&times;</a>
                <strong>Error!</strong> Maaf, Username Sudah di Blokir
                <p><a href="beranda.html#login" class="btn btn-sm btn-danger rounded"><i class="fas fa-info-circle fa-1x"></i> Hubungi Admin..!!</a>
           <?php 

          }

         ?>
      
      </div>
    </div>
    </center>
</body>
</html>