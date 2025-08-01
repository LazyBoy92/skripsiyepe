<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../index.php');
}
else { 
    
  if (isset($_POST['upload_siswa'])) {


    $target = basename($_FILES['fsiswa']['name']);

    move_uploaded_file($_FILES['fsiswa']['tmp_name'], $target);

    // mengambil isi file xls
    $data = new Spreadsheet_Excel_Reader($_FILES['fsiswa']['name'],false);

    // menghitung jumlah baris data yang ada
    $jumlah_baris = $data->rowcount($sheet_index=0);
    $d = 1;
    for ($i=2; $i<=$jumlah_baris; $i++){
 
      // menangkap data dan memasukkan ke variabel sesuai dengan kolumnya masing-masing
      $nis            = $data->val($i, 1);
      $nama_lengkap   = $data->val($i, 2);
      $alamat         = $data->val($i, 3);
      $tempat_lahir   = $data->val($i, 4);
      $tgl_lahir      = $data->val($i, 5);
      $jenis_kelamin  = $data->val($i, 6);
      $agama          = $data->val($i, 7);
      $nama_ayah      = $data->val($i, 8);
      $nama_ibu       = $data->val($i, 9);
      $th_masuk       = $data->val($i, 10);
      $email          = $data->val($i, 11);
      $no_telp        = $data->val($i, 12);
      //TANGGAL LAHIR
      $bln = substr($tgl_lahir,0,2);
      $tgl = substr($tgl_lahir,3,2);
      $thn = substr($tgl_lahir,6,4);
      $tanggal = $thn.$bln.$tgl;

      
      //$d++;
      $id_siswa = id_siswa();
      $id_max   = $id_siswa+$d;

      
      if($nis != "" && $nama_lengkap != "" && $tempat_lahir != "" && $tanggal != "" && $jenis_kelamin != "" && $agama != ""){

         $sql_import= "INSERT INTO siswa(id,nis, nama_lengkap, alamat, tempat_lahir, tgl_lahir, jenis_kelamin, agama, nama_ayah, nama_ibu, th_masuk, th_keluar, email, no_telp) VALUES('$id_max','$nis', '$nama_lengkap', '$alamat', '$tempat_lahir', '$tanggal', '$jenis_kelamin', '$agama', '$nama_ayah', '$nama_ibu', '$th_masuk', '9999', '$email', '$no_telp')";
        
        if(mysqli_query($koneksi,$sql_import)){
          // hapus kembali file .xls yang di upload tadi
          unlink($_FILES['fsiswa']['name']);

          save_alert('save','Import Sukses');
          htmlRedirect('media.php?module='.$module,1);
        }
        else {
          save_alert('error','Gagal Import');
          htmlRedirect('media.php?module='.$module,1);
        }
      }
      else {
        save_alert('error','Gagal Import (Tabel Excel Ada yang kosong)');
        htmlRedirect('media.php?module='.$module,1);
      }
    }   
  }

  elseif(isset($_POST['edit_data'])) {
    $nisn         = $_POST['nisn'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $jk           = $_POST['jenis_kelamin'];
    $tmp_lahir    = $_POST['tempat_lahir'];
    $tgl_lahir    = $_POST['tgl_lahir'];
    $no_telp      = $_POST['no_telp'];
    $th_masuk     = $_POST['th_masuk'];

    $sql_edit = mysqli_query($koneksi,"UPDATE siswa SET nama_lengkap = '$nama_lengkap' , jenis_kelamin = '$jk' , tempat_lahir = '$tmp_lahir' , tgl_lahir = '$tgl_lahir' , no_telp = '$no_telp' , th_masuk = '$th_masuk' WHERE id = '$_POST[id]'");
    //echo "UPDATE siswa SET nama_lengkap = '$nama_lengkap' , jenis_kelamin = '$jk' , tempat_lahir = '$tmp_lahir' , tgl_lahir = '$tgl_lahir' , no_telp = '$no_telp' , th_masuk = '$th_masuk' WHERE id = '$_POST[id]'";

    if($sql_edit){
      save_alert('save','Update Data Sukses');
      htmlRedirect('media.php?module='.$module.'&act=edit_data&id='.$_POST['id'],1);
    }
    else {
      save_alert('error','Update Gagal');
      htmlRedirect('media.php?module='.$module.'&act=edit_data&id='.$_POST['id'],1);

    }
  }


}

 ?>