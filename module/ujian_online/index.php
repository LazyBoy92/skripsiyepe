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
      	if ($_SESSION['leveluser']=='user_siswa'){
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card bg-primaryshadow">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<?php 
					$pmb 	= $_GET['pmb'];
					$topik 	= $_GET['topik'];
					//echo $topik;
					$r=mysqli_fetch_array(mysqli_query($koneksi,"SELECT a.*, b.nama_mapel, c.nama_lengkap FROM topik_ujian a, m_mapel b, guru c WHERE a.id_mapel = b.id_mapel AND a.pembuat=c.id AND a.id = '$topik' AND a.terbit ='Y'"));
				?>
		  		<h6 class="m-0 font-weight-bold text-primary">UJIAN ONLINE</h6>
		  		 <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    </a>
                    <a href="?module=sis_ujian&act=detail_ujian&id=<?= $pmb;?>" class="btn-sm btn-warning"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
			  	</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped" width="100%">
						<tr><td>NAMA UJIAN </td><td>: <?= $r['judul'];?></td></tr>
						<tr><td>MATA PELAJARAN </td><td>: <?= $r['nama_mapel'];?></td></tr>
						<tr><td>PENGAJAR </td><td>: <?= $r['nama_lengkap'];?></td></tr>
						<tr><td>BATAS WAKTU UJIAN </td><td>: <?= $r['waktu_pengerjaan']/60;?> Menit</td></tr>
						<?php 
						//Jika nilai sudah ada tampilkan tombol Sudah Mengerjakan, jika belum ada tampilkan tombol Kerjakan
				        $qnilai = mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_ujian='$r[id]' AND id_siswa='$_SESSION[id_user]'");
				        $tnilai = mysqli_num_rows($qnilai);
				        $rnilai = mysqli_fetch_array($qnilai);
						

				        if($tnilai>0 and $rnilai['nilai'] != "") {

				        	$tombol= "<a href='?module=ujian_online&act=show_nilai&id=$r[id]'> <button class='btn-md btn-info'><i class='fas fa-info'></i> Lihat Hasil </button></a>";
				        	$status = "<button class='btn-md btn-success'><i class='fas fa-info'></i> Sudah Mengerjakan</button>";
				    	}
						elseif($tnilai>0 and $rnilai['sisa_waktu'] != "") {

							$tombol="<a href='?module=show_ujian&id=$r[id]'><button class='btn-md btn-warning'><i class='fas fa-angle-double-up'></i> LANJUTKAN</button></a>";
							$status = "<button class='btn-md btn-warning'><i class='fas fa-info'></i> Dalam Proses Pengerjaan</button>";
						}
				        else {
				        	 $tombol= "<a href='?module=show_ujian&id=$r[id]'><button class='btn-md btn-primary'><i class='fas fa-angle-double-up'></i> MULAI KERJAKAN</button></a>";
				        	 $status = "<button class='btn-md btn-danger'><i class='fas fa-info'></i> Belum Mengerjakan</button>";
				        	}

				        $sql_essay=mysqli_query($koneksi,"SELECT COUNT(id_soal) as jum FROM soal_esay WHERE id_tujian ='$r[id]'");
				        $cek_essay=mysqli_fetch_array($sql_essay);

				        if(empty($cek_essay['jum'])){
				        	$stesay	="<button class='btn-md btn-warning'><i class='fas fa-info'></i> Tidak Ada Soal Essay</button>";
				        	$tom_essay = "<button class='btn-md btn-warning'><i class='fas fa-info'></i> No Action</button>";
				        }
				        else {
				        	$stesay	   ="<button class='btn-md btn-info'><i class='fas fa-info'></i>  Ada $cek_essay[jum] Soal Essay</button>";
				        	$aes =mysqli_fetch_array(mysqli_query($koneksi,"SELECT status FROM nilai_esay WHERE id_siswa = '$_SESSION[id_user]' AND id_ujian='$r[id]'"));
				        	if($aes['status']=='')
				        	{
				        		$tom_essay = "<a href='?module=show_esay&id=$r[id]'><button class='btn-md btn-primary'><i class='fas fa-angle-double-up'></i> MULAI KERJAKAN</button></a>";
				        	}
				        	elseif ($aes['status']=='mengerjakan') {
				        		$tom_essay = "<a href='?module=show_esay&id=$r[id]'><button class='btn-md btn-warning'><i class='fas fa-angle-double-up'></i> LANJUTKAN</button></a>";
				        	}
				        	elseif ($aes['status']=='selesai') {
				        		$tom_essay = "<a href='?module=ujian_online&act=show_nilai&id=$r[id]'><button class='btn-md btn-info'><i class='fas fa-angle-double-up'></i> Lihat Hasil</button></a>";
				        	}
				        }
        
						 ?>
						<tr><td>STATUS SOAL PIL GANDA </td><td>: <?= $status;?></td></tr>
						<tr><td>STATUS SOAL ESSAY </td><td>: <?= $stesay;?></td></tr>
						<tr><td colspan="2" style="background-color: blue; text-align: center; color: white;"> MULAI UJIAN </td></tr>
						<tr align="center"><td>PILIHAN GANDA</td><td>ESSAY</td></tr>
						<tr align="center"><td> <?= $tombol;?></td><td><?= $tom_essay;?></td></tr>
					</table>
				</div>	
			</div>
		</div>
	</div>
</div>

<?php

	}
