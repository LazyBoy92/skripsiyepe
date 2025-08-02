<?php
if (count(get_included_files()) == 1) {
    exit("Direct access not permitted.");
}

error_reporting(0);
session_start();
if (empty($_SESSION['namauser']) && empty($_SESSION['passuser'])) {
    header('location:../error_login.php');
    exit();
} else {
    switch ($_GET['act']) {
        default:
            if ($_SESSION['leveluser'] == 'user_siswa') {
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card bg-deafult shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Ujian</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="table_1" width="100%" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <td>No</td>
                                <td>Nama Ujian</td>
                                <td>Mapel</td>
                                <td>Pengajar</td>
                                <td>Waktu</td>
                                <td>Kerjakan</td>
                            </tr>
                        </thead>
                        <tbody>
<?php
$no = 1;
$nis = mysqli_fetch_array(mysqli_query($koneksi, "SELECT nis FROM siswa WHERE id='{$_SESSION['id_user']}'"));
$get_kelas = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_kelas FROM f_kelas WHERE nis='{$nis['nis']}' AND tp='$tahun_p'"));
$id_kelas_siswa = $get_kelas['id_kelas'];

$sql_data = mysqli_query($koneksi, "
    SELECT a.*, b.nama_mapel, c.nama_lengkap 
    FROM topik_ujian a
    JOIN m_mapel b ON a.id_mapel = b.id_mapel
    JOIN guru c ON a.pembuat = c.id
    JOIN kelas_ujian d ON a.id = d.id_topik
    WHERE a.terbit = 'Y'
      AND d.id_kelas = '$id_kelas_siswa'
    ORDER BY a.id DESC
");

if (mysqli_num_rows($sql_data) == 0) {
    echo "<tr><td colspan='6' align='center'>Tidak ada ujian untuk kelas Anda.</td></tr>";
} else {
    while ($r = mysqli_fetch_array($sql_data)) {
?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= $r['judul']; ?></td>
        <td><?= $r['nama_mapel']; ?></td>
        <td><?= $r['nama_lengkap']; ?></td>
        <td><?= $r['waktu_pengerjaan'] / 60; ?> Menit</td>
        <td align="center">
            <a href="?module=ujian_online&pmb=<?= $r['pembuat']; ?>&topik=<?= $r['id']; ?>" class="btn-sm btn-primary">
                <i class="fas fa-angle-double-right"></i>
            </a>
        </td>
    </tr>
<?php
    }
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
    }
}
?>
