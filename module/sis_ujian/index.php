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
	//echo "<meta http-equiv='refresh' content='0; url=http://$_SERVER[HTTP_HOST]'>";
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
		<div class="card bg-deafult shadow">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
		  		<h6 class="m-0 font-weight-bold text-primary">Daftar Ujian </h6>
		  		 <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    </a>
			  	</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped" id="table_1"  width="100%" cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								<td>No</td>
								<td>Mapel </td>
								<td>Pengajar</td>
								<td>Jumlah Ujian</td>
								<td>View</td>
							</tr>
						</thead>
						<tbody>
							<?php
							 $no 		= 1;
							 $nis		= mysqli_fetch_array(mysqli_query($koneksi,"SELECT nis FROM siswa WHERE id='$_SESSION[id_user]'"));
							 $kls 		=  mysqli_fetch_array(mysqli_query($koneksi,"SELECT id_kelas FROM f_kelas WHERE nis='$nis[nis]' AND tp='$tahun_p'"));
							 $kelas 	= $kls['id_kelas'];
							 $sql_data	= mysqli_query($koneksi,"SELECT id_mapel,nip FROM f_mapel WHERE id_kelas='$kelas' AND tp= '$tahun_p'");
							 //echo "SELECT id_mapel FROM f_mapel WHERE id_kelas='$kelas' AND tahun_p = '$tahun_p'";
							 
							 while($s=mysqli_fetch_array($sql_data)) {
							 
							 $r=mysqli_fetch_array(mysqli_query($koneksi,"SELECT COUNT(a.id) as jum, a.*, b.nama_mapel, c.nama_lengkap FROM topik_ujian a, m_mapel b, guru c, kelas_ujian d WHERE a.id_mapel = b.id_mapel AND a.pembuat=c.id AND a.id=d.id AND a.pembuat = '$s[nip]' AND a.terbit ='Y' AND a.id_mapel='$s[id_mapel]' AND d.id_kelas='$kls[id_kelas]' AND a.tgl_buat BETWEEN '$thn_lalu' AND '$thn_skrg'"));
							 if($r['jum']!='0') {

							 ?>
								 <tr>
								 	<td><?= $no;?></td>
								 	<td><?= $r['nama_mapel'];?></td>
								 	<td><?= $r['nama_lengkap'];?></td>
								 	<td><?= $r['jum'];?> Ujian</td>
								 	<td align="center"><a href="?module=sis_ujian&act=detail_ujian&id=<?=$r['pembuat'];?>" class="btn-sm btn-info"><i class="fas fa-search"></i></a></td>
								 </tr>
							 <?php
							 	$no++;
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
				<?php $nm=mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_lengkap FROM guru WHERE id='$_GET[id]'")) ?>
		  		<h6 class="m-0 font-weight-bold text-primary">Daftar Ujian Oleh <?=$nm['nama_lengkap'];?> </h6>
		  		 <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    </a>
                    <a href="?module=sis_ujian" class="btn-sm btn-warning"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
			  	</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped" id="table_1"  width="100%" cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								<td>No</td>
								<td>Nama Ujian</td>
								<td>Mapel </td>
								<td>Waktu</td>
								<td>Kerjakan</td>
							</tr>
						</thead>
						<tbody style="font-size: 14px;">
							<?php
							 $nis		= mysqli_fetch_array(mysqli_query($koneksi,"SELECT nis FROM siswa WHERE id='$_SESSION[id_user]'"));
							 $kls 		=  mysqli_fetch_array(mysqli_query($koneksi,"SELECT id_kelas FROM f_kelas WHERE nis='$nis[nis]' AND tp='$tahun_p'"));
							 $kelas 	= $kls['id_kelas'];
							 $no 		= 1;
							 $pembuat	= $_GET['id']; 
							 $sql_data	= mysqli_query($koneksi,"SELECT a.*, b.nama_mapel, c.nama_lengkap FROM topik_ujian a, m_mapel b, guru c, kelas_ujian d WHERE a.id_mapel = b.id_mapel AND a.pembuat=c.id AND a.id=d.id AND a.pembuat = '$pembuat' AND a.terbit ='Y' AND d.id_kelas='$kls[id_kelas]' AND a.tgl_buat BETWEEN '$thn_lalu' AND '$thn_skrg' ORDER BY a.id DESC");

							 //echo "SELECT id_mapel FROM f_mapel WHERE id_kelas='$kelas' AND tahun_p = '$tahun_p'";
							 
							 while($r=mysqli_fetch_array($sql_data)) {
							 
							 ?>
							 <tr>
							 	<td><?= $no;?></td>
							 	<td><?= $r['judul'];?></td>
							 	<td><?= $r['nama_mapel'];?></td>
							 	<td><?= $r['waktu_pengerjaan']/60;?> Menit</td>
							 	<td align="center"><a href="?module=ujian_online&pmb=<?=$r['pembuat'];?>&topik=<?= $r['id'];?>" class="btn-sm btn-primary"><i class="fas fa-angle-double-right"></i></a></td>
							 </tr>
							 <?php
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
case "selesai_ujian":
$gr=mysqli_fetch_array(mysqli_query($koneksi,"SELECT pembuat FROM topik_ujian WHERE id='$_POST[ujian]'"));
$rnilai = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_ujian='$_POST[ujian]' AND id_siswa='$_SESSION[id_user]'"));
	
   $arr_soal = explode(",", $rnilai['acak_soal']);
   $jawaban = explode(",", $rnilai['jawaban']);
   $jbenar = 0;
   $jkosong = 0;
   $jsoal = count($arr_soal);
   for($i=0; $i<count($arr_soal); $i++){
      $rsoal = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM soal_pilganda WHERE id_tujian='$_POST[ujian]' AND id_soalpg='$arr_soal[$i]'"));
      if($rsoal['kunci'] == $jawaban[$i]) $jbenar++;
  	 if($jawaban[$i] == 0) $jkosong++;
	$jsalah = $jsoal-$jbenar-$jkosong;
    }
   $nilai = ($jbenar/count($arr_soal))*100;
	
 mysqli_query($koneksi, "UPDATE nilai SET jml_benar='$jbenar', jml_kosong='$jkosong', jml_salah='$jsalah', nilai='$nilai', status='selesai' WHERE id_ujian='$_POST[ujian]' AND id_siswa='$_SESSION[id_user]'");
   
   
   mysqli_query($koneksi, "UPDATE siswa SET status='Selesai' WHERE id='$_SESSION[id_user]'");
   save_alert('save','Ujian Berhasil di Proses');
   htmlRedirect('media.php?module=ujian_online&pmb='.$gr['pembuat'].'&topik='.$_POST['ujian'],1);
break;
	}
}
?>
