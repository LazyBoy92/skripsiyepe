<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../index.php');
}
 else { 
  

if (isset($_POST['update'])) {

  $cek_data=mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM f_mapel WHERE id_mapel = '$_POST[id_mapel]' AND nip ='$_POST[nip]' AND id_kelas='$_POST[id_kelas]' AND tp='$_POST[tp]' "));
    //echo "SELECT * FROM f_mapel WHERE id_mapel = '$_POST[id_mapel]' AND nip ='$_POST[nip]' AND tp='$_POST[tp]' ";
    if ($cek_data!=0){
                 
        save_alert('error','Data Sudah Ada');
        htmlRedirect('media.php?module='.$module);
          }
    else{ 
        mysqli_query($koneksi,"UPDATE f_mapel SET id_mapel = '$_POST[id_mapel]', id_kelas = '$_POST[id_kelas]', deskripsi = '$_POST[deskripsi]' WHERE id = '$_POST[id]'");
          save_alert('save','Berhasil di Update');
          htmlRedirect('media.php?module='.$module);
        }
  }



if (isset($_POST['simpan'])) {

    $cek_data=mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM f_mapel WHERE id_mapel = '$_POST[id_mapel]' AND nip ='$_POST[nip]' AND id_kelas='$_POST[id_kelas]' AND tp='$_POST[tp]' "));
    //echo "SELECT * FROM f_mapel WHERE id_mapel = '$_POST[id_mapel]' AND nip ='$_POST[nip]' AND tp='$_POST[tp]' ";
    if ($cek_data!=0){
                 
        save_alert('error','Data Sudah Ada');
        htmlRedirect('media.php?module='.$module);
          }
    else{ 
        mysqli_query($koneksi,"INSERT INTO f_mapel(id_mapel,id_kelas,nip,deskripsi,tp) VALUES ('$_POST[id_mapel]', '$_POST[id_kelas]', '$_POST[nip]','$_POST[deskripsi]','$_POST[tp]')");
          save_alert('save','Data Tersimpan');
          htmlRedirect('media.php?module='.$module);
        }
    }

if ($_GET['post']=='hapus') {

    mysqli_query($koneksi,"DELETE FROM f_mapel WHERE id='$_GET[id]'");
    save_alert('save','Data di hapus');
    htmlRedirect('media.php?module='.$module);
    }




}



/*
if (isset($_POST['simpan_essay'])) {
	$lokasi_file = $_FILES['fupload']['tmp_name'];
  $nama_file   = $_FILES['fupload']['name'];
  $tipe_file   = $_FILES['fupload']['type'];
  $direktori_file = "./././files_materi/$nama_file";

  $extensionList = array("zip", "rar", "doc", "docx", "ppt", "pptx", "pdf");
  $pecah = explode(".", $nama_file);
  $ekstensi = $pecah[1];
  
  $pelajaran = mysql_query("SELECT * FROM mata_pelajaran WHERE id_matapelajaran = '$_POST[id_matapelajaran]'");
  $data_mapel = mysql_fetch_array($pelajaran);
  $pengajar = mysql_query("SELECT * FROM siswa WHERE id_siswa = '$data_mapel[id_pengajar]'");
  
      if (file_exists($direktori_file)){
            echo "<center> File sudah ada, Silahkan Pilih Yang Lain</center>";
						save_alert('error',error);
						htmlRedirect('media.php?module='.$module);
            }
	  elseif (!in_array($ekstensi, $extensionList)){
               
                echo "<center>Type File Tidak di Ijinkan, Silahkan Pilih Yang Lain</center>";
						save_alert('error',error);
						htmlRedirect('media.php?module='.$module);
        }
			else{ //echo "A";
                    UploadFile($nama_file);
                    mysql_query("INSERT INTO file_materi(judul,
                                    id_kelas,
                                    id_matapelajaran,
                                    nama_file,
                                    tgl_posting,
                                    pembuat)
                            VALUES('$_POST[judul]',
                                   '$_POST[id_kelas]',
                                   '$_POST[id_matapelajaran]',
                                   '$nama_file',
                                   '$tgl_sekarang',
                                    '$data_mapel[id_pengajar]')");
                    save_alert('save',save);
					htmlRedirect('media.php?module='.$module);
            }
}*/
 ?>