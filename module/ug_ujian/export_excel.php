<?php
// Tidak boleh ada spasi atau output sebelum <?php
ob_start(); // Bersihkan buffer output


if (empty($_POST['id_ujian']) || empty($_POST['id_kelas'])) {
    die("ID Kelas atau ID Ujian tidak ditemukan.");
}
$id_ujian = $_POST['id_ujian'];
$id_kelas = $_POST['id_kelas'];


include "../../config/koneksi.php";
include "../../config/fungsi_indotgl.php";
include "../../config/library.php";
include "../../config/fungsi.php";

$id_kelas = $_POST['id_kelas'] ?? '';
$id_ujian = $_POST['id_ujian'] ?? '';

if (empty($id_kelas) || empty($id_ujian)) {
    die("ID Kelas atau ID Ujian tidak ditemukan.");
}

// Header untuk file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=hasil_ujian_{$id_ujian}_{$id_kelas}.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Ambil data ujian
$sql_head = mysqli_query($koneksi, "
    SELECT a.*, b.nama_mapel, c.nama_lengkap
    FROM topik_ujian a
    JOIN m_mapel b ON a.id_mapel = b.id_mapel
    JOIN guru c ON a.pembuat = c.id
    WHERE a.id = '$id_ujian'
");
$r = mysqli_fetch_array($sql_head);

$r2 = mysqli_fetch_array(mysqli_query($koneksi, "
    SELECT nama_kelas FROM m_kelas WHERE id_kelas = '$id_kelas'
"));

// Ambil data siswa
$sql_data = mysqli_query($koneksi, "
    SELECT s.nama_lengkap, s.id
    FROM siswa s
    JOIN f_kelas fk ON s.nis = fk.nis
    JOIN kelas_ujian ku ON ku.id_kelas = fk.id_kelas
    WHERE ku.id_topik = '$id_ujian'
      AND fk.id_kelas = '$id_kelas'
      AND fk.tp = '$tahun_p'
    ORDER BY s.nama_lengkap ASC
");

?>
<u><h3 style="text-align:center;">HASIL UJIAN ONLINE</h3></u>
<table>
    <tr><td>Judul Ujian</td><td>: <?= $r['judul']; ?></td></tr>
    <tr><td>Mapel</td><td>: <?= $r['nama_mapel']; ?></td></tr>
    <tr><td>Guru</td><td>: <?= $r['nama_lengkap']; ?></td></tr>
    <tr><td>Kelas</td><td>: <?= $r2['nama_kelas']; ?></td></tr>
</table>

<br>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr style="background-color:#f5f5f5;">
            <th>NO</th>
            <th>NAMA LENGKAP</th>
            <th>NILAI PIL GANDA</th>
            <th>NILAI ESSAY</th>
            <th>NILAI AKHIR</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        while ($dt = mysqli_fetch_array($sql_data)) {
            $row1 = mysqli_fetch_array(mysqli_query($koneksi,"
                SELECT SUM(nilai) as jum 
                FROM nilai_esay 
                WHERE id_ujian = '$id_ujian' AND id_siswa = '{$dt['id']}'
            "));
            $row2 = mysqli_fetch_array(mysqli_query($koneksi,"
                SELECT nilai 
                FROM nilai 
                WHERE id_ujian = '$id_ujian' AND id_siswa = '{$dt['id']}'
            "));

            $n_pilg = ($r['bobot_pg'] / 100) * floatval($row2['nilai']);
            $n_esay = ($r['bobot_esay'] / 100) * floatval($row1['jum']);
            $jumlah = $n_pilg + $n_esay;
            ?>
            <tr>
                <td align="center"><?= $no++; ?></td>
                <td><?= $dt['nama_lengkap']; ?></td>
                <td align="center"><?= number_format($n_pilg, 2); ?></td>
                <td align="center"><?= number_format($n_esay, 2); ?></td>
                <td align="center"><?= number_format($jumlah, 2); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php
ob_end_flush(); // Kirim output
?>
