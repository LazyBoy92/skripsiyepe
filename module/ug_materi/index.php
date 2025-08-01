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
  <div class="col-md-12">
    <div class="card shadow">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Daftar Modul / Materi Yang Anda Upload</h6>
           <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <button class="btn-sm btn-primary" data-toggle="modal" data-target="#tambah_data"><i class="fas fa-plus"></i> Tambah Data</button>
                    </a>
          </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="table_1"  width="100%" cellspacing="0" cellpadding="0" style="font-size: 13px;">
            <thead>
              <tr>
                <th>NO</th>
                <th>Judul Materi/Modul</th>
                <th>Mapel</th>
                <th>Kelas</th>
                <th>Jenis File</th>
                <th>Edit</th>
                <th>Hapus</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $sql_data = mysqli_query($koneksi,"SELECT DISTINCT a.*,b.nama_mapel FROM file_materi a, m_mapel b WHERE a.id_mapel=b.id_mapel AND a.pembuat='$_SESSION[id_user]' ORDER BY a.id_file DESC");
                $no=1;
                while($r=mysqli_fetch_array($sql_data)){
                  $file      ='module/files_materi/'.$r[nama_file];
                  $info_file = pathinfo($file);
                  $ext       = $info_file['extension'];
                  
                  if($ext=='doc' or $ext=='docx'){
                    $jenis_file='<i class="fas fa-file-word"><i>';
                  }
                  elseif($ext=='xls' or $ext=='xslx'){
                    $jenis_file='<i class="fas fa-file-excel"><i>';
                  }
                  elseif($ext=='ppt' or $ext=='pptx'){
                    $jenis_file='<i class="fas fa-file-powerpoint"><i>';
                  }
                  elseif($ext=='pdf'){
                    $jenis_file='<i class="fas fa-file-pdf"><i>';
                  }
                  
                  $kelas=mysqli_query($koneksi,"SELECT a.nama_kelas, b.id_kelas FROM m_kelas a, file_materi_det b  WHERE a.id_kelas =b.id_kelas AND  b.id_file ='$r[id_file]'");
              ?>
                <tr>
                    <td><?= $no;?></td>
                    <td><?= $r['judul'];?></td>
                    <td><?= $r['nama_mapel'];?></td>
                    <td>
                      <ol>
                        <?php foreach ($kelas as $kls ) {
                          ?>
                        <li><?= $kls['nama_kelas'];?></li>
                        <?php } ?>
                        
                      </ol>
                    </td>
                    <td align="center"><a href="<?=$file;?>" class="btn-sm btn-info"><?= $jenis_file;?></a></td>
                    <td align="center"><a href="#" class="btn-sm btn-warning" data-toggle="modal" data-target="#edit_<?php echo $r['id_file'];?>"><i class="fas fa-edit"></i></a></td>
                    <td align="center"><a href="javascript:confirmdelete('?module=ug_materi&act=save&post=hapus&id_file=<?php echo $r['id_file'];?>')" class="btn-sm btn-danger"><i class="fas fa-trash"></i></a></td>
                  </tr>

                  <!-- Modal Edit-->
                <div class="modal fade" id="edit_<?php echo $r['id_file'];?>"  role="dialog" >
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                          <h4 class="modal-title" id="myModalLabel">Edit Modul/Materi</h4>
                          </div>
                        <form action="?module=ug_materi&act=save" method="POST" role="form" enctype="multipart/form-data">
                        <div class="modal-body">
                        <?php 
                          $us= $r['id_file'];
                          $edit=mysqli_query($koneksi,"SELECT * FROM file_materi WHERE id_file ='$us'");
                          $ed=mysqli_fetch_array($edit);

                          $mpl=mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_mapel FROM m_mapel WHERE id_mapel='$ed[id_mapel]'"));
                          $sq_mpl=mysqli_query($koneksi,"SELECT DISTINCT a.nama_mapel,b.id_mapel FROM m_mapel a,f_mapel b WHERE a.id_mapel=b.id_mapel AND b.nip='$ed[pembuat]'");
                        ?>
                          <div class="form-group">
                              <label for="">Judul Modul / Materi</label>
                                <input type="hidden" class="form-control" name="id_file" value="<?php echo $ed['id_file'];?>">
                                <input type="text" class="form-control" name="judul" value="<?php echo $ed['judul'];?>">
                           </div>
                           <div class="form-group">
                              <label for="">Mata Pelajaran</label>
                                <select name="id_mapel" class="select2 form-control">
                                  <option value="<?=$ed['id_mapel'];?>"><?=$mpl['nama_mapel'];?></option>
                                  <?php 

                                  while ($rm=mysqli_fetch_array($sq_mpl)) {
                                    echo "<option value='$rm[id_mapel]'>$rm[nama_mapel]</option>";
                                  }

                                   ?>
                                </select>
                           </div>
                           <div class="form-group">
                              <label for="">Kelas</label>
                                <select name="id_kelas[]" class="select2 form-control" multiple="multiple">
                                  <?php 
                                    $kelas1 = mysqli_query($koneksi,"SELECT a.nama_kelas, b.id_kelas FROM m_kelas a, file_materi_det b WHERE a.id_kelas=b.id_kelas AND b.id_file = '$r[id_file]'");
                                    $kelas2 = mysqli_query($koneksi,"SELECT DISTINCT a.nama_kelas, b.id_kelas FROM m_kelas a, f_mapel b WHERE a.id_kelas=b.id_kelas AND b.nip = '$_SESSION[id_user]'");

                                      while($kls=mysqli_fetch_array($kelas1)){
                                  ?>
                                    <option value="<?=$kls['id_kelas'];?>" selected><?=$kls['nama_kelas'];?></option>
                                  
                                  <?php 
                                      }
                                  
                                  while ($kls2=mysqli_fetch_array($kelas2)) {
                                    echo "<option value='$kls2[id_kelas]'>$kls2[nama_kelas]</option>";
                                  }

                                   ?>
                                </select>
                           </div>
                           <div class="form-group">
                              <label for="">Ganti File</label>
                                <input type="file" class="form-control" name="fupload">
                           </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" name="edit_materi" class="btn btn-primary">Update</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div><!--end modal-->
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

<!-- Modal Tambah-->
<div class="modal fade" id="tambah_data"  role="dialog" >
  <div class="modal-dialog modal-lg" role="document">
    <form action="?module=ug_materi&act=save" method="POST" role="form" enctype="multipart/form-data">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Tambah Materi</h4>
        </div>
     
        <div class="modal-body">
        <?php 
          
          $sq_mpl=mysqli_query($koneksi,"SELECT DISTINCT a.nama_mapel,b.id_mapel FROM m_mapel a,f_mapel b WHERE a.id_mapel=b.id_mapel AND b.nip='$_SESSION[id_user]'");
        ?>
          <div class="form-group">
              <label for="">Judul Modul / Materi</label>
                <input type="hidden" class="form-control" name="pembuat" value="<?= $_SESSION['id_user'];?>">
                <input type="text" class="form-control" name="judul" value="" required="required">
           </div>
           <div class="form-group">
              <label for="">Mata Pelajaran</label>
                <select name="id_mapel" class="select2 form-control" required="required">
                  <option value="">--Pilih Mapel--</option>
                  <?php 

                  while ($rm=mysqli_fetch_array($sq_mpl)) {
                    echo "<option value='$rm[id_mapel]'>$rm[nama_mapel]</option>";
                  }

                   ?>
                </select>
           </div>
           <div class="form-group">
              <label for="">Kelas</label>
                <select name="id_kelas[]" class="select2 form-control" multiple="multiple" required="required">
                  <option value="">-- Cari Kelas--</option>
                  <?php 
                    $kelas1 = mysqli_query($koneksi,"SELECT DISTINCT a.nama_kelas, b.id_kelas FROM m_kelas a, f_mapel b WHERE a.id_kelas=b.id_kelas AND b.nip = '$_SESSION[id_user]'");
                  
                  while ($kls=mysqli_fetch_array($kelas1)) {
                    echo "<option value='$kls[id_kelas]'>$kls[nama_kelas]</option>";
                  }

                   ?>
                </select>
           </div>
           <div class="form-group">
              <label for="">File</label>
                <input type="file" class="form-control" name="fupload">
           </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" name="simpan_materi" class="btn btn-primary">Simpan</button>
        </div>      
      
      </div>
    </form>
  </div>
</div><!--end modal-->

<?php 
    }
break;

case 'save':
include 'save.php';

break;
  }
} 
?>
