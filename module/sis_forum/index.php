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
        if ($_SESSION['leveluser']=='user_siswa'){
        
        $sq_kls=mysqli_fetch_array(mysqli_query($koneksi,"SELECT id_kelas FROM f_kelas a, siswa b WHERE a.nis=b.nis AND b.id='$_SESSION[id_user]' AND a.tp='$tahun_p'"));
        $id_kelas= $sq_kls['id_kelas'];


?>
<div class="row">
  <div class="col-md-12">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
           <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <button class="btn-sm btn-primary" data-toggle="modal" data-target="#tambah_data"><i class="fas fa-post"></i> Posting Pembahasan</button>

                    </a>
          </div>
    </div>
  </div>
</div>

<div class="row">
<?php 
  $sql_data="SELECT DISTINCT a.*,b.id_mapel FROM topik_forum a, f_mapel b WHERE a.id_mapel=b.id_mapel AND b.id_kelas='$id_kelas' AND b.tp = '$tahun_p' AND a.tgl_post BETWEEN '$thn_lalu' AND '$thn_skrg' ORDER BY a.id DESC";
  //echo "SELECT DISTINCT a.*,b.id_mapel FROM topik_forum a, f_mapel b WHERE a.id_mapel=b.id_mapel AND b.id_kelas='$id_kelas' AND b.tp = '$tahun_p' AND a.tgl_post BETWEEN '$thn_lalu' AND '$thn_skrg' ORDER BY a.id DESC";
  $forum=mysqli_query($koneksi,$sql_data);
  
 ?>
  <div class="col-sm-12">
    <div class="card shadow">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <small class="text-primary"></small>
      </div>
      <div class="card-body">
        <table class="table table-striped" id="table_1">
          <thead style="font-size: 11px;">
            <tr><th width="3%">NO</th><th colspan="2" width="70%">Topik Pembahasan</th><th width="20%">Kategori</th><th width="7%">Aksi</th></tr>
          </thead>
          <tbody>
            <?php 
            $no = 1;
            foreach ($forum as $r ) { 
              $mpl=mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_mapel FROM m_mapel WHERE id_mapel='$r[id_mapel]'"));
              $kat=mysqli_fetch_array(mysqli_query($koneksi,"SELECT nm_kategori FROM kat_forum WHERE idkategori='$r[id_kat]'"));
              $cmnt = mysqli_num_rows(mysqli_query($koneksi,"SELECT id FROM komentar WHERE id_topik='$r[id]'"));
              

              $cek_pembuat=mysqli_num_rows(mysqli_query($koneksi,"SELECT id FROM guru WHERE id='$r[id_user]'"));

              if($cek_pembuat!=0){

                $prsn = mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_lengkap,jabatan FROM guru WHERE id='$r[id_user]'"));
                $nama= $prsn['nama_lengkap'];
                $jabatan = $prsn['jabatan'];
              }
              else {
                $prsn = mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_lengkap FROM siswa WHERE id='$r[id_user]'"));
                $nama= $prsn['nama_lengkap'];
                $jabatan = 'Siswa';
              }
              
            ?>
            <tr>
              <td align="center" style="font-size: 12px;"><?=$no;?></td>
              <td align="center"> <img src="module/foto_forum/medium_<?= $r['gambar'];?>" width="120"> </td>
              <td>
                <h5 class="font-weight-bold text-primary"><b><small>[<?= $kat['nm_kategori'];?>] </small></b> <a href="?module=sis_forum&act=read&id=<?= $r['id'];?>"> <?=$r['judul_topik'];?></a></h5>
                <small><i class="text-danger fas fa-person"></i> Oleh : <?= $nama;?> (<?= $jabatan;?>)        <span class="font-weight-bold text-danger">Tanggal Post : <?= tgl_indo($r['tgl_post']);?></span></small><br>
                <small><i class="text-info fas fa-comment"></i>  <?= $cmnt;?> Komentar</small><br>
                
              </td>
              <td><i class="text-dark" style="font-size: 13px;"><?=$mpl['nama_mapel'];?></i><br></td>
              <td><a href="?module=sis_forum&act=read&id=<?= $r['id'];?>" class="btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
            </tr>
            <?php $no++; } ?>
          </tbody>
        </table>
      </div>
      <div class="card-footer">
        <div class="row">
          <div class="col-sm-6 text-left">
            
          </div>
          <div class="col-sm-6 text-right">

          </div>
        
        </div>
      </div>
    </div>
  </div>

</div>
<!-- Modal Tambah-->
<div class="modal fade" id="tambah_data"  role="dialog" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Topik Pembahasan Baru</h4>
        </div>
      <form action="?module=sis_forum&act=save" method="POST" role="form" enctype="multipart/form-data">
        <div class="modal-body">
        <?php 
          
          $sq_mpl=mysqli_query($koneksi,"SELECT DISTINCT a.nama_mapel,b.id_mapel FROM m_mapel a,f_mapel b WHERE a.id_mapel=b.id_mapel AND b.id_kelas='$id_kelas' AND b.tp='$tahun_p'");
        ?>
          <div class="form-group">
              <label for="">Judul Topik Pembahasan</label>
                <input type="hidden" class="form-control" name="id_user" value="<?= $_SESSION['id_user'];?>">
                <input type="text" class="form-control" name="judul_topik" value="" required="required">
           </div>
           <div class="form-group">
              <label for="">Kategori</label>
                <select name="id_kat" class="form-control" required="required">
                  <option value="">--Pilih Kategori</option>
                  <option value="1">Tanya</option>
                  <option value="2">Berbagi</option>
                  <option value="3">Pembahasan</option>
                </select>
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
              <label for="">Gambar Posting</label>
                <input type="file" class="form-control" name="fupload">
           </div>

           <div class="form-group">
              <label for="">Isi Bahasan</label>
                <textarea class="forum_text" name="isi_topik"  placeholder="Place some text here"></textarea>
           </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" name="simpan_forum" class="btn btn-primary">Simpan</button>
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

case 'read':
$id_topik = $_GET['id'];

$s = mysqli_fetch_array(mysqli_query($koneksi,"SELECT a.*, b.nama_mapel, c.nm_kategori FROM topik_forum a, m_mapel b, kat_forum c WHERE a.id_mapel=b.id_mapel AND a.id_kat=c.idkategori AND a.id ='$id_topik' "));

$cek_pembuat=mysqli_num_rows(mysqli_query($koneksi,"SELECT id FROM guru WHERE id='$s[id_user]'"));

              if($cek_pembuat!=0){

                $prsn = mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_lengkap,jabatan,foto FROM guru WHERE id='$s[id_user]'"));
                $nama    = $prsn['nama_lengkap'];
                $jabatan = $prsn['jabatan'];
                $foto    = '<img src="module/foto_pengajar/medium_'.$prsn[foto].'" width="50%">';
              }
              else {
                $prsn = mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_lengkap,foto FROM siswa WHERE id='$s[id_user]'"));
                $nama    = $prsn['nama_lengkap'];
                $jabatan = 'Siswa';
                $foto    = '<img src="module/foto_siswa/medium_'.$prsn['foto'].'" width="50%">';
              }
?>
<div class="row">
  <div class="col-md-12">
    <div class="card shadow">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h5 class="m-0 font-weight-bold text-primary"><b><small>[<?= $s['nm_kategori'];?>] </small></b><?=$s['judul_topik'];?></h5>
        <div class="dropdown no-arrow">
          <?php if ($s['id_user']==$_SESSION['id_user']) { ?>
            <a href="#" class="btn-sm btn-warning" data-toggle="modal" data-target="#edit_data"><i class="fas fa-edit"></i> Edit</a>
            <a href="javascript:confirmdelete('?module=sis_forum&act=save&post=hapus_topik&id=<?php echo $s['id'];?>')" class="btn-sm btn-danger"><i class="fas fa-trash"></i></a>   
            <?php } else {} ?> 
            <a href="?module=sis_forum" class="btn-sm btn-secondary"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>  
        </div>
      </div>
    </div>
  </div>
</div>
<br>
<div class="row">
  <div class="col-md-8">
    <div class="card shadow">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Isi Pembahasan</h6>
      </div>
      
      <div class="card-body">
        <img src="module/foto_forum/medium_<?= $s['gambar'];?>">
        <p class="py-3 d-flex flex-row align-items-center justify-content-between">
          <?= $s['isi_topik'];?>
        </p>
      </div>
      <div class="card-footer">
        <p class="py-3 d-flex flex-row align-items-center justify-content-between">
          <small class="text-primary"> Kategori Mata Pelajaran : <?= $s['nama_mapel'];?></small>
        </p>
      </div>
    </div>
  </div>

  <?php 
  $pm 
   ?>
  <div class="col-md-4">
    <div class="card shadow">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Pembuat Posting</h6>
      </div>
      <div class="card-body" >
        <table >
          <tr><td align="center"><?= $foto;?></td></tr>
          <tr><td align="center"><?= $nama;?></td></tr>
          <tr><td align="center"><?= $jabatan;?></td></tr>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- Modal Edit-->
<div class="modal fade" id="edit_data"  role="dialog" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Topik Pembahasan Baru</h4>
        </div>
      <form action="?module=sis_forum&act=save" method="POST" role="form" enctype="multipart/form-data">
        <div class="modal-body">
        <?php 
          
          $sq_mpl=mysqli_query($koneksi,"SELECT DISTINCT a.nama_mapel,b.id_mapel FROM m_mapel a,f_mapel b WHERE a.id_mapel=b.id_mapel AND b.nip='$_SESSION[id_user]'");
        ?>
          <div class="form-group">
              <label for="">Judul Topik Pembahasan</label>
                <input type="hidden" class="form-control" name="id" value="<?=$s['id'];?>">
                <input type="text" class="form-control" name="judul_topik" value="<?=$s['judul_topik'];?>" required="required">
           </div>
           <div class="form-group">
              <label for="">Kategori</label>
                <select name="id_kat" class="form-control" required="required">
                  <option value="<?=$s['id_kat'];?>"><?=$s['nm_kategori'];?></option>
                  <option value="1">Tanya</option>
                  <option value="2">Berbagi</option>
                  <option value="3">Pembahasan</option>
                </select>
           </div>
           <div class="form-group">
              <label for="">Mata Pelajaran</label>
                <select name="id_mapel" class="select2 form-control" required="required">
                  <option value="<?=$s['id_mapel'];?>"><?=$s['nama_mapel'];?></option>
                  <?php 

                  while ($rm=mysqli_fetch_array($sq_mpl)) {
                    echo "<option value='$rm[id_mapel]'>$rm[nama_mapel]</option>";
                  }

                   ?>
                </select>
           </div>
           <div class="form-group">
              <label for="">Gambar Posting</label>
                <input type="file" class="form-control" name="fupload">
           </div>

           <div class="form-group">
              <label for="">Isi Bahasan</label>
                <textarea class="forum-text" name="isi_topik"  placeholder="Place some text here"><?=$s['isi_topik'];?></textarea>
           </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" name="edit_forum" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div><!--end modal-->
<p>
<div class="row">
  <div class="col-md-8">
    <div class="card mb-4 py-3 border-bottom-primary bg-light">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h5 class="m-0 font-weight-bold text-primary">Komentar</h5>
        <div class="dropdown no-arrow"> 
            <a href="#" class="btn-sm btn-primary" data-toggle="modal" data-target="#post_komen" title="Beri Komentar"><i class="fas fa-comment-medical"></i></a> 
        </div>
      </div>
      <div class="card-body">
        <table class="table table-striped">
          <?php 
          $sql_komen = mysqli_query($koneksi,"SELECT * FROM komentar WHERE id_topik = '$id_topik' ");
          foreach ($sql_komen as $kom ) {
            
            $cek_yangkomen=mysqli_num_rows(mysqli_query($koneksi,"SELECT id FROM guru WHERE id='$kom[id_user]'"));
            //echo $cek_yangkomen;
            
            if($cek_yangkomen!=0){
                $org = mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_lengkap,jabatan,foto FROM guru WHERE id='$kom[id_user]'"));
                $nama_org    = $org['nama_lengkap'];
                $jabatan_org = $org['jabatan'];
                $foto_org    = '<img src="module/foto_pengajar/medium_'.$org[foto].'" width="60">';
              }
              else {
                $org = mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_lengkap,foto FROM siswa WHERE id='$kom[id_user]'"));
                $nama_org    = $org['nama_lengkap'];
                $jabatan_org = 'Siswa';
                $foto_org    = '<img src="module/foto_siswa/medium_'.$org[foto].'" width="60">';
              }
              //echo "SELECT nama_lengkap,foto FROM siswa WHERE id='$kom[id_user]'";
    
          ?>
                  <tr>
                    <td width="40"><?=$foto_org;?></td>
                    <td><span class="text-primary"><?= $nama_org.' <small>('.$jabatan_org.')</small>';?></span><br><small><?= tgl_indo($kom['tgl_post_komentar']);?></small><br><?= $kom['isi_komentar'];?>
                    </td>
                    <td class="text-right"><?php if ($kom['id_user']==$_SESSION['id_user']) { ?>
                        <a href="#" class="btn-sm btn-warning" data-toggle="modal" data-target="#edit_komen<?=$kom['id'];?>" title="Edit Komentar"><i class="fas fa-edit"></i></a> 
                        <a href="javascript:confirmdelete('?module=sis_forum&act=save&post=hapus_komentar&id=<?= $kom['id'];?>&id_topik=<?=$id_topik;?>')" class="btn-sm btn-danger"><i class="fas fa-trash"></i></a>    
                        <?php } else {} ?> </td>
                  </tr>
                  <!-- Edit Komentar-->
                        <div class="modal fade" id="edit_komen<?=$kom['id'];?>"  role="dialog" >
                          <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">Tambahkan Komentar</h4>
                                </div>
                              <form action="?module=sis_forum&act=save" method="POST" role="form" enctype="multipart/form-data">
                                <div class="modal-body">
                                <?php 
                                  
                                  $r_kom=mysqli_fetch_array(mysqli_query($koneksi,"SELECT id,isi_komentar FROM komentar WHERE id='$kom[id]'"));
                                ?>
                                  
                                   <div class="form-group">
                                    <input type="text" name="id" value="<?=$r_kom['id'];?>">
                                    <input type="text" name="id_topik" value="<?=$id_topik;?>">
                                      <label for="">Isi Komentar</label>
                                        <textarea class="forum-text" name="isi_komentar"  placeholder="Place some text here"><?= $r_kom['isi_komentar'];?></textarea>
                                   </div>
                                  
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  <button type="submit" name="edit_komen" class="btn btn-primary">Simpan</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div><!--end modal-->
          <?php
          }
           ?>
        </table> 
      </div>
    </div>
  </div>
</div>

<!-- Tambah Komentar-->
<div class="modal fade" id="post_komen"  role="dialog" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Tambahkan Komentar</h4>
        </div>
      <form action="?module=sis_forum&act=save" method="POST" role="form" enctype="multipart/form-data">
        <div class="modal-body">
        <?php 
          
          $sq_mpl=mysqli_query($koneksi,"SELECT DISTINCT a.nama_mapel,b.id_mapel FROM m_mapel a,f_mapel b WHERE a.id_mapel=b.id_mapel AND b.nip='$_SESSION[id_user]'");
        ?>
          
           <div class="form-group">
            <input type="hidden" name="id_topik" value="<?= $id_topik;?>">
            <input type="hidden" name="id_user" value="<?= $_SESSION['id_user'];?>">
              <label for="">Isi Komentar</label>
                <textarea class="forum-text" name="isi_komentar"  placeholder="Place some text here"></textarea>
           </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" name="simpan_komen" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div><!--end modal-->

<?php
break;
  }
} 
?>
