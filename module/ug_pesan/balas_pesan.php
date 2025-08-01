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

          $id_pesan     = $_GET['id'];
          $dt_psn       = mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM kirim_pesan WHERE id_kirim='$id_pesan'"));
          //CARI USER
          $sql_getdata  = mysqli_query($koneksi,"SELECT id, nama_lengkap FROM siswa WHERE id='$dt_psn[ke]' OR id ='$dt_psn[dari]'");
          $dt           = mysqli_fetch_array($sql_getdata);
          $num          = mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM balas_pesan WHERE id_kirim='$id_pesan'"));
          $bls_ke       = (int) $num + 1;
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
                            <input type="hidden" name="id_kirim" value="<?=$id_pesan;?>">
                            <input type="hidden" name="ke" value="<?=$dt['id'];?>">
                            <input type="text" name="nama_lengkap" value="<?=$dt['nama_lengkap'];?>" class="form-control" readonly="readonly">
                          </td>
                      </tr>
                      <tr>
                        <td>Judul Pesan:</td>
                        <td>
                          <input type="text" name="judul_balas" class="form-control" value="Re-<?=$bls_ke;?> <?=$dt_psn['judul_pesan'];?>" readonly="readonly" placeholder="Judul Pesan">
                          <input type="hidden" name="dari" class="form-control" value="<?=$_SESSION['id_user'];?>" placeholder="Judul Pesan">
                        </td>
                      </tr>
                    </table>
                  </div>
                  <div class="card-body no-padding">
                    <label>Isi Balasan..</label>
                    <textarea class="editor_pesan" name="isi_balas" placeholder="isi pesan"></textarea>
                  </div>
                </div>
              </div>

              <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-block" name="balas_pesan"><i class="fas fa-reply"></i> Balas Pesan</button>
              </form><p><p>
                <a href="?module=ug_pesan&act=pesan_keluar"><button class="btn btn-warning btn-block"><i class="fas fa-share"></i> Pesan Keluar</button></a>
              </div>

            </div>


<?php
  }
  elseif ($_SESSION['leveluser']=='user_siswa'){

          $id_pesan     = $_GET['id'];
          $dt_psn       = mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM kirim_pesan WHERE id_kirim='$id_pesan'"));
          //CARI USER
          $sql_getdata  = mysqli_query($koneksi,"SELECT id,nama_lengkap FROM guru WHERE id ='$dt_psn[ke]' OR id='$dt_psn[dari]'");
          $dt           = mysqli_fetch_array($sql_getdata);
          $num          = mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM balas_pesan WHERE id_kirim='$id_pesan'"));
          $bls_ke       = (int) $num + 1;
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
                            <input type="hidden" name="id_kirim" value="<?=$id_pesan;?>">
                            <input type="hidden" name="ke" value="<?=$dt['id'];?>">
                            <input type="text" name="nama_lengkap" value="<?=$dt['nama_lengkap'];?>" class="form-control" readonly="readonly">
                          </td>
                      </tr>
                      <tr>
                        <td>Judul Pesan:</td>
                        <td>
                          <input type="text" name="judul_balas" class="form-control" value="Re-<?=$bls_ke;?> <?=$dt_psn['judul_pesan'];?>" readonly="readonly" placeholder="Judul Pesan">
                          <input type="hidden" name="dari" class="form-control" value="<?=$_SESSION['id_user'];?>" placeholder="Judul Pesan">
                        </td>
                      </tr>
                    </table>
                  </div>
                  <div class="card-body no-padding">
                    <label>Isi Balasan..</label>
                    <textarea class="editor_pesan" name="isi_balas" placeholder="isi pesan"></textarea>
                  </div>
                </div>
              </div>

              <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-block" name="balas_pesan"><i class="fas fa-reply"></i> Balas Pesan</button>
              </form><p><p>
                <a href="?module=ug_pesan&act=pesan_keluar"><button class="btn btn-warning btn-block"><i class="fas fa-share"></i> Pesan Keluar</button></a>
              </div>

            </div>
<?php  
    }

  }
} 
?>
