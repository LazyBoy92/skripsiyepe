<?php
  include "config/koneksi.php";
  session_start();
  mysqli_query($koneksi,"UPDATE user SET login='0' WHERE username='$_SESSION[namauser]'");
  session_destroy();
  echo "<script>window.alert('Time Out..!!!  Anda Tidak Melakukan Aktifitas Selama 15 Menit'); window.location='beranda.html'</script>";
  die();
		

?>