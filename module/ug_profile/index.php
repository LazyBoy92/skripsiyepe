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
<div class="row">
	<div class="col-lg-12">
		<div class="card shadow">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
		  		<h6 class="m-0 font-weight-bold text-primary">Detail Profil</h6>
		  		 <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    </a>
			  	</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<?php 
	            	$d=mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM guru WHERE id='$_SESSION[id_user]'"))
	            	?>
		        <div class="row">
		          <div class="col-sm-3">
		            <!-- Profile Image -->
		            <div class="card card-primary card-outline">
		              	<div class="card-body box-profile">
		                	<div class="text-center">
		                  		<img src="module/foto_pengajar/medium_<?= $d['foto'];?>" width="100%" alt="User profile picture"><br><h5><?php echo $d['nama_lengkap'];?></h5>
		                	</div>
		                </div>
		            </div>
		           </div>
		           <div class="col-sm-9">
		           		<div class="card card-primary">
			              <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
			                <h5>Detail Data</h5>
			                <a href="?module=ug_profile&act=edit_guru&id=<?=$d['id'];?>" class="btn-sm btn-warning rounded"><i class="fas fa-edit"></i> Edit Data</a>
			              </div><!-- /.card-header -->

			              <div class="card-body">
			                  	<div class="form-group row">
			                        <div class="col-sm-3"><b>NIP/NUPTK </b></div>
			                        <div class="col-sm-6">: <?php echo $d['nip'];?></div>
			                    </div>
			                  	<div class="form-group row">
			                        <div class="col-sm-3"><b>Nama Lengkap </b></div>
			                        <div class="col-sm-6">: <?php echo $d['nama_lengkap'];?></div>
			                    </div>
			                    <div class="form-group row">
			                        <div class="col-sm-3"><b>Jenis Kelamin </b></div>
			                        <div class="col-sm-6">: <?php echo $d['jenis_kelamin'];?></div>
			                    </div>
			                    <div class="form-group row">
			                        <div class="col-sm-3"><b>TTL </b></div>
			                        <div class="col-sm-6">: <?php echo $d['tempat_lahir'].', '.tgl_indo($d['tgl_lahir']);?></div>
			                    </div>
			                    <div class="form-group row">
			                        <div class="col-sm-3"><b>Alamat </b></div>
			                        <div class="col-sm-6">: <?php echo $d['alamat'];?></div>
			                    </div>
			                    <div class="form-group row">
			                        <div class="col-sm-3"><b>No Telp/HP(WA)  </b></div>
			                        <div class="col-sm-6">: <?php echo $d['no_telp'];?></div>
			                    </div>
			                  	<div class="form-group row">
			                  		<div class="col-sm-3"><b>Tahun Masuk  </b></div>
			                        <div class="col-sm-6">: <?php echo $d['th_masuk'];?></div>
			                    </div>
			                    <div class="form-group row">
			                        <div class="col-sm-3"><b>Jabatan  </b></div>
			                        <div class="col-sm-6">: <?php echo $d['jabatan'];?></div>
			                    </div>
			              	</div>
			              </div>
		           		</div>
					</div>
				</div>
			</div>
	  	</div>
	  </div>
	</div>
	

