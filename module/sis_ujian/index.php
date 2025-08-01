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
								<td>Mapel</td>
								<td>Pengajar</td>
								<td>Jumlah Ujian</td>
								<td>View</td>
							</tr>
						</thead>
						<tbody>
<?php
$no = 1;
$nis = mysqli_fetch_array(mysqli_query($koneksi, "SELECT nis FROM siswa WHERE id='$_SESSION[id_user]'"));
$kls = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_kelas FROM f_kelas WHERE nis='$nis[nis]' AND tp='$tahun_p'"));
$kelas = $kls['id_kelas'];

$sql_data = mysqli_query($koneksi,"SELECT id_mapel,nip FROM f_mapel WHERE id_kelas='$kelas' AND tp='$tahun_p'");

while ($s = mysqli_fetch_array($sql_data)) {
	$query = mysqli_query($koneksi, "
		SELECT COUNT(a.id) AS jum, b.nama_mapel, c.nama_lengkap, a.pembuat
		FROM topik_ujian a
		JOIN m_mapel b ON a.id_mapel = b.id_mapel
		JOIN guru c ON a.pembuat = c.id
		JOIN kelas_ujian d ON a.id = d.id_topik
		WHERE a.terbit = 'Y'
		  AND a.pembuat = '$s[nip]'
		  AND a.id_mapel = '$s[id_mapel]'
		  AND d.id_kelas = '$kelas'
		  AND a.tgl_buat BETWEEN '$thn_lalu' AND '$thn_skrg'
	");
	$r = mysqli_fetch_array($query);
	if ($r['jum'] != '0') {
?>
	<tr>
		<td><?= $no++; ?></td>
		<td><?= $r['nama_mapel']; ?></td>
		<td><?= $r['nama_lengkap']; ?></td>
		<td><?= $r['jum']; ?> Ujian</td>
		<td align="center">
			<a href="?module=sis_ujian&act=detail_ujian&id=<?= $r['pembuat']; ?>" class="btn-sm btn-info">
				<i class="fas fa-search"></i>
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

<?php }
break;

case "detail_ujian":
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card bg-primaryshadow">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<?php $nm = mysqli_fetch_array(mysqli_query($koneksi, "SELECT nama_lengkap FROM guru WHERE id='$_GET[id]'")) ?>
				<h6 class="m-0 font-weight-bold text-primary">Daftar Ujian Oleh <?= $nm['nama_lengkap']; ?></h6>
				<a href="?module=sis_ujian" class="btn-sm btn-warning"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped" id="table_1" width="100%" cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								<td>No</td>
								<td>Nama Ujian</td>
								<td>Mapel</td>
								<td>Waktu</td>
								<td>Kerjakan</td>
							</tr>
						</thead>
						<tbody>
<?php
$nis = mysqli_fetch_array(mysqli_query($koneksi, "SELECT nis FROM siswa WHERE id='$_SESSION[id_user]'"));
$kls = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_kelas FROM f_kelas WHERE nis='$nis[nis]' AND tp='$tahun_p'"));
$kelas = $kls['id_kelas'];
$pembuat = $_GET['id'];
$no = 1;

$sql_data = mysqli_query($koneksi, "
	SELECT a.*, b.nama_mapel, c.nama_lengkap 
	FROM topik_ujian a
	JOIN m_mapel b ON a.id_mapel = b.id_mapel
	JOIN guru c ON a.pembuat = c.id
	JOIN kelas_ujian d ON a.id = d.id_topik
	WHERE a.pembuat = '$pembuat'
	  AND a.terbit = 'Y'
	  AND d.id_kelas = '$kelas'
	  AND a.tgl_buat BETWEEN '$thn_lalu' AND '$thn_skrg'
	ORDER BY a.id DESC
");

while ($r = mysqli_fetch_array($sql_data)) {
?>
	<tr>
		<td><?= $no++; ?></td>
		<td><?= $r['judul']; ?></td>
		<td><?= $r['nama_mapel']; ?></td>
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
	break;
	} // end switch
} // end else
?>
