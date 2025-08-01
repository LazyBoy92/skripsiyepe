<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../index.php');
}
 else { 
  

if (isset($_POST['edit_forum'])) {

  $lokasi_file = $_FILES['fupload']['tmp_name'];
  $nama_file   = $_FILES['fupload']['name'];
  $tipe_file   = $_FILES['fupload']['type'];
  

  $extensionList = array("jpg", "jpeg");
  $pecah = explode(".", $nama_file);
  $ekstensi = $pecah[1];

  if(!empty($nama_file)) {
    if (!in_array($ekstensi, $extensionList)){
                 
        save_alert('error','Type File tidak di izinkan');
        htmlRedirect('media.php?module='.$module);
          }
    else{ 
          $file_lama=mysqli_fetch_array(mysqli_query($koneksi,"SELECT gambar FROM topik_forum WHERE id='$_POST[id]'"));
          $exist_file = 'module/foto_forum/'.$file_lama['gambar'];
          unlink($exist_file);

          UploadImage_forum($nama_file);
          mysqli_query($koneksi,"UPDATE topik_forum SET id_kat = '$_POST[id_kat]', id_mapel = '$_POST[id_mapel]', judul_topik = '$_POST[judul_topik]', isi_topik = '$_POST[isi_topik]', gambar = '$nama_file' WHERE id='$_POST[id]'");
          save_alert('save','Update Sukses');
          htmlRedirect('media.php?module='.$module.'&act=read&id='.$_POST[id]);
        }
    }
  else{
          mysqli_query($koneksi,"UPDATE topik_forum SET id_kat = '$_POST[id_kat]', id_mapel = '$_POST[id_mapel]', judul_topik = '$_POST[judul_topik]', isi_topik = '$_POST[isi_topik]' WHERE id='$_POST[id]'");
          save_alert('save','Update Sukses');
          htmlRedirect('media.php?module='.$module.'&act=read&id='.$_POST[id]);
          //echo "UPDATE topik_forum SET id_kat = '$_POST[id_kat]', id_mapel = '$_POST[id_mapel]', judul_topik = '$_POST[judul_topik]', isi_topik = '$_POST[isi_topik]' WHERE id='$_POST[id]'";
    }
  }

elseif (isset($_POST['simpan_forum'])) {

  $lokasi_file = $_FILES['fupload']['tmp_name'];
  $nama_file   = $_FILES['fupload']['name'];
  $tipe_file   = $_FILES['fupload']['type'];
  

  $extensionList = array("jpg", "jpeg");
  $pecah = explode(".", $nama_file);
  $ekstensi = $pecah[1];

    if (!in_array($ekstensi, $extensionList)){
                 
        save_alert('error','Type File tidak di izinkan');
        htmlRedirect('media.php?module='.$module);
          }
    else{ 

          UploadImage_forum($nama_file);
          mysqli_query($koneksi,"INSERT INTO topik_forum(id_kat, id_user, id_mapel, judul_topik, isi_topik, tgl_post,gambar) VALUES ('$_POST[id_kat]', '$_POST[id_user]', '$_POST[id_mapel]', '$_POST[judul_topik]', '$_POST[isi_topik]','$tgl_sekarang','$nama_file')");
          save_alert('save','Postingan baru berhasil');
          htmlRedirect('media.php?module='.$module);
        }
    }

elseif ($_GET['post']=='hapus_topik') {

    $file_lama=mysqli_fetch_array(mysqli_query($koneksi,"SELECT gambar FROM topik_forum WHERE id='$_GET[id]'"));
    $exist_file = 'module/foto_forum/'.$file_lama['gambar'];
    unlink($exist_file);

    mysqli_query($koneksi,"DELETE FROM topik_forum WHERE id='$_GET[id]'");
    save_alert('save','Topik Forum di hapus');
    htmlRedirect('media.php?module='.$module);
    }


elseif (isset($_POST['simpan_komen'])) {

  $tgl_post = $date_time;
  $sql_save= "INSERT INTO komentar(id_topik, id_user, isi_komentar, tgl_post_komentar) VALUES ('$_POST[id_topik]', '$_POST[id_user]', '$_POST[isi_komentar]','$tgl_post')";
  //echo $sql_save;
  
    if (mysqli_query($koneksi, $sql_save)){
                 
        save_alert('save','Komentar di tambahkan');
        htmlRedirect('media.php?module='.$module.'&act=read&id='.$_POST[id_topik]);
          }
    else{ 
          save_alert('error','Tidak bisa di simpan');
          htmlRedirect('media.php?module='.$module.'&act=read&id='.$_POST[id_topik]);
        }
    }

elseif (isset($_POST['edit_komen'])) {

  $tgl_post = $date_time;
  $sql_edit= "UPDATE komentar SET isi_komentar = '$_POST[isi_komentar]' WHERE id='$_POST[id]'";
  //echo $sql_save;
  
    if (mysqli_query($koneksi, $sql_edit)){
                 
        save_alert('save','Update berhasil');
        htmlRedirect('media.php?module='.$module.'&act=read&id='.$_POST[id_topik]);
          }
    else{ 
          save_alert('error','Tidak bisa di simpan');
          htmlRedirect('media.php?module='.$module.'&act=read&id='.$_POST[id_topik]);
        }
    }

elseif ($_GET['post']=='hapus_komentar') {

    //echo "DELETE FROM komentar WHERE id='$_GET[id]'";
    mysqli_query($koneksi,"DELETE FROM komentar WHERE id='$_GET[id]'");
    save_alert('save','Komentar di hapus');
    htmlRedirect('media.php?module='.$module.'&act=read&id='.$_GET[id_topik]);
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