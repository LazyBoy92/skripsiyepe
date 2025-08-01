<?php
include "config/koneksi.php";

$direktori = "module/files_materi/"; // folder tempat penyimpanan file yang boleh didownload
$id_file   = $_GET['id'];
$r         = mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_file FROM file_materi WHERE id_file='$id_file'"));
$filename  = $r['nama_file'];

$file      = $direktori.$filename;

  if(file_exists($file)) {
    mysqli_query($koneksi,"UPDATE file_materi SET hits=hits+1 where nama_file='$filename'");
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: private');
    header('Pragma: private');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
  } 
  else {
     echo "<h1>Access forbidden!</h1>
        <p>Maaf, file yang Anda download sudah tidak tersedia atau filenya (direktorinya) telah diproteksi. <br />
        Silahkan hubungi <a href='mailto:administrator@digitalsmkn1cilegon.net'>webmaster</a>.</p>";
  exit;
        }

/*
$file_extension = strtolower(substr(strrchr($filename,"."),1));

switch($file_extension){
  case "pdf": $ctype="application/pdf"; break;
  case "exe": $ctype="application/octet-stream"; break;
  case "zip": $ctype="application/zip"; break;
  case "rar": $ctype="application/rar"; break;
  case "doc": $ctype="application/msword"; break;
  case "xls": $ctype="application/vnd.ms-excel"; break;
  case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
  case "gif": $ctype="image/gif"; break;
  case "png": $ctype="image/png"; break;
  case "jpeg":
  case "jpg": $ctype="image/jpg"; break;
  default: $ctype="application/proses";
}

if ($file_extension=='php'){
  echo "<h1>Access forbidden!</h1>
        <p>Maaf, file yang Anda download sudah tidak tersedia atau filenya (direktorinya) telah diproteksi. <br />
        Silahkan hubungi <a href='mailto:administrator@digitalsmkn1cilegon.net'>webmaster</a>.</p>";
  exit;
}
else{
  mysqli_query($koneksi,"UPDATE file_materi SET hits=hits+1 where nama_file='$filename'");

  header("Content-Type: octet/stream");
  header("Pragma: private"); 
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private",false); 
  header("Content-Type: $ctype");
  header("Content-Disposition: attachment; filename=".basename($filename).";" );
  header("Content-Transfer-Encoding: binary");
  header("Content-Length: ".filesize($direktori.$filename));
  readfile("$direktori$filename");
  exit();   
}*/
?>
