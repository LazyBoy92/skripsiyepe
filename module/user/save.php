<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../index.php');
}

else { 
  

  if (isset($_POST['reset'])) {

    $id_user = $_POST['id_user'];
    $new_pas = $_POST['password'];

    $aksi = mysqli_query($koneksi,"UPDATE user SET password = '$new_pas' WHERE id_user = '$id_user'");
    //echo "UPDATE user SET password = '$new_pas' WHERE id_user = '$id_user'";
    
    if($aksi) {
      save_alert('save','Reset Password Berhasil');
      htmlRedirect('media.php?module='.$module.'&act=edit_data&id='.$id_user);
    }

    else {
      save_alert('error','Gagal Reset Password');
      htmlRedirect('media.php?module='.$module.'&act=edit_data&id='.$id_user);
    }     

  }

  elseif (isset($_POST['proses_blokir'])) {

    $id_user = $_POST['id_user'];
    $blokir  = $_POST['blokir'];

    $aksi = mysqli_query($koneksi,"UPDATE user SET blokir = '$blokir' WHERE id_user = '$id_user'");
    //echo "UPDATE user SET blokir = '$blokir' WHERE id_user = '$id_user'";
    
    if($aksi) {
      save_alert('save','Pemblokiran User Berhasil');
      htmlRedirect('media.php?module='.$module.'&act=edit_data&id='.$id_user);
    }

    else {
      save_alert('error','Proses Gagal');
      htmlRedirect('media.php?module='.$module.'&act=edit_data&id='.$id_user);
    }     
  }



}



 ?>