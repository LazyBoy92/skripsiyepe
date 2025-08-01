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
  if ($_SESSION['leveluser']=='user_guru' OR $_SESSION['leveluser']=='user_siswa'){


?>
<div class="row">
  <div class="col-md-11">
    <div class="card card-solid">
      <div class="card-header with-border">
        <h6>Daftar Pesan Masuk</h6>
      </div>
      <div class="card-body">
        <table class="table table-striped" id="table_1">
          <thead>
            <tr><th width="5%">No</th><th width="30%">Dari</th><th width="50%">Isi Pesan</th><th width="15%">Aksi</th></tr>
          </thead>
          <tbody>
            <?php 
            $no = 1;
            $sql_data=mysqli_query($koneksi,"SELECT DISTINCT * FROM kirim_pesan WHERE ke = '$_SESSION[id_user]' UNION SELECT b.id_kirim, b.dari,b.ke, b.judul_balas, b.isi_balas,b.tgl_balas,b.jam_balas, b.dibaca FROM kirim_pesan a, balas_pesan b WHERE a.id_kirim=b.id_kirim AND b.ke = '$_SESSION[id_user]' ORDER BY tgl_kirim DESC,jam_kirim DESC");
            foreach ($sql_data as $r ) {
              $user=mysqli_fetch_array(mysqli_query($koneksi,"SELECT level FROM user WHERE id_user='$r[dari]'"));

              if($user['level']=='user_siswa') {
                  $sql_data = mysqli_fetch_array(mysqli_query($koneksi,"SELECT a.nama_lengkap, b.nama_kelas,c.id_kelas FROM siswa a, m_kelas b, f_kelas c WHERE a.nis=c.nis AND b.id_kelas=c.id_kelas AND a.id='$r[dari]'"));
                  
                  $jabatan=$sql_data['nama_kelas'];
                  if ($r['dibaca']=='N') { $status = '<small><i class="text-info">Belum di baca</i></small>'; }
                  else {$status = '<small><i class="text-info">Sudah di baca</i></small>';}
              }
              else {
                $sql_data = mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_lengkap, jabatan FROM guru WHERE id='$r[dari]'"));
                  $jabatan=$sql_data['jabatan'];
                  if ($r['dibaca']=='N') { $status = '<small><i class="text-info">Belum di baca</i></small>'; }
                  else {$status = '<small><i class="text-info">Sudah di baca</i></small>';}
              }
            ?>
            <tr>
              <td><?= $no;?></td>
              <td><?= $sql_data['nama_lengkap'].'<br><small><b class="text-primary">'.$jabatan.'</b></small>';?></td>
              <td><?= '<b class="text-primary">'.$r['judul_pesan'].'</b><br>'.substr($r['isi_pesan'], 0,10).'<br>'.$status;?></td>
              <td><a href="?module=ug_pesan&act=lihat_pesan&id=<?= $r['id_kirim'];?>" class="btn-sm btn-primary"><i class="fas fa-eye"></i> Lihat isi</a></td>
            </tr>
            <?php
            $no++; 
            }

            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<?php 
  }
} 
?>
