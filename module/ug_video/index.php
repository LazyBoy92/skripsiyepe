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
  <div class="col-md-12">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Daftar Video Yang Anda Upload</h6>
           <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <button class="btn-sm btn-primary" data-toggle="modal" data-target="#tambah_data"><i class="fas fa-plus"></i> Upload dari lokal</button>

                      <button class="btn-sm btn-danger" data-toggle="modal" data-target="#tambah_data2"><i class="fas fa-plus"></i> Upload dari Youtube</button>
                    </a>
          </div>
    </div>
  </div>
</div>

<div class="row">
<?php
  $p      = new Paging;
  $batas  = 6;
  $posisi = $p->cariPosisi($batas); 
  $sql_data="SELECT * FROM file_video WHERE pembuat='$_SESSION[id_user]' ORDER BY id_video DESC LIMIT $posisi,$batas";
  //$sql_data="SELECT * FROM file_video ORDER BY id_video DESC LIMIT $posisi,$batas";

  $video=mysqli_query($koneksi,$sql_data);

  $jmldata     = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM file_video WHERE pembuat='$_SESSION[id_user]' ORDER BY id_video DESC"));
  $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
  $linkHalaman = $p->navHalaman(anti_injection($_GET['hal']), $jmlhalaman);

  //$j=mysqli_num_rows(mysqli_query($koneksi,$sql_data));
  //$video=mysqli_query($koneksi,$sql_data);
  //echo $j;
  foreach ($video as $r ) 
    { 
    
    if(empty($r['nama_video'])){
      $video = $r['youtube'];
    }
    else{
      $video = 'module/files_video/'.$r[nama_video];
    }

    $sql_kelas = mysqli_query($koneksi,"SELECT a.nama_kelas, b.id_kelas FROM m_kelas a, file_video_det b WHERE a.id_kelas = b.id_kelas AND b.id_video='$r[id_video]'");
    
 ?>
  <div class="col-md-4 mt-4">
    <div class="card shadow">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary"><?= $r['judul'];?></h6>
      </div>
      <div class="card-body">
        <div class="embed-responsive embed-responsive-16by9">
          <iframe class="embed-responsive-item" src="<?= $video;?>" allowfullscreen></iframe>
        </div><br>
        <div class="flex-row align-items-center justify-content-between">
          Dilihat Oleh : 
          <ol>
          <?php 
            foreach ($sql_kelas as $kls ) {
              echo '<li>'.$kls['nama_kelas'].'</li>';
            }
           ?>
           </ol>
        </div>
      </div>
      <div class="card-footer">
        <a href="?module=ug_video&act=lihat&id=<?=$r['id_video'];?>" class="btn-sm btn-primary"> View</a>
        <a href="#" data-toggle="modal" data-target="#edit_<?=$r['id_video'];?>" class="btn-sm btn-warning"> Edit</a>
        <a href="javascript:confirmdelete('?module=ug_video&act=save&post=hapus&id_video=<?php echo $r['id_video'];?>')" class="btn-sm btn-danger"><i class="fas fa-trash"></i></a>
      </div>
    </div>
  </div>

  <!-- Modal Edit-->
  <div class="modal fade" id="edit_<?php echo $r['id_video'];?>"  role="dialog" >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Edit Video</h4>
            </div>
          <form action="?module=ug_video&act=save" method="POST" role="form" enctype="multipart/form-data">
          <div class="modal-body">
          <?php 
            $us= $r['id_video'];
            $edit=mysqli_query($koneksi,"SELECT * FROM file_video WHERE id_video ='$us'");
            $ed=mysqli_fetch_array($edit);

            $mpl=mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_mapel FROM m_mapel WHERE id_mapel='$ed[id_mapel]'"));
            $sq_mpl=mysqli_query($koneksi,"SELECT DISTINCT a.nama_mapel,b.id_mapel FROM m_mapel a,f_mapel b WHERE a.id_mapel=b.id_mapel AND b.nip='$ed[pembuat]'");
          ?>
            <div class="form-group">
                <label for="">Judul Modul / Materi</label>
                  <input type="hidden" class="form-control" name="id_video" value="<?php echo $ed['id_video'];?>">
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
                      $kelas1 = mysqli_query($koneksi,"SELECT a.nama_kelas, b.id_kelas FROM m_kelas a, file_video_det b WHERE a.id_kelas=b.id_kelas AND b.id_video = '$r[id_video]'");
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

             <?php if(!empty($ed['nama_video'])) { ?>
             <div class="form-group">
                <label for="">Ganti File</label>
                  <input type="file" class="form-control" name="fupload">
             </div>
             <?php } else { ?>
             <div class="form-group">
              <label for="">Video dari Youtube</label>
                <input type="text" class="form-control" name="youtube" value="<?php echo $ed['youtube'];?>" placeholder="Sisipkan Link Youtube Ex:" required="required">
              </div>
          <?php } ?>
            <div class="form-group">
              <label for="">Keterangan Video Video</label>
                <textarea class="editor" name="keterangan"  placeholder="Keterangan"><?php echo $ed['keterangan'];?></textarea>
           </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" name="edit_video" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div><!--end modal-->

<?php } ?>

</div>

<hr class="divider">
<?= $linkHalaman;?>

<!-- Modal Tambah-->
<div class="modal fade" id="tambah_data"  role="dialog" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Tambah Viedo Interaktif</h4>
        </div>
      <form action="?module=ug_video&act=save" method="POST" role="form" enctype="multipart/form-data">
        <div class="modal-body">
        <?php 
          
          $sq_mpl=mysqli_query($koneksi,"SELECT DISTINCT a.nama_mapel,b.id_mapel FROM m_mapel a,f_mapel b WHERE a.id_mapel=b.id_mapel AND b.nip='$_SESSION[id_user]'");
        ?>
          <div class="form-group">
              <label for="">Judul Video</label>
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
              <label for="">Upload Video</label>
                <input type="file" class="form-control" name="fupload">
           </div>
           <div class="form-group">
              <label for="">Keterangan Video Video</label>
                <textarea class="editor"  name="keterangan" placeholder="keterangan"></textarea>
           </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" name="simpan_video" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div><!--end modal-->

<!-- Modal Tambah-->
<div class="modal fade" id="tambah_data2"  role="dialog" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Masukan dari Youtube</h4>
        </div>
      <form action="?module=ug_video&act=save" method="POST" role="form" enctype="multipart/form-data">
        <div class="modal-body">
        <?php 
          
          $sq_mpl=mysqli_query($koneksi,"SELECT DISTINCT a.nama_mapel,b.id_mapel FROM m_mapel a,f_mapel b WHERE a.id_mapel=b.id_mapel AND b.nip='$_SESSION[id_user]'");
        ?>
          <div class="form-group">
              <label for="">Judul Video</label>
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
              <label for="">Video dari Youtube</label>
                <input type="text" class="form-control" name="youtube" value="" placeholder="Sisipkan Link Youtube Ex:" required="required">
           </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" name="simpan_youtube" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
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
