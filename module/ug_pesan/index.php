<script>
function confirmdelete(delUrl) {
if (confirm("Anda yakin ingin menghapus?")) {
document.location = delUrl;
}
}
</script>
<?php 
 ?>
<?php
//Deteksi hanya bisa diinclude, tidak bisa langsung dibuka (direct open)
if(count(get_included_files())==1){
  echo "<meta http-equiv='refresh' content='0; url=http://$_SERVER[HTTP_HOST]'>";
  exit("Direct access not permitted.");
  }
error_reporting(0);
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../error_login.php');
  }
else{
  switch($_GET['act']){
    default:
        if ($_SESSION['leveluser']=='user_guru' OR $_SESSION['leveluser']=='user_siswa'){


?>
<!-- 404 Error Text -->
          <div class="text-center">
            <div class="error mx-auto" data-text="404">404</div>
            <p class="lead text-gray-800 mb-5">NO DIRECT</p>
          </div>


<?php 
    }
break;
case 'kirim_pesan':
include 'kirim_pesan.php';
break;

case 'pesan_keluar':
include 'pesan_keluar.php';
break;

case 'pesan_masuk':
include 'pesan_masuk.php';
break;

case 'lihat_pesan':
include 'lihat_pesan.php';
break;

case 'balas_pesan':
include 'balas_pesan.php';
break;

case 'save':
include 'save.php';
break;
  }
} 
?>
