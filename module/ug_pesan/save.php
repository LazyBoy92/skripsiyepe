<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../index.php');
}
  else { 
    
    if (isset($_POST['kirim_pesan'])) {

      $ke     = $_POST['ke'];
      $jum_ke = count($ke);
      $x = 0;

      for ($x=0; $x <$jum_ke ; $x++) { 
         
         $input = "INSERT INTO kirim_pesan(dari,ke,judul_pesan,isi_pesan,tgl_kirim,jam_kirim,dibaca) VALUES('$_POST[dari]','$ke[$x]','$_POST[judul_pesan]','$_POST[isi_pesan]','$tgl_sekarang','$jam_sekarang','N')";
         mysqli_query($koneksi,$input);
        }
      save_alert('save','Pesan Telah di Kirim');
      htmlRedirect('media.php?module='.$module.'&act=pesan_keluar');
      }

    if (isset($_POST['kirim_pesan_siswa'])) {

      $ke     = $_POST['ke'];
      $input = "INSERT INTO kirim_pesan(dari, ke, judul_pesan, isi_pesan, tgl_kirim, jam_kirim, dibaca) VALUES('$_POST[dari]','$ke','$_POST[judul_pesan]','$_POST[isi_pesan]','$tgl_sekarang','$jam_sekarang','N')";
      
      mysqli_query($koneksi,$input);
      
      save_alert('save','Pesan Telah di Kirim');
      htmlRedirect('media.php?module='.$module.'&act=pesan_keluar');
      }


    elseif (isset($_POST['balas_pesan'])) {

      $ke     = $_POST['ke'];
      $dari   = $_POST['dari'];
      
      $input = "INSERT INTO balas_pesan(id_kirim, dari, ke, judul_balas, isi_balas, tgl_balas, jam_balas, dibaca) VALUES('$_POST[id_kirim]','$dari','$ke', '$_POST[judul_balas]','$_POST[isi_balas]','$tgl_sekarang','$jam_sekarang','N')";
      
      //echo $input;
      mysqli_query($koneksi,$input);
        
      save_alert('save','Pesan Telah di Kirim');
      htmlRedirect('media.php?module='.$module.'&act=pesan_keluar');
      }


elseif ($_GET['post']=='hapus') {

    $file_lama=mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_video FROM file_video WHERE id_video='$_GET[id_video]'"));
    $exist_file = 'module/files_video/'.$file_lama['nama_video'];
    unlink($exist_file);

    mysqli_query($koneksi,"DELETE FROM file_video WHERE id_video='$_GET[id_video]'");
    save_alert('save','Video di hapus');
    htmlRedirect('media.php?module='.$module);
    }


}

 ?>