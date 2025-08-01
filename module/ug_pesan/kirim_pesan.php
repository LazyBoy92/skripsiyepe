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
  switch($_GET['act']){
    default:
        if ($_SESSION['leveluser']=='user_guru'){


?>
<div class="row">
  <div class="col-md-10">
    <form method="POST" action="?module=ug_pesan&act=save" enctype="multipart/form-data">
    <div class="card card-solid">
      <div class="card-header with-border">
        <table class="table table-striped">
          <tr>
              <td width="15%">Kirim Ke :</td>
              <td width="85%">
                <?php 
                $sql_kelas = mysqli_query($koneksi,"SELECT a.nama_kelas,b.id_kelas FROM m_kelas a, f_mapel b WHERE a.id_kelas = b.id_kelas AND b.nip='$_SESSION[id_user]'");
                 ?>
                <select name="ke[]" class="select2  form-control" required="required" multiple="multiple" placeholder="Pilih Siswa Binaan">
                <?php 
                
                foreach ($sql_kelas as $r ) {
                  $id_kelas = $r['id_kelas'];
                  $sql_siswa = mysqli_query($koneksi,"SELECT a.id, a.nama_lengkap, b.nis FROM siswa a, f_kelas b WHERE a.nis=b.nis AND b.id_kelas='$id_kelas'");
                  foreach ($sql_siswa as $siswa) {
                    echo '<option value='.$siswa['id'].'>'.$siswa['nama_lengkap'].' ('.$r['nama_kelas'].')'.'</option>';
                  }
                  
                }

                ?>
                </select>
              </td>
          </tr>
          <tr>
            <td>Judul Pesan:</td>
            <td>
              <input type="text" name="judul_pesan" class="form-control" value="" required="required" placeholder="Judul Pesan">
              <input type="hidden" name="dari" class="form-control" value="<?=$_SESSION['id_user'];?>" placeholder="Judul Pesan">
            </td>
          </tr>
        </table>
      </div>
      <div class="card-body no-padding">
        <textarea class="editor_pesan" name="isi_pesan" placeholder="isi pesan"></textarea>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <button type="submit" class="btn btn-primary btn-block" name="kirim_pesan"><i class="fas fa-paper-plane"></i> Kirim Pesan</button>
    </form><p><p>
    <a href="?module=ug_pesan&act=pesan_keluar"><button class="btn btn-warning btn-block"><i class="fas fa-share"></i> Pesan Keluar</button></a>
  </div>

</div>


<?php 
    }

    elseif ($_SESSION['leveluser']=='user_siswa'){


?>
<div class="row">
  <div class="col-md-10">
    <form method="POST" action="?module=ug_pesan&act=save" enctype="multipart/form-data">
    <div class="card card-solid">
      <div class="card-header with-border">
        <table class="table table-striped">
          <tr>
              <td width="15%">Kirim Ke :</td>
              <td width="85%">
                <?php 
                //cari kelas; 
                $kelas  =mysqli_fetch_array(mysqli_query($koneksi,"SELECT id_kelas FROM siswa a, f_kelas b WHERE a.nis=b.nis AND a.id='$_SESSION[id_user]'"));

                $sql_mapel = mysqli_query($koneksi,"SELECT DISTINCT a.nama_mapel,b.id_mapel,b.id_kelas FROM m_mapel a, f_mapel b WHERE a.id_mapel = b.id_mapel AND b.id_kelas='$kelas[id_kelas]'");

                 ?>
                <select name="ke" class="select2  form-control" required="required" placeholder="Pilih Siswa Binaan">
                  <option value="">--Cari Penerima--</option>
                <?php 
                
                foreach ($sql_mapel as $r ) {
                  $id_kelas = $r['id_kelas'];
                  $id_mapel = $r['id_mapel'];
                  $sql_guru = mysqli_query($koneksi,"SELECT DISTINCT a.nama_lengkap, b.nip FROM guru a, f_mapel b WHERE a.id=b.nip AND b.id_kelas ='$id_kelas' AND b.id_mapel='$id_mapel'");
                  
                  
                  foreach ($sql_guru as $r2) {
                    echo '<option value='.$r2['nip'].'>'.$r2['nama_lengkap'].' ('.$r['nama_mapel'].')'.'</option>';
                  }
                  
                }

                ?>
                </select>
              </td>
          </tr>
          <tr>
            <td>Judul Pesan:</td>
            <td>
              <input type="text" name="judul_pesan" class="form-control" value="" required="required" placeholder="Judul Pesan">
              <input type="hidden" name="dari" class="form-control" value="<?=$_SESSION['id_user'];?>" placeholder="Judul Pesan">
            </td>
          </tr>
        </table>
      </div>
      <div class="card-body no-padding">
        <textarea class="editor_pesan" name="isi_pesan" placeholder="isi pesan"></textarea>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <button type="submit" class="btn btn-primary btn-block" name="kirim_pesan_siswa"><i class="fas fa-paper-plane"></i> Kirim Pesan</button>
    </form><p><p>
    <a href="?module=ug_pesan&act=pesan_keluar"><button class="btn btn-warning btn-block"><i class="fas fa-share"></i> Pesan Keluar</button></a>
  </div>

</div>

<?php } 
  }
} 
?>
