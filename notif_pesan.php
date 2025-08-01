<?php
error_reporting(0);
session_start();

if(isset($_POST["view"]))
{
 //echo "aa";
 include "config/koneksi.php";
 
 if($_POST["view"] != '') {}
 
 $query = "SELECT DISTINCT * FROM kirim_pesan WHERE ke = '$_SESSION[id_user]' AND dibaca='N' UNION SELECT b.id_kirim, b.dari, b.ke, b.judul_balas, b.isi_balas,b.tgl_balas,b.jam_balas, b.dibaca FROM kirim_pesan a, balas_pesan b WHERE a.id_kirim=b.id_kirim AND b.dibaca='N' AND b.ke = '$_SESSION[id_user]' ORDER BY tgl_kirim DESC,jam_kirim DESC LIMIT 10";

 $result = mysqli_query($koneksi, $query);
 $output = '<div class="dropdown-list dropdown-menu-right shadow"><h6 class="dropdown-header">
                  Notifikasi Pesan Masuk</h6>';
 
 if(mysqli_num_rows($result) != 0)
 {
  while($row = mysqli_fetch_array($result))
  {

    $cek_us = mysqli_fetch_array(mysqli_query($koneksi,"SELECT level FROM user WHERE id_user='$row[dari]'"));
    
    if($cek_us['level']=='user_siswa') {
      $r2 = mysqli_fetch_array(mysqli_query($koneksi,"SELECT a.nama_lengkap, a.foto, b.nama_kelas,c.id_kelas FROM siswa a, m_kelas b, f_kelas c WHERE a.nis=c.nis AND b.id_kelas=c.id_kelas AND a.id='$row[dari]'"));
      $nama = $r2['nama_lengkap'];
      $jab  = $r2['nama_kelas'];
      $foto = 'module/foto_siswa/'.$r2['foto'];
    }
    else {
      $r2 = mysqli_fetch_array(mysqli_query($koneksi,"SELECT DISTINCT nama_lengkap, jabatan, foto FROM guru WHERE id='$row[dari]' "));
      $nama = $r2['nama_lengkap'];
      $jab  = $r2['jabatan'];
      $foto = 'module/foto_pengajar/'.$r2['foto'];
    }

   $output .= '
   <li>
    <a class="dropdown-item d-flex align-items-center" href="?module=ug_pesan&act=lihat_pesan&id='.$row[id_kirim].'">
      <div class="dropdown-list-image mr-3">
        <img class="rounded-circle" src="'.$foto.'">
      </div>
      <div class="font-weight-bold">
        <div class="text-truncate"><b class="text-danger">'.$nama.'</b><br>'.$row["judul_pesan"].'</div>
        <div class="small text-gray-500">'.substr($row["isi_pesan"],0,10).'</div>
      </div>
     
    </a>
   </li>
   <li class="divider"></li>
   ';
  }
 }
 else
 {
  $output .= '<li>
                <div class="dropdown-list-image mr-4"></div>
                <div class="font-weight-italic m-4">
                  <div class="text-truncate"><center><i class="text-danger">Tidak Ada Pesan Baru Yang Masuk</i></center></div>
                  <div class="small text-gray-500"></div>
                </div>
              </li>';
 }

  $output .='</div>';
 
 $query_1 = "SELECT DISTINCT * FROM kirim_pesan WHERE ke = '$_SESSION[id_user]' AND dibaca='N' UNION SELECT b.id_kirim, b.dari,b.ke, b.judul_balas, b.isi_balas,b.tgl_balas,b.jam_balas, b.dibaca FROM kirim_pesan a, balas_pesan b WHERE a.id_kirim=b.id_kirim AND b.dibaca='N' AND b.ke = '$_SESSION[id_user]' ORDER BY tgl_kirim DESC,jam_kirim DESC LIMIT 10";
 $result_1 = mysqli_query($koneksi, $query_1);
 $count = mysqli_num_rows($result_1);
 $data = array(
  'notification'   => $output,
  'unseen_notification' => $count
 );
 echo json_encode($data);
}
?>