<script>
function confirmdelete(delUrl) {
if (confirm("Anda yakin ingin menghapus?")) {
document.location = delUrl;
}
}
</script>
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
<!-- Content Row -->
  <div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Kelas Yang di Ampu</div>
              <?php 

                $kls=mysqli_fetch_array(mysqli_query($koneksi,"SELECT DISTINCT id_kelas, COUNT(id_kelas) as jum_kls FROM f_mapel WHERE nip='$_SESSION[id_user]' AND tp='$tahun_p'"));

               ?>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $kls['jum_kls'];?> Kelas</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Siswa Binaan</div>
              <?php 

                $kelas=mysqli_query($koneksi,"SELECT id_kelas FROM f_mapel WHERE nip='$_SESSION[id_user]' AND tp='$tahun_p'");
                //echo "SELECT id_kelas FROM f_mapel WHERE nip='$_SESSION[id_user]' AND tp='$tahun_p'";
                foreach ($kelas as $s ) {

                  $sql_jum=mysqli_fetch_array(mysqli_query($koneksi,"SELECT COUNT(nis) as jum_sis FROM f_kelas WHERE id_kelas='$s[id_kelas]' AND tp='$tahun_p'"));
                  $jum_sis += (int) $sql_jum['jum_sis'];

                }
               ?>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $jum_sis;?> Siswa</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-users fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Mapel yang di Ampu</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <?php 
                    $mpl=mysqli_num_rows(mysqli_query($koneksi,"SELECT DISTINCT id_mapel FROM f_mapel WHERE nip='$_SESSION[id_user]' AND tp='$tahun_p'"));
                   ?>
                  <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $mpl;?> Mapel</div>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Upload Materi/Modul</div>
              <?php 
                  $modul=mysqli_num_rows(mysqli_query($koneksi,"SELECT DISTINCT id_file FROM file_materi WHERE pembuat='$_SESSION[id_user]' AND tgl_posting BETWEEN '$thn_lalu' AND '$thn_skrg'"));
                ?>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $modul;?> Materi</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-book fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<!-- Content Row -->
<div class="row">
  <div class="col-md-12">
    <div class="card shadow">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Mata Pelajaran Yang di Ampu TP : <?= $tahun_p;?> </h6>
           <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <button class="btn-sm btn-primary" data-toggle="modal" data-target="#tambah_data"><i class="fas fa-plus"></i> Tambah Data</button>
                    </a>
          </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="table_1"  width="100%" cellspacing="0" cellpadding="0">
            <thead>
              <tr>
                <td>NO</td>
                <td>Kelas</td>
                <td>Nama Mapel</td>
                <td>Edit</td>
                <td>Hapus</td>
              </tr>
            </thead>
            <tbody>
              <?php 
                $sql_data = mysqli_query($koneksi,"SELECT a.*,b.nama_mapel,c.nama_lengkap,d.nama_kelas FROM f_mapel a, m_mapel b, guru c,m_kelas d WHERE a.id_mapel = b.id_mapel AND a.nip=c.id AND a.id_kelas =d.id_kelas AND c.id='$_SESSION[id_user]' AND tp='$tahun_p' ORDER BY a.id_mapel ASC");
                $no=1;
                while($r=mysqli_fetch_array($sql_data)){
              ?>
                <tr>
                  <td><?php echo $no;?></td>
                  <td><?php echo $r['nama_kelas'];?></td>
                    <td><?php echo $r['nama_mapel'];?></td>
                    <td align="center"><a href="#" class="btn-sm btn-warning" data-toggle="modal" data-target="#edit<?php echo $r['id'];?>"><i class="fas fa-edit"></i></a></td>
                    <td align="center"><a href="javascript:confirmdelete('?module=ug_home&act=save&post=hapus&id=<?php echo $r['id'];?>')" class="btn-sm btn-danger"><i class="fas fa-trash"></i></a></td>
                  </tr>

                  <!-- Modal Edit-->
                <div class="modal fade" id="edit<?php echo $r['id'];?>"  role="dialog" >
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                          <h4 class="modal-title" id="myModalLabel">Edit Master Mata Pelajaran</h4>
                          </div>
                        <form action="?module=ug_home&act=save" method="POST" role="form">
                        <div class="modal-body">
                          <div class="form-group">
                              <label for="">Pilih Kelas</label>
                                <select name="id_kelas" class="select2 form-control" required="required">
                                  <option value="<?= $r['id_kelas']?>"> <?= $r['nama_kelas']?> </option>
                                  <?php 
                                    $kls=mysqli_query($koneksi,"SELECT id_kelas,nama_kelas FROM m_kelas ORDER BY id_kelas ASC ");
                                    while($dkl=mysqli_fetch_array($kls)) {
                                        echo '<option value='.$dkl['id_kelas'].'>'.$dkl['nama_kelas'].'</option>';
                                      }
                                   ?>
                                </select>
                          </div>

                          <div class="form-group">
                              <label for="">Pilih Mata Pelajaran</label>
                                <select name="id_mapel" class="select2 form-control" required="required">
                                  <option value="<?= $r['id_mapel']?>"> <?= $r['nama_mapel']?> </option>
                                  <?php 
                                    $mpl=mysqli_query($koneksi,"SELECT id_mapel,nama_mapel FROM m_mapel ORDER BY id_mapel ASC ");
                                    while($dmp=mysqli_fetch_array($mpl)) {
                                        echo '<option value='.$dmp['id_mapel'].'>'.$dmp['nama_mapel'].'</option>';
                                      }
                                   ?>
                                </select>
                          </div>

                           <div class="form-group">
                              <label for="">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" placeholder="Isikan Deskripsi"><?= $r['deskripsi']?></textarea>
                                <input type="hidden" name="id" value="<?= $r['id']?>">
                           </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" name="update" class="btn btn-primary">Update</button>
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
      <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Guru Mata Pelajaran</h4>
          </div>
        <form action="?module=ug_home&act=save" method="POST" role="form" enctype="multipart/form-data">
        <div class="modal-body">

          <div class="form-group">
              <label for="">Pilih Kelas</label>
                <select name="id_kelas" class="select2 form-control" required="required">
                  <option value=""> Cari Kelas </option>
                  <?php 
                    $kls=mysqli_query($koneksi,"SELECT id_kelas,nama_kelas FROM m_kelas ORDER BY id_kelas ASC ");
                    while($dkl=mysqli_fetch_array($kls)) {
                        echo '<option value='.$dkl['id_kelas'].'>'.$dkl['nama_kelas'].'</option>';
                      }
                   ?>
                </select>
          </div>

          <div class="form-group">
              <label for="">Pilih Mata Pelajaran</label>
                <select name="id_mapel" class="select2 form-control" required="required">
                  <option value=""> Cari Mata Pelajaran </option>
                  <?php 
                    $mpl=mysqli_query($koneksi,"SELECT id_mapel,nama_mapel FROM m_mapel ORDER BY id_mapel ASC ");
                    while($dmp=mysqli_fetch_array($mpl)) {
                        echo '<option value='.$dmp['id_mapel'].'>'.$dmp['nama_mapel'].'</option>';
                      }
                   ?>
                </select>
          </div>

           <div class="form-group">
              <label for="">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" placeholder="Isikan Deskripsi"></textarea>
                <input type="hidden" name="nip" value="<?= $_SESSION['id_user'];?>">
                <input type="hidden" name="tp" value="<?= $tahun_p;?>">
           </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div><!--end modal-->
<?php 
    }

break;
case "save":
include 'save.php';
break;
  }
} 
?>
