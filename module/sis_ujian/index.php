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
        <div class="card bg-default shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Ujian</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="table_1" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Ujian</th>
                                <th>Mapel</th>
                                <th>Pengajar</th>
                                <th>Waktu</th>
                                <th>Kerjakan</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
$no = 1;

// Ambil NIS siswa
$nis_data = mysqli_fetch_array(mysqli_query($koneksi, "SELECT nis FROM siswa WHERE id='{$_SESSION['id_user']}'"));
$nis_siswa = $nis_data['nis'];

// Ambil daftar ujian sesuai kelas siswa
$sql_data = mysqli_query($koneksi, "
    SELECT DISTINCT a.id, a.judul, b.nama_mapel, c.nama_lengkap, a.waktu_pengerjaan, a.pembuat
    FROM topik_ujian a
    JOIN m_mapel b ON a.id_mapel = b.id_mapel
    JOIN guru c ON a.pembuat = c.id
    JOIN kelas_ujian d ON a.id = d.id_topik
    JOIN f_kelas fk ON d.id_kelas = fk.id_kelas
    WHERE a.terbit = 'Y'
      AND fk.nis = '$nis_siswa'
      AND fk.tp = '$tahun_p'
    ORDER BY a.id DESC
");

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
<?php } ?>
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
