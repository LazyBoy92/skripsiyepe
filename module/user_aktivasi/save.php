<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../index.php');
}

else { 
  

  if (isset($_POST['proses'])) {

    $id_user = $_POST['id_user'];

    $aksi = mysqli_query($koneksi,"UPDATE user SET blokir = 'N' WHERE id_user = '$id_user'");
    //echo "UPDATE user SET password = '$new_pas' WHERE id_user = '$id_user'";
    
    if($aksi) {
      save_alert('save','Aktifasi User Berhasil');
      htmlRedirect('media.php?module='.$module);
    }

    else {
      save_alert('error','Aktifasi User Gagal');
      htmlRedirect('media.php?module='.$module);
    }     

  }


}



 ?>