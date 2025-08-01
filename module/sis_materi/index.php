<?php
// Skrip Materi Detail - Fix untuk data tidak tampil karena filter tanggal

// Fungsi ukuran file
function fsize($file){
  $a = array("B", "KB", "MB", "GB", "TB", "PB");
  $pos = 0;
  $size = filesize($file);
  while ($size >= 1024) {
    $size /= 1024;
    $pos++;
  }
  return round($size, 2) . " " . $a[$pos];
}

// Cegah akses langsung
if(count(get_included_files())==1){
  echo "<meta http-equiv='refresh' content='0; url=http://$_SERVER[HTTP_HOST]'>";
  exit("Direct access not permitted.");
}

session_start();
if (empty($_SESSION['namauser']) && empty($_SESSION['passuser'])) {
  header('location:../error_login.php');
  exit();
}

$sq_kls = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_kelas FROM f_kelas a, siswa b WHERE a.nis=b.nis AND b.id='$_SESSION[id_user]' AND a.tp='$tahun_p'"));
$id_kelas = $sq_kls['id_kelas'];

switch ($_GET['act']) {

  default:
    if ($_SESSION['leveluser'] == 'user_siswa') {
?>
<div class="row">
  <div class="col-md-12">
    <div class="card shadow">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pilih Materi Sesuai Mata Pelajaran</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="table_1">
            <thead>
              <tr>
                <td>NO</td>
                <td>Mata Pelajaran</td>
                <td>Guru Pengajar</td>
                <td>Lihat Materi</td>
              </tr>
            </thead>
            <tbody>
<?php
  $sql_data = mysqli_query($koneksi, "SELECT DISTINCT a.nama_mapel, b.nama_lengkap, c.id AS id_fmapel FROM m_mapel a, guru b, f_mapel c WHERE a.id_mapel = c.id_mapel AND b.id = c.nip AND c.id_kelas = '$id_kelas' AND c.tp = '$tahun_p' ORDER BY a.nama_mapel ASC");
  $no = 1;
  while ($r = mysqli_fetch_array($sql_data)) {
    echo "<tr>
      <td>$no</td>
      <td>{$r['nama_mapel']}</td>
      <td>{$r['nama_lengkap']}</td>
      <td align='center'><a href='?module=sis_materi&act=detail&id={$r['id_fmapel']}' class='btn-sm btn-info'><i class='fas fa-search'></i></a></td>
    </tr>";
    $no++;
  }
?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
    }
    break;

  case "detail":

    $f_mapel = $_GET['id'];
    $h = mysqli_fetch_array(mysqli_query($koneksi, "SELECT a.nama_mapel, b.nama_lengkap, c.id_mapel, c.nip, c.id_kelas FROM m_mapel a, guru b, f_mapel c WHERE a.id_mapel=c.id_mapel AND b.id = c.nip AND c.id='$f_mapel' AND c.tp='$tahun_p'"));
?>
<div class="row">
  <div class="col-md-12">
    <div class="card-header py-3">
      <table class="table table-striped">
        <tr><td>Mata Pelajaran</td><td>: <?= $h['nama_mapel']; ?></td></tr>
        <tr><td>Guru / Pengajar</td><td>: <?= $h['nama_lengkap']; ?></td></tr>
        <tr><td colspan="2"><a href="?module=sis_materi" class="btn-sm btn-warning"><i class="fas fa-arrow-alt-circle-left"></i> Back</a></td></tr>
      </table>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="card shadow">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="table_1">
            <thead>
              <tr>
                <td>NO</td>
                <td>Judul Materi/Modul</td>
                <td>Download</td>
              </tr>
            </thead>
            <tbody>
<?php
  // Fix query: hapus filter tanggal (jika perlu bisa ditambah lagi nanti)
  $sql_data = mysqli_query($koneksi, "SELECT DISTINCT a.*, b.nama_mapel, c.id_kelas FROM file_materi a, m_mapel b, file_materi_det c WHERE a.id_mapel = b.id_mapel AND a.id_mapel = '$h[id_mapel]' AND a.id_file = c.id_file AND a.pembuat = '$h[nip]' AND c.id_kelas = '$h[id_kelas]' ORDER BY a.judul ASC");
  $no = 1;
  while ($r = mysqli_fetch_array($sql_data)) {
    $file = 'module/files_materi/' . $r['nama_file'];
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    $icon = '<i class="fas fa-file"></i>';
    if ($ext == 'pdf') $icon = '<i class="fas fa-file-pdf"></i>';
    elseif ($ext == 'doc' || $ext == 'docx') $icon = '<i class="fas fa-file-word"></i>';
    elseif ($ext == 'xls' || $ext == 'xlsx') $icon = '<i class="fas fa-file-excel"></i>';
    elseif ($ext == 'ppt' || $ext == 'pptx') $icon = '<i class="fas fa-file-powerpoint"></i>';

    echo "<tr>
      <td>$no</td>
      <td>{$r['judul']}</td>
      <td align='center'><a href='download.php?id={$r['id_file']}' class='btn-sm btn-info'>$icon</a></td>
    </tr>";
    $no++;
  }
?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
    break;
}
?>
