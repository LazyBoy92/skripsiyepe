<script>
function confirmdelete(delUrl) {
if (confirm("Anda yakin ingin menghapus?")) {
document.location = delUrl;
}
}
</script>
<?php 
//DEKLARASI FUNGSI
function fsize($file){
  $a = array("B", "KB", "MB", "GB", "TB", "PB");
  $pos = 0;
  $size = filesize($file);
  while ($size >= 1024) {
    $size /= 1024;
    $pos++;
  }
  return round ($size,2)." ".$a[$pos];
}
 ?>
<?php
//Deteksi hanya bisa diinclude, tidak bisa langsung dibuka (direct open)
if(count(get_included_files())==1){
  //echo "<meta http-equiv='refresh' content='0; url=http://$_SERVER[HTTP_HOST]'>";
  //exit("Direct access not permitted.");
  }
error_reporting(0);
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../error_login.php');
  }
else{
  if ($_SESSION['leveluser']=='user_guru' OR $_SESSION['leveluser']=='user_siswa'){

      include "drzchat.php";
  }
}

?>
