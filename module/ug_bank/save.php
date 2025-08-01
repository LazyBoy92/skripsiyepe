<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../index.php');
}
  else { 
    
    if (isset($_POST['simpan_pilganda'])) {

      $sql_save = "INSERT INTO bank_pilganda(pembuat,id_mapel,pertanyaan,pil_a,pil_b,pil_c,pil_d,pil_e,kunci,tgl_buat) VALUES('$_POST[pembuat]','$_POST[id_mapel]','$_POST[pertanyaan]','$_POST[pil_a]','$_POST[pil_b]','$_POST[pil_c]','$_POST[pil_d]','$_POST[pil_e]','$_POST[kunci]','$tgl_sekarang')";      
      
      if (mysqli_query($koneksi,$sql_save)) {
          
          save_alert('save','Data Soal Tersimpan');
          htmlRedirect('media.php?module='.$module.'&act=pil_ganda',1);
      }
      else {
          save_alert('error','Gagal Tersimpan');
          htmlRedirect('media.php?module='.$module.'&act=pil_ganda',1);
        }
    }


    elseif (isset($_POST['simpan_essay'])) {

      $sql_save = "INSERT INTO bank_esay(pembuat,id_mapel, pertanyaan, tgl_buat) VALUES('$_POST[pembuat]','$_POST[id_mapel]', '$_POST[pertanyaan]', '$tgl_sekarang')";      
      
      if (mysqli_query($koneksi,$sql_save)) {
          
          save_alert('save','Data Soal Tersimpan');
          htmlRedirect('media.php?module='.$module.'&act=esay',1);
      }
      else {
          save_alert('error','Gagal Tersimpan');
          htmlRedirect('media.php?module='.$module.'&act=esay',1);
        }
    }


    elseif (isset($_POST['update_essay'])) {

      $sql_edit = "UPDATE bank_esay SET pertanyaan = '$_POST[pertanyaan]' WHERE id='$_POST[id]'";      
      
      if (mysqli_query($koneksi,$sql_edit)) {
          
          save_alert('save','Data Soal di Update');
          htmlRedirect('media.php?module='.$module.'&act=esay',1);
      }
      else {
          save_alert('error','Gagal Update');
          htmlRedirect('media.php?module='.$module.'&act=esay',1);
        }
    }


    elseif (isset($_POST['edit_pilganda'])) {

      $sql_update = "UPDATE bank_pilganda SET pertanyaan = '$_POST[pertanyaan]', pil_a = '$_POST[pil_a]', pil_b = '$_POST[pil_b]', pil_c = '$_POST[pil_c]', pil_d = '$_POST[pil_d]', pil_e = '$_POST[pil_e]', kunci = '$_POST[kunci]' WHERE id = '$_POST[id]'";      
      
      if (mysqli_query($koneksi,$sql_update)) {
          
          save_alert('save','Data Soal Diupdate');
          htmlRedirect('media.php?module='.$module.'&act=pil_ganda',1);
      }
      else {
          save_alert('error','Gagal Update');
          htmlRedirect('media.php?module='.$module.'&act=pil_ganda',1);
        }
    }


  elseif ($_GET['post']=='hapus') {

    mysqli_query($koneksi,"DELETE FROM bank_pilganda WHERE id='$_GET[id]'");
    save_alert('save','Data di hapus');
    htmlRedirect('media.php?module='.$module.'&act=pil_ganda',1);
  }


  elseif ($_GET['post']=='hapus_esay') {

    mysqli_query($koneksi,"DELETE FROM bank_esay WHERE id='$_GET[id]'");
    save_alert('save','Data di hapus');
    htmlRedirect('media.php?module='.$module.'&act=esay',1);
  }

  
  elseif (isset($_POST['upload'])) {

    $pembuat    = $_POST['pembuat'];
    $id_mapel   = $_POST['id_mapel'];
    $target = basename($_FILES['file']['name']);

    move_uploaded_file($_FILES['file']['tmp_name'], $target);

    // mengambil isi file xls
    $data = new Spreadsheet_Excel_Reader($_FILES['file']['name'],false);

    // menghitung jumlah baris data yang ada
    $jumlah_baris = $data->rowcount($sheet_index=0);
    
    for ($i=2; $i<=$jumlah_baris; $i++){
 
      // menangkap data dan memasukkan ke variabel sesuai dengan kolumnya masing-masing
      $pertanyaan = $data->val($i, 1);
      $pil_a      = $data->val($i, 2);
      $pil_b      = $data->val($i, 3);
      $pil_c      = $data->val($i, 4);
      $pil_d      = $data->val($i, 5);
      $pil_e      = $data->val($i, 6);
      $kunci      = $data->val($i, 7);
     
    
      if($pertanyaan != "" && $pil_a != "" && $kunci != ""){

         $sql_import= "INSERT INTO bank_pilganda(pembuat, id_mapel, pertanyaan, pil_a, pil_b, pil_c, pil_d, pil_e, kunci,tgl_buat) VALUES('$pembuat', '$id_mapel', '$pertanyaan', '$pil_a', '$pil_b', '$pil_c', '$pil_d', '$pil_e', '$kunci', '$tgl_sekarang')";
        
        if(mysqli_query($koneksi,$sql_import)){
          // hapus kembali file .xls yang di upload tadi
          unlink($_FILES['file']['name']);

          save_alert('save','Import Sukses');
          htmlRedirect('media.php?module='.$module.'&act=pil_ganda',1);
        }
        else {
          save_alert('error','Gagal Import');
          htmlRedirect('media.php?module='.$module.'&act=pil_ganda',1);
        }
      }
      else {
        save_alert('error','Gagal Import (Tabel Excel Ada yang kosong)');
        htmlRedirect('media.php?module='.$module.'&act=pil_ganda',1);
      }
    }    
  }


  elseif (isset($_POST['upload_esay'])) {

    $pembuat    = $_POST['pembuat'];
    $id_mapel   = $_POST['id_mapel'];
    $target = basename($_FILES['file']['name']);

    move_uploaded_file($_FILES['file']['tmp_name'], $target);

    // mengambil isi file xls
    $data = new Spreadsheet_Excel_Reader($_FILES['file']['name'],false);

    // menghitung jumlah baris data yang ada
    $jumlah_baris = $data->rowcount($sheet_index=0);
    
    for ($i=2; $i<=$jumlah_baris; $i++){
 
      // menangkap data dan memasukkan ke variabel sesuai dengan kolumnya masing-masing
      $pertanyaan = $data->val($i, 1);
    
      if($pertanyaan != "" ){

         $sql_import= "INSERT INTO bank_esay(pembuat,id_mapel, pertanyaan, tgl_buat) VALUES('$pembuat', '$id_mapel', '$pertanyaan', '$tgl_sekarang')";
        
        if(mysqli_query($koneksi,$sql_import)){
          // hapus kembali file .xls yang di upload tadi
          unlink($_FILES['file']['name']);

          save_alert('save','Import Sukses');
          htmlRedirect('media.php?module='.$module.'&act=esay',1);
        }
        else {
          save_alert('error','Gagal Import');
          htmlRedirect('media.php?module='.$module.'&act=esay',1);
        }
      }
      else {
        save_alert('error','Gagal Import (Tabel Excel Ada yang kosong)');
        htmlRedirect('media.php?module='.$module.'&act=esay',1);
      }
    }    
  }


}

 ?>