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


?>
<div class="row">
  <div class="col-md-11">
    <div class="card card-solid">
      <div class="card-header with-border">
        <h6>Daftar Pesan Keluar</h6>
      </div>
      <div class="card-body">
        <table class="table table-striped" id="table_1">
          <thead>
            <tr><th width="5%">No</th><th width="30%">Ke</th><th width="50%">Isi Pesan</th><th width="15%">Lihat</th></tr>
          </thead>
          <tbody>
            <?php 
            $no = 1;
            $sql_data=mysqli_query($koneksi,"SELECT DISTINCT * FROM kirim_pesan WHERE dari = '$_SESSION[id_user]' UNION SELECT b.id_kirim, b.ke,b.dari, b.judul_balas, b.isi_balas,b.tgl_balas,b.jam_balas, b.dibaca FROM kirim_pesan a, balas_pesan b WHERE a.id_kirim=b.id_kirim AND b.dari = '$_SESSION[id_user]' ORDER BY tgl_kirim DESC,jam_kirim DESC");

            foreach ($sql_data as $r ) {

              if($_SESSION['leveluser']=='user_guru') {
                $r2 = mysqli_fetch_array(mysqli_query($koneksi,"SELECT DISTINCT a.nama_lengkap, b.nama_kelas,c.id_kelas FROM siswa a, m_kelas b, f_kelas c WHERE a.nis=c.nis AND b.id_kelas=c.id_kelas AND a.id='$r[ke]' OR a.id='$r[dari]' "));
                $nama = $r2['nama_lengkap'];
                $jab  = $r2['nama_kelas'];
              }
              else {
                $r2 = mysqli_fetch_array(mysqli_query($koneksi,"SELECT DISTINCT nama_lengkap, jabatan FROM guru WHERE id='$r[ke]' OR id='$r[dari]' "));
                $nama = $r2['nama_lengkap'];
                $jab  = $r2['jabatan'];
              }
            ?>
            <tr>
              <td><?= $no;?></td>
              <td><?= $nama.'<br><small><b class="text-primary">'.$jab.'</b></small>';?></td>
              <td><?= '<b class="text-primary">'.$r['judul_pesan'].'</b><br>'.substr($r['isi_pesan'], 0,10);?></td>
              <td><a href="?module=ug_pesan&act=lihat_pesan&id=<?= $r['id_kirim'];?>"><i class="fas fa-eye"></i> Lihat</a></td>
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
