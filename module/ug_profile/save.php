<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../index.php');
}
 else { 
  

if (isset($_POST['update_guru'])) {

  $lokasi_file = $_FILES['fupload']['tmp_name'];
  $nama_file   = $_FILES['fupload']['name'];
  $tipe_file   = $_FILES['fupload']['type'];
  

  $extensionList = array("jpg", "jpeg", "png");
  $pecah = explode(".", $nama_file);
  $ekstensi = $pecah[1];

  if(!empty($nama_file)) {
    if (!in_array($ekstensi, $extensionList)){
                 
        save_alert('error','Type File tidak di izinkan');
        htmlRedirect('media.php?module='.$module);
          }
    else{ 
          UploadImage_pengajar($nama_file);
          mysqli_query($koneksi,"UPDATE guru SET nip = '$_POST[nip]', nama_lengkap  = '$_POST[nama_lengkap]', jabatan  = '$_POST[jabatan]', alamat  = '$_POST[alamat]', tempat_lahir  = '$_POST[tempat_lahir]', tgl_lahir  = '$_POST[tgl_lahir]', jenis_kelamin  = '$_POST[jenis_kelamin]', agama  = '$_POST[agama]', email  = '$_POST[email]', no_telp  = '$_POST[no_telp]', foto = '$nama_file' WHERE id = '$_POST[id]'");
          save_alert('save','Data di Update');
          htmlRedirect('media.php?module='.$module);
        }
    }
  else{
      mysqli_query($koneksi,"UPDATE guru SET nip = '$_POST[nip]', nama_lengkap  = '$_POST[nama_lengkap]', jabatan  = '$_POST[jabatan]', alamat  = '$_POST[alamat]', tempat_lahir  = '$_POST[tempat_lahir]', tgl_lahir  = '$_POST[tgl_lahir]', jenis_kelamin  = '$_POST[jenis_kelamin]', agama  = '$_POST[agama]', email  = '$_POST[email]', no_telp  = '$_POST[no_telp]' WHERE id = '$_POST[id]'");
      save_alert('save','Update Sukses');
      htmlRedirect('media.php?module='.$module);
    }
  }

  

  elseif (isset($_POST['update_siswa'])) {

//   function antiinjection($data){
//   global $koneksi;
//   $filter = mysqli_real_escape_string($koneksi, stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
//   return $filter;
// }

  $nama_lengkap = filter($_POST['nama_lengkap']);
  $alamat       = filter($_POST['alamat']);
  $tempat_lahir = filter($_POST['tempat_lahir']);
  $agama        = filter($_POST['agama']);
  $no_telp      = filter($_POST['no_telp']);
  $id           = filter($_POST['id']);

  $lokasi_file = $_FILES['fupload']['tmp_name'];
  $nama_file   = $_FILES['fupload']['name'];
  $tipe_file   = $_FILES['fupload']['type'];
  

  $extensionList = array("jpg", "jpeg", "png");
  $pecah = explode(".", $nama_file);
  $ekstensi = $pecah[1];

      if(!empty($nama_file)) {
        if (!in_array($ekstensi, $extensionList)){
                     
            save_alert('error','Type File tidak di izinkan');
            htmlRedirect('media.php?module='.$module);
              }
        else{ 
              
              $query_update = mysqli_query($koneksi,"UPDATE siswa SET  nama_lengkap  = '$nama_lengkap',  alamat  = '$alamat', tempat_lahir  = '$tempat_lahir', tgl_lahir  = '$_POST[tgl_lahir]', jenis_kelamin  = '$_POST[jenis_kelamin]', agama  = '$agama', email  = '$email', no_telp  = '$no_telp', foto = '$nama_file' WHERE id = '$id'");
              if($query_update){
                UploadImage_siswa($nama_file);
                save_alert('save','Data di Update');
                htmlRedirect('media.php?module='.$module);
              }
              else {
                save_alert('error','Terjadi Kesalahan');
                htmlRedirect('media.php?module='.$module);
              }
              
            }
        }
      else{
         
         $query_update2= mysqli_query($koneksi,"UPDATE siswa SET  nama_lengkap  = '$_POST[nama_lengkap]',  alamat  = '$_POST[alamat]', tempat_lahir  = '$_POST[tempat_lahir]', tgl_lahir  = '$_POST[tgl_lahir]', jenis_kelamin  = '$_POST[jenis_kelamin]', agama  = '$_POST[agama]', email  = '$_POST[email]', no_telp  = '$_POST[no_telp]' WHERE id = '$_POST[id]'");
         if($query_update2) {
          save_alert('save','Update Sukses');
          htmlRedirect('media.php?module='.$module);
         }
         else {
          save_alert('error','Terjadi Kesalahan');
          htmlRedirect('media.php?module='.$module);
         }
          
        }
   

  }






}




 ?>