break;
case "show_nilai":

$id_siswa = $_SESSION['id_user'];
$id_ujian = $_GET['id'];

$sql_data = "SELECT a.*,b.nama_mapel, c.nama_lengkap FROM topik_ujian a, m_mapel b, guru c WHERE a.id_mapel=b.id_mapel AND a.pembuat=c.id AND a.id = '$id_ujian' ";
$r 		  = mysqli_fetch_array(mysqli_query($koneksi,$sql_data));

$nil_pg   = mysqli_fetch_array(mysqli_query($koneksi,"SELECT nilai FROM nilai WHERE id_ujian='$id_ujian' AND id_siswa ='$id_siswa'"));
$nil_es   = mysqli_fetch_array(mysqli_query($koneksi,"SELECT SUM(nilai) as jum FROM nilai_esay WHERE id_ujian='$id_ujian' AND id_siswa ='$id_siswa'"));

	if($nil_es['jum']==0){
		$nil_esay = 'Belum di koreksi';
	}
	else{
		$nil_esay = $nil_es['jum'];
	}
?>

<div class="row">
	<div class="col-lg-7">
		<div class="card bg-primaryshadow">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
		  		<h6 class="m-0 font-weight-bold text-primary">Hasil Ujian</h6>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped" width="100%">
						<thead>
							<tr><th>Jenis Ujian</th><th>Nilai</th></tr>
						</thead>
						<tbody>
							<tr><td>Ujian Pilihan Ganda</td><td><?= $nil_pg['nilai'];?></td></tr>
							<tr><td>Ujian Essay</td><td><?= $nil_esay;?></td></tr>
						</tbody>
					</table>
				</div>	
			</div>
		</div>
	</div>

	<div class="col-lg-5">
		<div class="card bg-primaryshadow">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
		  		<h6 class="m-0 font-weight-bold text-primary">Info Ujian</h6>
		  		 <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    </a>
                    
			  	</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped" width="100%">
						<tr><td>Judul Ujian</td><td>: <?=$r['judul'];?></td></tr>
						<tr><td>Mapel</td><td>: <?=$r['nama_mapel'];?></td></tr>
						<tr><td>Guru</td><td>: <?=$r['nama_lengkap'];?></td></tr>
					</table>
				</div>	
			</div>
			<div class="card-footer">
				<a href="?module=ujian_online&pmb=<?= $r['pembuat'];?>&topik=<?= $r['id']?>" class="btn-sm btn-warning"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>

				<a href="module/ujian_online/cetak_hasil.php?&topik=<?= $r['id']?>&siswa=<?=$id_siswa;?>" class="btn-sm btn-primary"><i class="fas fa-print"></i> Print</a>
			</div>
		</div>
	</div>

</div>
<?php
break;
	}
}
?>