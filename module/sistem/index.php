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
      	
	if ($_SESSION['leveluser']=='admin'){

		$r=mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM sis_identitas WHERE id = '1'"));

?>
<div class="row">
	<div class="col-xl-8 lg-7">
		<form method="POST" action="?module=sistem&act=save" name="edit_nmlengkap" enctype="multipart/form-data">
			<div class="card shadow">
				<div class="card-header">
					<h6 class="m-0 font-weight-bold text-primary">Edit Nama System</h6>
				</div>
				<div class="card-body">
					<input type="text" name="nama_panjang" value="<?=$r['nama_panjang'];?>" class="form-control" required="required"><small>Edit Nama Panjang Sistem Digital Learning</small>
				</div>
				<div class="card-footer" align="right">
					<input type="submit" name="btn_namap"class="btn btn-primary rounded" value="Proses">
				</div>
			</div>
		</form>
	</div>

	<div class="col-xl-4 lg-3">
		<form method="POST" action="?module=sistem&act=save" name="edit_tahunp" enctype="multipart/form-data">
			<div class="card shadow">
				<div class="card-header">
					<h6 class="m-0 font-weight-bold text-primary">Ganti Tahun Pelajaran</h6>
				</div>
				<div class="card-body">
					<select name="tahun_p" class="form-control" required>
						<option><?=$r['tahun_p'];?></option>
						<option>2019/2020</option>
						<option>2020/2021</option>
						<option>2021/2022</option>
						<option>2022/2023</option>
						<option>2023/2024</option>
						<option>2024/2025</option>
					</select>
				</div>
				<div class="card-footer" align="right">
					<input type="submit" name="btn_tahunp"class="btn btn-primary rounded" value="Proses">
				</div>
			</div>
		</form>
	</div>

	<div class="col-xl-8 lg-7 mt-2">
		<form method="POST" action="?module=sistem&act=save" name="edit_nmsingkat" enctype="multipart/form-data">
			<div class="card shadow">
				<div class="card-header">
					<h6 class="m-0 font-weight-bold text-primary">Edit Nama Singkatan</h6>
				</div>
				<div class="card-body">
					<input type="text" name="nama_singkat" value="<?=$r['nama_singkat'];?>" class="form-control" required="required"><small>Edit Nama Singkatan Sistem Digital Learning</small>
				</div>
				<div class="card-footer" align="right">
					<input type="submit" name="btn_namas"class="btn btn-primary rounded" value="Proses">
				</div>
			</div>
		</form>
	</div>

	<div class="col-xl-8 lg-7 mt-2">
		<form method="POST" action="?module=sistem&act=save" name="edit_logonav" enctype="multipart/form-data">
			<div class="card shadow">
				<div class="card-header">
					<h6 class="m-0 font-weight-bold text-primary">Logo Nav</h6>
				</div>
				<div class="card-body">
					<img src="dist/img/<?=$r['logo_nav'];?>" width="20%">
					<input type="file" name="flogo" value="" class="form-control" required="required"><small>Plih File Untuk Ganti Logo</small>
				</div>
				<div class="card-footer" align="right">
					<input type="submit" name="btn_logo"class="btn btn-primary rounded" value="Proses">
				</div>
			</div>
		</form>
	</div>



</div>
<hr class="clear-fix"></hr>

<?php 
		}
break;

case 'save':
include 'save.php';
break;
	}
}
?>
