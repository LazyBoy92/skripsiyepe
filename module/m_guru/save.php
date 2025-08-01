<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../index.php');
}
  else { 
    
    if (isset($_POST['upload_guru'])) {


      $target = basename($_FILES['fguru']['name']);

      move_uploaded_file($_FILES['fguru']['tmp_name'], $target);

      // mengambil isi file xls
      $data = new Spreadsheet_Excel_Reader($_FILES['fguru']['name'],false);

      // menghitung jumlah baris data yang ada
      $jumlah_baris = $data->rowcount($sheet_index=0);
      $d=1;
      for ($i=2; $i<=$jumlah_baris; $i++){
   
        // menangkap data dan memasukkan ke variabel sesuai dengan kolumnya masing-masing
        $nip            = $data->val($i, 1);
        $nama_lengkap   = $data->val($i, 2);
        $jabatan        = $data->val($i, 3);
        $alamat         = $data->val($i, 4);
        $tempat_lahir   = $data->val($i, 5);
        $tgl_lahir      = $data->val($i, 6);
        $jenis_kelamin  = $data->val($i, 7);
        $agama          = $data->val($i, 8);
        $th_masuk       = $data->val($i, 8);
        $email          = $data->val($i, 10);
        $no_telp        = $data->val($i, 11);
        //TANGGAL LAHIR
        $bln = substr($tgl_lahir,0,2);
        $tgl = substr($tgl_lahir,3,2);
        $thn = substr($tgl_lahir,6,4);
        $tanggal = $thn.$bln.$tgl;

        $id_guru = id_guru();
        $id_max = $id_guru+$d;

        if($nip != "" && $nama_lengkap != "" && $tempat_lahir != "" && $tanggal != "" && $jenis_kelamin != "" && $agama != ""){

           $sql_import= "INSERT INTO guru(id,nip, nama_lengkap, jabatan, alamat, tempat_lahir, tgl_lahir, jenis_kelamin, agama, th_masuk, email, no_telp) VALUES('$id_max','$nip', '$nama_lengkap', '$jabatan', '$alamat', '$tempat_lahir', '$tanggal', '$jenis_kelamin', '$agama', '$th_masuk', '$email', '$no_telp')";
          
          if(mysqli_query($koneksi,$sql_import)){
            // hapus kembali file .xls yang di upload tadi
            unlink($_FILES['fguru']['name']);

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


}

 ?>