<?php }

	elseif ($_SESSION['leveluser']=='user_siswa'){ 
		?>

		<div class="row">
			<div class="col-lg-12">
				<div class="card shadow">
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				  		<h6 class="m-0 font-weight-bold text-primary">Detail Profil Siswa</h6>
				  		 <div class="dropdown no-arrow">
		                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		                    </a>
					  	</div>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<?php 
			            	$d=mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM siswa WHERE id='$_SESSION[id_user]'"));
			            	$e=mysqli_fetch_array(mysqli_query($koneksi,"SELECT a.nama_kelas,b.id_kelas FROM m_kelas a, f_kelas b WHERE a.id_kelas = b.id_kelas AND b.nis='$d[nis]' AND b.tp='$tahun_p'"));
			            	?>
				        <div class="row">
				          <div class="col-md-3">
				            <!-- Profile Image -->
				            <div class="card card-primary card-outline">
				              	<div class="card-body box-profile">
				                	<div class="text-center">
				                  		<img src="module/foto_siswa/medium_<?= $d['foto'];?>" width="100%" alt="User profile picture"><br><h5><?php echo $d['nama_lengkap'];?></h5>
				                	</div>
				                </div>
				            </div>
				           </div>
				           <div class="col-md-9">
				           		<div class="card card-primary">
					              <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					                <h5>Detail Data</h5>
					                <a href="?module=ug_profile&act=edit_siswa&id=<?=$d['id'];?>" class="btn-sm btn-warning rounded"><i class="fas fa-edit"></i> Edit Data</a>
					              </div><!-- /.card-header -->

					              <div class="card-body">
					                  	<div class="form-group row">
					                        <div class="col-sm-3"><b>NISN </b></div>
					                        <div class="col-sm-6">: <?php echo $d['nis'];?></div>
					                    </div>
					                  	<div class="form-group row">
					                        <div class="col-sm-3"><b>Nama Lengkap </b></div>
					                        <div class="col-sm-6">: <?php echo $d['nama_lengkap'];?></div>
					                    </div>
					                    <div class="form-group row">
					                        <div class="col-sm-3"><b>Jenis Kelamin </b></div>
					                        <div class="col-sm-6">: <?php echo $d['jenis_kelamin'];?></div>
					                    </div>
					                    <div class="form-group row">
					                        <div class="col-sm-3"><b>TTL </b></div>
					                        <div class="col-sm-6">: <?php echo $d['tempat_lahir'].', '.tgl_indo($d['tgl_lahir']);?></div>
					                    </div>
					                    <div class="form-group row">
					                        <div class="col-sm-3"><b>Alamat </b></div>
					                        <div class="col-sm-6">: <?php echo $d['alamat'];?></div>
					                    </div>
					                    <div class="form-group row">
					                        <div class="col-sm-3"><b>No Telp/HP(WA)  </b></div>
					                        <div class="col-sm-6">: <?php echo $d['no_telp'];?></div>
					                    </div>
					                  	<div class="form-group row">
					                  		<div class="col-sm-3"><b>Tahun Masuk  </b></div>
					                        <div class="col-sm-6">: <?php echo $d['th_masuk'];?></div>
					                    </div>
					                    <div class="form-group row">
					                        <div class="col-sm-3"><b>Kelas  </b></div>
					                        <div class="col-sm-6">: <?php echo $e['nama_kelas'];?></div>
					                    </div>
					              	</div>
					              </div>
				           		</div>
							</div>
						</div>
					</div>
			  	</div>
			  </div>
			</div>

		<?php
		
	}

	else{
		?>
		<!-- 404 Error Text -->
          <div class="text-center">
            <div class="error mx-auto" data-text="404">404</div>
            <p class="lead text-gray-800 mb-5">Modul Tidak ditemukan</p>
            <p class="text-gray-500 mb-0">Modul yang anda cari belum tersedia</p>
            <a href="?module=home">&larr; Kembali ke Dashboard</a>
          </div>
      <?php
		}
break;
case "edit_guru":

$id_guru = $_GET['id'];
$r=mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM guru WHERE id='$id_guru'"));
?>

