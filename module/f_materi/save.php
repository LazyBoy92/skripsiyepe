<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../index.php');
}
 else { 
  

if (isset($_POST['update'])) {

  $lokasi_file = $_FILES['fupload']['tmp_name'];
  $nama_file   = $_FILES['fupload']['name'];
  $tipe_file   = $_FILES['fupload']['type'];
  

  $extensionList = array("zip", "rar", "doc", "docx", "ppt", "pptx", "pdf");
  $pecah = explode(".", $nama_file);
  $ekstensi = $pecah[1];

   $kelas = $_POST['id_kelas'];

  if(!empty($nama_file)) {
    if (!in_array($ekstensi, $extensionList)){
                 
        save_alert('error','Type File tidak di izinkan');
        htmlRedirect('media.php?module='.$module);
          }
    else{ 
          $file_lama=mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_file FROM file_materi WHERE id_file='$_POST[id_file]'"));
          $exist_file = 'module/files_materi/'.$file_lama['nama_file'];
          unlink($exist_file);

          UploadFile($nama_file);
          mysqli_query($koneksi,"UPDATE file_materi SET judul = '$_POST[judul]', id_mapel  = '$_POST[id_mapel]', nama_file = '$nama_file' WHERE id_file = '$_POST[id_file]'");

          mysqli_query($koneksi,"DELETE FROM file_materi_det WHERE id_file='$_POST[id_file]'");
            $jk    = count($kelas);
            for($x=0;$x<$jk;$x++){
              $a2="INSERT INTO file_materi_det VALUES('$_POST[id_file]','$kelas[$x]')";
              mysqli_query($koneksi,$a2);
              //echo $a2;
            }

          save_alert('save','File Materi di Upadate');
          htmlRedirect('media.php?module='.$module);
        }
    }
  else{
      mysqli_query($koneksi,"UPDATE file_materi SET judul = '$_POST[judul]', id_mapel  = '$_POST[id_mapel]'WHERE id_file = '$_POST[id_file]'");

       mysqli_query($koneksi,"DELETE FROM file_materi_det WHERE id_file='$_POST[id_file]'");
        $jk    = count($kelas);
        for($x=0;$x<$jk;$x++){
                $a2="INSERT INTO file_materi_det VALUES('$_POST[id_file]','$kelas[$x]')";
                mysqli_query($koneksi,$a2);
                //echo $a2;
        }

      save_alert('save','Update Sukses');
      htmlRedirect('media.php?module='.$module);
    }
  }

if (isset($_POST['simpan'])) {

  $lokasi_file = $_FILES['fupload']['tmp_name'];
  $nama_file   = $_FILES['fupload']['name'];
  $tipe_file   = $_FILES['fupload']['type'];
  
  $extensionList = array("zip", "rar", "doc", "docx", "ppt", "pptx", "pdf");
  $pecah = explode(".", $nama_file);
  $ekstensi = $pecah[1];

  $kelas = $_POST['id_kelas'];

  $id_file = mysqli_fetch_array(mysqli_query($koneksi,"SELECT max(id_file) as maxid FROM file_materi"));
  $idmax =  $id_file['maxid']+1;
  
    if (!in_array($ekstensi, $extensionList)){
                 
        save_alert('error','Type File tidak di izinkan');
        htmlRedirect('media.php?module='.$module);
          }
    else{ 

          //echo "INSERT INTO file_materi(judul,id_mapel,nama_file,tgl_posting,hits,pembuat) VALUES ('$_POST[judul]', '$_POST[id_mapel]', '$nama_file','$tgl_sekarang','0','$_POST[pembuat]')";
          UploadFile($nama_file);
          mysqli_query($koneksi,"INSERT INTO file_materi(id_file, judul, id_mapel, nama_file, tgl_posting, hits, pembuat) VALUES ('$idmax','$_POST[judul]', '$_POST[id_mapel]', '$nama_file','$tgl_sekarang','0','$_POST[pembuat]')");

          $jk    = count($kelas);
            for($x=0;$x<$jk;$x++){
              $a2="INSERT INTO file_materi_det VALUES('$idmax','$kelas[$x]')";
              mysqli_query($koneksi,$a2);
              //echo $a2;
            }

          save_alert('save','File Materi di simpan');
          htmlRedirect('media.php?module='.$module);
        }
    }

if ($_GET['aksi']=='hapus') {

    $file_lama=mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_file FROM file_materi WHERE id_file='$_GET[id]'"));
    $exist_file = 'module/files_materi/'.$file_lama['nama_file'];
    unlink($exist_file);

    mysqli_query($koneksi,"DELETE FROM file_materi_det WHERE id_file='$_GET[id_file]'");
    mysqli_query($koneksi,"DELETE FROM file_materi WHERE id_file='$_GET[id]'");
    save_alert('save','File Materi di hapus');
    htmlRedirect('media.php?module='.$module);
    }




}

 ?>