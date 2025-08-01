<script>
function confirmdelete(delUrl) {
if (confirm("Anda yakin ingin menghapus?")) {
document.location = delUrl;
}
}
</script>
<?php 
 ?>
<?php
//Deteksi hanya bisa diinclude, tidak bisa langsung dibuka (direct open)
if(count(get_included_files())==1){
  echo "<meta http-equiv='refresh' content='0; url=http://$_SERVER[HTTP_HOST]'>";
  exit("Direct access not permitted.");
  }
error_reporting(0);
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../error_login.php');
  }
else{
  if ($_SESSION['leveluser']=='user_guru' OR $_SESSION['leveluser']=='user_siswa' ){

    $id_kirim = $_GET['id'];

    //UPDATE DIBACA MENJADI Y
    mysqli_query($koneksi,"UPDATE kirim_pesan SET dibaca = 'Y' WHERE id_kirim = '$id_kirim'");
    mysqli_query($koneksi,"UPDATE balas_pesan SET dibaca = 'Y' WHERE id_kirim = '$id_kirim'");
    
    $d =mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM kirim_pesan WHERE id_kirim = '$id_kirim'"));
    $cek_us = mysqli_fetch_array(mysqli_query($koneksi,"SELECT level FROM user WHERE id_user='$d[dari]'"));
    
    if($cek_us['level']=='user_siswa') {
      $r2 = mysqli_fetch_array(mysqli_query($koneksi,"SELECT a.nama_lengkap, a.foto, b.nama_kelas,c.id_kelas FROM siswa a, m_kelas b, f_kelas c WHERE a.nis=c.nis AND b.id_kelas=c.id_kelas AND a.id='$d[dari]'"));
      $nama = $r2['nama_lengkap'];
      $jab  = $r2['nama_kelas'];
      $foto = '<img class="img-profile rounded-circle" src="module/foto_siswa/'.$r2['foto'].'" width="30">';
    }
    else {
      $r2 = mysqli_fetch_array(mysqli_query($koneksi,"SELECT DISTINCT nama_lengkap, jabatan, foto FROM guru WHERE id='$d[dari]' "));
      $nama = $r2['nama_lengkap'];
      $jab  = $r2['jabatan'];
      $foto = '<img class="img-profile rounded-circle" src="module/foto_pengajar/'.$r2['foto'].'" width="30">';
    }

    
?>

<div class="row">
  <div class="col-md-12">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h2 class="m-0 font-weight-bold text-primary"><?= $d['judul_pesan'];?></h2>
        <div class="dropdown no-arrow">
          <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="text-secondary"><?= tgl_indo($d['tgl_kirim']);?></i> | <?= $d['jam_kirim'];?>
          </a>
          <a href="?module=ug_pesan&act=balas_pesan&id=<?= $d['id_kirim'];?>" class="btn btn-primary"><i class="fas fa-reply"></i> Balas Pesan</a>
        </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-secondary"><?=$foto.' '.$nama;?> | <?= $jab;?> </h6>
        <div class="dropdown no-arrow">
          <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            
          </a>
        </div>
    </div>
    <div class="card-body bg-white">
      <p align="justify-content-between" style="font-size: 20px;"><?= $d['isi_pesan'];?></p>
    </div>
  </div>
</div>

<?php 

$sql_balas = mysqli_query($koneksi,"SELECT * FROM balas_pesan WHERE id_kirim = '$id_kirim' ORDER BY id_balas DESC");
$cek = mysqli_num_rows($sql_balas);
if ($cek !=0) {
?>

<br>
<div class="row">
  <div class="col-md-12">
      <div class="card-header with-border">
        <i class="text-info">Pesan Balasan</i>
      </div>
      <?php 

      foreach ($sql_balas as $k ) {
        $db_u = mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM user WHERE id_user ='$k[dari]'"));
        //CARI FOTO USER
        if($db_u['level']=='user_guru') {
          $qf=mysqli_fetch_array(mysqli_query($koneksi,"SELECT foto FROM guru WHERE id='$db_u[id_user]'"));
          $foto = '<img class="img-profile rounded-circle" src="module/foto_pengajar/'.$qf['foto'].'" width="30">';
        }
        else {
          $qf=mysqli_fetch_array(mysqli_query($koneksi,"SELECT foto FROM siswa WHERE id='$db_u[id_user]'"));
          $foto = '<img class="img-profile rounded-circle" src="module/foto_siswa/'.$qf['foto'].'" width="30">';
        }
      ?>  

      <div class="card-body bg-white">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between ">
        <h6 class="m-0 font-weight-bold text-secondary"> <?= $foto.' '.$db_u['nama_lengkap'];?> |  <?= substr($db_u['level'], 5,10);?> | <i class="text-info>"><?= $k['judul_balas'];?></i></h6> <small><?= substr(tgl_indo($k['tgl_balas']), 0,6).' - '.substr($k['jam_balas'],0,5);?></small>
        </div>
        <p align="justify-content-between" style="font-size: 20px;"><?= $k['isi_balas'];?></p>
      </div>

      <?php
      }


       ?>
  </div>
</div>


<?php
}
else {
?>
<br>
<div class="row">
  <div class="col-md-12">
      <div class="card-header with-border">
        <i class="text-info">Belum ada pesan balasan...</i>
      </div>
  </div>
</div>
<?php 
} 
?>



<?php 
  }
} 
?>
