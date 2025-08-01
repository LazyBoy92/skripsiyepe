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
  if ($_SESSION['leveluser']=='user_guru'){


?>
<div class="row">
  <div class="col-md-12">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Soal Essay yang anda buat</h6>
       <div class="dropdown no-arrow">
          <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <button class="btn-sm btn-primary" data-toggle="modal" data-target="#tambah_soal"><i class="fas fa-plus"></i> Add Soal </button>
            <button class="btn-sm btn-info" data-toggle="modal" data-target="#upload_esay"><i class="fas fa-upload"></i> Upload Excel </button>
            <a href="module/ug_bank/bank_essay.xls" target="_blank"><button class="btn-sm btn-success"><i class="fas fa-file-excel"></i> Contoh Excel </button></a>
          </a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card card-solid">
      <div class="card-header with-border">
      </div>
      <div class="card-body" style="font-size: 13px;">
        <div class="table-responsive">
          <table class="table table-striped" id="table_1">
            <thead>
              <tr><th width="5%">No</th><th width="55%">Soal</th><th width="34">Mapel</th><th width="3%"><i class="fas fa-edit"></i></th><th width="3%"><i class="fas fa-trash"></i></th></tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $sql_data=mysqli_query($koneksi,"SELECT * FROM bank_esay WHERE pembuat = '$_SESSION[id_user]' ORDER BY id DESC");
                //echo "SELECT * FROM bank_pilganda WHERE pembuat = '$_SESSION[id_user]'";
              foreach ($sql_data as $r ) {
                $d=mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_mapel FROM m_mapel WHERE id_mapel = '$r[id_mapel]'")); 
              ?>
              <tr>
                <td><?= $no;?></td>
                <td><?= $r['pertanyaan'];?></td>
                <td><?= $d['nama_mapel'];?></td>
                <td><a href="#" data-toggle="modal" data-target="#edit_<?=$r['id'];?>"><i class="fas fa-edit"></i></a></td>
                <td><a href="javascript:confirmdelete('?module=ug_bank&act=save&post=hapus_esay&id=<?=$r['id'];?>')"><i class="fas fa-trash"></i></a></td>
              </tr>

              <!-- Modal Add Soal Essay-->
              <div class="modal fade" id="edit_<?=$r['id'];?>"  role="dialog" >
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Edit Soal Essay</h4>
                    </div>
                    <form action="?module=ug_bank&act=save" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                      <div class="form-group">
                        <label for="">Pertanyaan</label>
                          <input type="hidden" class="form-control" name="id" value="<?= $r['id'];?>">
                          <textarea name="pertanyaan" cols="75" rows="3" class="editor"><?= $r['pertanyaan'];?></textarea>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                      <button type="submit" name="update_essay" class="btn btn-primary">Simpan</button>
                    </div>
                  </form>
                </div>
              </div>
              </div><!--end modal-->

              <?php } ?>
            </tbody>
            <tfoot>
              <tr><th width="5%">No</th><th width="55%">Soal</th><th width="34">Mapel</th><th width="3%"><i class="fas fa-edit"></i></th><th width="3%"><i class="fas fa-trash"></i></th></tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Add Soal Essay-->
<div class="modal fade" id="tambah_soal"  role="dialog" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Tambah Soal Essay</h4>
        </div>
      <form action="?module=ug_bank&act=save" method="POST" enctype="multipart/form-data">
      <div class="modal-body">
        <?php
        $sql_mapel2 = mysqli_query($koneksi,"SELECT DISTINCT id_mapel FROM f_mapel WHERE nip = '$_SESSION[id_user]'"); 
        ?>
        <div class="form-group">
          <label>Mata Pelajaran</label>
            <select name="id_mapel" class="select2 form-control" required="required">
              <option value="">--Pilih Mata Pelajaran--</option>
              <?php 

                foreach ($sql_mapel2 as $d2) {
                $nm_mapel2 = mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_mapel FROM m_mapel WHERE id_mapel='$d2[id_mapel]'"));
                echo '<option value="'.$d2['id_mapel'].'">'.$nm_mapel2['nama_mapel'].'</option>';
              }

               ?>
            </select>
            <input type="hidden" class="form-control" name="pembuat" value="<?= $_SESSION['id_user'];?>">
        </div>
        <div class="form-group">
          <label for="">Pertanyaan</label>
            <textarea name="pertanyaan" cols="75" rows="3" class="editor"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
        <button type="submit" name="simpan_essay" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
</div><!--end modal-->

<!-- Modal Upload Excel Essay-->
<div class="modal fade" id="upload_esay"  role="dialog" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Tambahkan ke Bank Soal Essay</h4>
        </div>
      <form action="?module=ug_bank&act=save" method="POST" enctype="multipart/form-data">
      <div class="modal-body">
        <?php
        $sql_mapel2 = mysqli_query($koneksi,"SELECT DISTINCT id_mapel FROM f_mapel WHERE nip = '$_SESSION[id_user]'"); 
        ?>
        <div class="form-group">
          <label>Mata Pelajaran</label>
            <select name="id_mapel" class="select2 form-control" required="required">
              <option value="">--Pilih Mata Pelajaran--</option>
              <?php 

                foreach ($sql_mapel2 as $d2) {
                $nm_mapel2 = mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_mapel FROM m_mapel WHERE id_mapel='$d2[id_mapel]'"));
                echo '<option value="'.$d2['id_mapel'].'">'.$nm_mapel2['nama_mapel'].'</option>';
              }

               ?>
            </select>
            <input type="hidden" class="form-control" name="pembuat" value="<?= $_SESSION['id_user'];?>">
        </div>
        <div class="form-group">
          <label>Upload File Excel</label>
          <input type="file" name="file" class="form-control" required="required">     
        </div>
        Keterangan : 
        <small>
        <ul>
          <li>Harap Download File Template Soalnya </li>
          <li>Tulis Pertanyaan dan jawabannya pada template </li>
          <li>Upload Soal Excel Tidak Bisa memasukan Soal Gambar dan Rumus</li>
        </ul>
        </small> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
        <button type="submit" name="upload_esay" class="btn btn-primary">Proses</button>
      </div>
    </form>
  </div>
</div>
</div><!--end modal-->


<?php 
  }
} 
?>