<div class="row">
	<div class="col-lg-12">
		<div class="card shadow">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h5>FORM EDIT PROFILE</h5>
                <a href="?module=ug_profile" class="btn-sm btn-warning rounded"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
            </div>
            <form method="POST" action="?module=ug_profile&act=save" enctype="multipart/form-data">
            <div class="card-body">
            	
        		<div class="form-group">
        			<label for="">NIP/NUPTK</label>
                    <input type="hidden" class="form-control" name="id" value="<?= $r['id'];?>">
                    <input type="text" class="form-control" name="nip" value="<?= $r['nip'];?>">
                </div>

                <div class="form-group">
        			<label for="">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama_lengkap" value="<?= $r['nama_lengkap'];?>">
                </div>

                <div class="form-group">
        			<label for="">Jabatan</label>
                    <input type="text" class="form-control" name="jabatan" value="<?= $r['jabatan'];?>">
                </div>

                <div class="form-group">
        			<label for="">Alamat</label>
        			<textarea name="alamat" class="form-control"><?= $r['alamat'];?></textarea>
                </div>

                <div class="form-group">
                	<label for="">Tempat Tanggal Lahir</label>
        			<div class="row">
        				<div class="col-sm-4">
        					<input type="text" class="form-control" name="tempat_lahir" value="<?= $r['tempat_lahir'];?>">
        				</div>
        				<div class="col-sm-8">
        					<input type="date" class="form-control" name="tgl_lahir" value="<?= $r['tgl_lahir'];?>">
        				</div>	
        			</div>
                </div>

                <div class="form-group">
        			<label for="">Jenis Kelamin</label>
        			<select name="jenis_kelamin" class="form-control">
        				<option value="<?=$r['jenis_kelamin'];?>"><?=$r['jenis_kelamin'];?></option>
        				<option>P</option>
        				<option>L</option>
        			</select>
                </div>

                <div class="form-group">
        			<label for="">Agama</label>
                    <input type="text" class="form-control" name="agama" value="<?= $r['agama'];?>">
                </div>

                <div class="form-group">
        			<label for="">E-Mail</label>
                    <input type="text" class="form-control" name="email" value="<?= $r['email'];?>">
                </div>

                <div class="form-group">
        			<label for="">No Telp / HP</label>
                    <input type="number" class="form-control" name="no_telp" value="<?= $r['no_telp'];?>">
                </div>

                <div class="form-group">
        			<label for="">Ganti Foto</label><p></p>
        			<img src="module/foto_pengajar/medium_<?=$r['foto'];?>" width="100"><p></p>
                    <input type="file" class="form-control" name="fupload" value="">
                </div>
            	
            </div>
            <div class="card-footer">
            	<input type="submit" name="update_guru" class="btn btn-primary" value="Update">
            </div>
            </form>
        </div>
    </div>
</div>

<?php
break;

case "edit_siswa":

$id_siswa = $_GET['id'];
$r=mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM siswa WHERE id='$id_siswa'"));
?>

<div class="row">
	<div class="col-lg-12">
		<div class="card shadow">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h5>FORM EDIT PROFILE</h5>
                <a href="?module=ug_profile" class="btn-sm btn-warning rounded"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
            </div>
            <form method="POST" action="?module=ug_profile&act=save" enctype="multipart/form-data">
            <div class="card-body">
            	<input type="hidden" class="form-control" name="id" value="<?= $r['id'];?>">
                <div class="form-group">
        			<label for="">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama_lengkap" value="<?= $r['nama_lengkap'];?>">
                </div>

                <div class="form-group">
        			<label for="">Alamat</label>
        			<textarea name="alamat" class="form-control"><?= $r['alamat'];?></textarea>
                </div>

                <div class="form-group">
                	<label for="">Tempat Tanggal Lahir</label>
        			<div class="row">
        				<div class="col-sm-4">
        					<input type="text" class="form-control" name="tempat_lahir" value="<?= $r['tempat_lahir'];?>">
        				</div>
        				<div class="col-sm-8">
        					<input type="date" class="form-control" name="tgl_lahir" value="<?= $r['tgl_lahir'];?>">
        				</div>	
        			</div>
                </div>

                <div class="form-group">
        			<label for="">Jenis Kelamin</label>
        			<select name="jenis_kelamin" class="form-control">
        				<option value="<?=$r['jenis_kelamin'];?>"><?=$r['jenis_kelamin'];?></option>
        				<option>P</option>
        				<option>L</option>
        			</select>
                </div>

                <div class="form-group">
        			<label for="">Agama</label>
                    <input type="text" class="form-control" name="agama" value="<?= $r['agama'];?>">
                </div>

                <div class="form-group">
        			<label for="">E-Mail</label>
                    <input type="text" class="form-control" name="email" value="<?= $r['email'];?>">
                </div>

                <div class="form-group">
        			<label for="">No Telp / HP</label>
                    <input type="number" class="form-control" name="no_telp" value="<?= $r['no_telp'];?>">
                </div>

                <div class="form-group">
        			<label for="">Ganti Foto</label><p></p>
        			<img src="module/foto_siswa/medium_<?=$r['foto'];?>" width="100"><p></p>
                    <input type="file" class="form-control" name="fupload" value="">
                </div>
            	
            </div>
            <div class="card-footer">
            	<input type="submit" name="update_siswa" class="btn btn-primary" value="Update">
            </div>
            </form>
        </div>
    </div>
</div>

<?php
break;

case "save":
include "save.php";
break;






	}
}
?>
