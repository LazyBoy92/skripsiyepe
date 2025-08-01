<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../index.php');
}
  else { 
    
    if (isset($_POST['upload_kelas'])) {


      $target = basename($_FILES['fkelas']['name']);

      move_uploaded_file($_FILES['fkelas']['tmp_name'], $target);

      // mengambil isi file xls
      $data = new Spreadsheet_Excel_Reader($_FILES['fkelas']['name'],false);

      // menghitung jumlah baris data yang ada
      $jumlah_baris = $data->rowcount($sheet_index=0);
     
      for ($i=2; $i<=$jumlah_baris; $i++){
   
        // menangkap data dan memasukkan ke variabel sesuai dengan kolumnya masing-masing
        $nis            = $data->val($i, 1);
        $id_kelas       = $_POST['id_kelas'];
        $tahun_p        = $tahun_p;
        
        $cek = mysqli_num_rows(mysqli_query($koneksi,"SELECT nis FROM f_kelas WHERE nis ='$nis' AND tp='$tahun_p' AND id_kelas='$id_kelas'"));

        if($cek == 0 ){

           $sql_import= "INSERT INTO f_kelas(nis, id_kelas, tp) VALUES('$nis', '$id_kelas', '$tahun_p')";
          
          if(mysqli_query($koneksi,$sql_import)){
            // hapus kembali file .xls yang di upload tadi
            unlink($_FILES['fkelas']['name']);

            save_alert('save','Import Sukses');
            htmlRedirect('media.php?module='.$module.'&act=detail&id_kelas='.$_POST['id_kelas'],1);
          }
          else {
            //echo $sql_import;
            save_alert('error','Gagal Import');
            htmlRedirect('media.php?module='.$module.'&act=detail&id_kelas='.$_POST['id_kelas'],1);
          }
        }

        else {
          save_alert('error','Gagal Import (Data Sudah Ada)');
          htmlRedirect('media.php?module='.$module.'&act=detail&id_kelas='.$_POST['id_kelas'],1);
        }
      }   
  }

  elseif (isset($_POST['hapus_siswa_kelas'])) {

    $id_kelas = $_POST['id_kelas'];
    $tp       = $_POST['tp'];

    $sql_hapus = mysqli_query($koneksi,"DELETE FROM f_kelas WHERE id_kelas='$id_kelas' AND tp='$tahun_p'");

    if($sql_hapus){
      save_alert('save','Hapus Berhasil');
      htmlRedirect('media.php?module='.$module.'&act=detail&id_kelas='.$_POST['id_kelas'],1);
    } 
    else {
      save_alert('error','Hapus_gagal');
      htmlRedirect('media.php?module='.$module.'&act=detail&id_kelas='.$_POST['id_kelas'],1);
    }    
  }

}

 ?>