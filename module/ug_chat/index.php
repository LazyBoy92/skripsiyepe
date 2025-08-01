<script>
function confirmdelete(delUrl) {
if (confirm("Anda yakin ingin menghapus?")) {
document.location = delUrl;
}
}
</script>
<?php 
//DEKLARASI FUNGSI
function fsize($file){
  $a = array("B", "KB", "MB", "GB", "TB", "PB");
  $pos = 0;
  $size = filesize($file);
  while ($size >= 1024) {
    $size /= 1024;
    $pos++;
  }
  return round ($size,2)." ".$a[$pos];
}
 ?>
<?php
//Deteksi hanya bisa diinclude, tidak bisa langsung dibuka (direct open)
if(count(get_included_files())==1){
  //echo "<meta http-equiv='refresh' content='0; url=http://$_SERVER[HTTP_HOST]'>";
  //exit("Direct access not permitted.");
  }
error_reporting(0);
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../error_login.php');
  }
else{
  switch($_GET['act']){
    default:
        if ($_SESSION['leveluser']=='user_guru'){


?>
<div class="row">
  <div class="col-md-10">
    <div class="card shadow">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Daftar Siswa di Mapel Anda</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="table_1" cellspacing="0" cellpadding="0">
            <thead>
              <tr>
                <th></th>
                <th>Nama Siswa</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody>
              <?php 
                $sql_data = mysqli_query($koneksi,"SELECT DISTINCT a.id, a.nis, a.nama_lengkap, a.foto, d.nama_kelas FROM siswa a, f_mapel b, f_kelas c, m_kelas d WHERE a.nis=c.nis AND b.id_kelas = c.id_kelas AND c.id_kelas=d.id_kelas AND b.nip = '$_SESSION[id_user]'");
                $no=1;
                while($r=mysqli_fetch_array($sql_data)){
                  $sql_st = mysqli_fetch_array(mysqli_query($koneksi,"SELECT login FROM user WHERE id_user='$r[id]'"));
                  if($sql_st['login']!=1){ $status = '<small class="text-danger"><b>Offline</b></small>';}
                  else {$status = '<small class="text-success"><b>Online</b></small>';}
                  
              ?>
                <tr>
                    <td align="center"><img src="module/foto_siswa/<?=$r['foto'];?>" width="80"></td>
                    <td><b class="text-primary"><?= $r['nama_lengkap'];?> [<?= $r['nis'];?>]</b><br><?= $status;?><br><small><?= $r['nama_kelas'];?></small></td>
                    <td align="center"><a href="?module=view_chat&id=<?= $r['id'];?>" class="btn-sm btn-primary"><i class="fas fa-comments"></i></a></td>
                    
                </tr>
              <?php
                $no++;
                }

               ?>
            </tbody>
            <tfoot>
              
            </tfoot>
          </table>
        </div>  
      </div>
    </div>
  </div>
</div>

<?php 
    }
break;

case "view":
include "drzchat.php";

break;
  }
} 
?>
