<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../index.php');
}
 else { 
  

if (isset($_POST['edit_video'])) {

  $lokasi_file = $_FILES['fupload']['tmp_name'];
  $nama_file   = $_FILES['fupload']['name'];
  $tipe_file   = $_FILES['fupload']['type'];
  

  $extensionList = array("mp4", "avi");
  $pecah = explode(".", $nama_file);
  $ekstensi = $pecah[1];

  $kelas = $_POST['id_kelas'];

  if(!empty($nama_file)) {
    if (!in_array($ekstensi, $extensionList)){
                 
        save_alert('error','Type File tidak di izinkan');
        htmlRedirect('media.php?module='.$module);
          }
    else{ 
          $file_lama=mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_video FROM file_video WHERE id_video='$_POST[id_video]'"));
          $exist_file = 'module/files_video/'.$file_lama['nama_video'];
          unlink($exist_file);

          UploadVideo($nama_file);
          mysqli_query($koneksi,"UPDATE file_video SET judul = '$_POST[judul]', id_mapel  = '$_POST[id_mapel]', nama_video = '$nama_file', keterangan = '$_POST[keterangan]' WHERE id_video = '$_POST[id_video]'");

          mysqli_query($koneksi,"DELETE FROM file_video_det WHERE id_video='$_POST[id_video]'");
            $jk    = count($kelas);
            for($x=0;$x<$jk;$x++){
              $a2="INSERT INTO file_video_det VALUES('$_POST[id_video]','$kelas[$x]')";
              mysqli_query($koneksi,$a2);
              //echo $a2;
            }

          save_alert('save','File Video di Upadate');
          htmlRedirect('media.php?module='.$module);
        }
    }
  else{
      mysqli_query($koneksi,"UPDATE file_video SET judul = '$_POST[judul]', id_mapel  = '$_POST[id_mapel]', youtube  = '$_POST[youtube]', keterangan = '$_POST[keterangan]' WHERE id_video = '$_POST[id_video]'");

        mysqli_query($koneksi,"DELETE FROM file_video_det WHERE id_video='$_POST[id_video]'");
        $jk    = count($kelas);
        for($x=0;$x<$jk;$x++){
                $a2="INSERT INTO file_video_det VALUES('$_POST[id_video]','$kelas[$x]')";
                mysqli_query($koneksi,$a2);
                //echo $a2;
        }
      save_alert('save','Update Sukses');
      htmlRedirect('media.php?module='.$module);
    }
  }

elseif (isset($_POST['simpan_video'])) {

  $lokasi_file = $_FILES['fupload']['tmp_name'];
  $nama_file   = $_FILES['fupload']['name'];
  $tipe_file   = $_FILES['fupload']['type'];
  

  $extensionList = array("mp4", "avi");
  $pecah = explode(".", $nama_file);
  $ekstensi = $pecah[1];

  $kelas = $_POST['id_kelas'];

  $id_file = mysqli_fetch_array(mysqli_query($koneksi,"SELECT max(id_video) as maxid FROM file_video"));
  $idmax =  $id_file['maxid']+1;

    if (!in_array($ekstensi, $extensionList)){
                 
        save_alert('error','Type File tidak di izinkan');
        htmlRedirect('media.php?module='.$module);
          }
    else{ 

          UploadVideo($nama_file);
          mysqli_query($koneksi,"INSERT INTO file_video(id_video,judul,id_mapel,nama_video,tgl_posting,pembuat,hits,keterangan) VALUES ('$id_video','$_POST[judul]', '$_POST[id_mapel]', '$nama_file','$tgl_sekarang','$_POST[pembuat]','0','$_POST[keterangan]')");

          $jk    = count($kelas);
            for($x=0;$x<$jk;$x++){
              $a2="INSERT INTO file_video_det VALUES('$idmax','$kelas[$x]')";
              mysqli_query($koneksi,$a2);
              //echo $a2;
            }

          save_alert('save','File Video di Simpan');
          htmlRedirect('media.php?module='.$module);
        }
    }

elseif (isset($_POST['simpan_youtube'])) {

    $id_file = mysqli_fetch_array(mysqli_query($koneksi,"SELECT max(id_video) as maxid FROM file_video"));
    $idmax =  $id_file['maxid']+1;
    $kelas = $_POST['id_kelas'];

    mysqli_query($koneksi,"INSERT INTO file_video(id_video,judul,id_mapel,tgl_posting,pembuat,hits,youtube) VALUES ('$idmax','$_POST[judul]', '$_POST[id_mapel]', '$tgl_sekarang','$_POST[pembuat]','0','$_POST[youtube]')");

    $jk    = count($kelas);
            for($x=0;$x<$jk;$x++){
              $a2="INSERT INTO file_video_det VALUES('$idmax','$kelas[$x]')";
              mysqli_query($koneksi,$a2);
              //echo $a2;
            }
    save_alert('save','File Video di Simpan');
    htmlRedirect('media.php?module='.$module);
        
    }

elseif ($_GET['post']=='hapus') {

    $file_lama=mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_video FROM file_video WHERE id_video='$_GET[id_video]'"));
    $exist_file = 'module/files_video/'.$file_lama['nama_video'];
    unlink($exist_file);

    mysqli_query($koneksi,"DELETE FROM file_video_det WHERE id_video='$_GET[id_video]'");
    mysqli_query($koneksi,"DELETE FROM file_video WHERE id_video='$_GET[id_video]'");
    save_alert('save','Video di hapus');
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