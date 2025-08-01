<script>
function confirmreset(delUrl) {
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
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card bg-primaryshadow">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
		  		<h6 class="m-0 font-weight-bold text-primary">Data User</h6>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table  table-striped" id="data_user"  width="100%" cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								<th>Username</th>
								<th>Nama Lengkap</th>
								<th>Level</th>
								<th>Blokir</th>
								<th>Edit</th>
								<!--<td>Hapus</td>-->
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>Username</th>
								<th>Nama Lengkap</th>
								<th>Level</th>
								<th>Blokir</th>
								<th>Edit</th>
								<!--<td>Hapus</td>-->
							</tr>
						</tfoot>
					</table>
				</div>	
			</div>
		</div>
	</div>
</div>

<?php }
break;

case "edit_data":

	if ($_SESSION['leveluser']=='admin'){
		
		$id_user = $_GET['id'];
		$sql_user= mysqli_query($koneksi,"SELECT * FROM user WHERE id_user='$id_user'");
		
		$r 		 = mysqli_fetch_array($sql_user);

		if($r['level']=='user_guru'){
			$password = md5('guru');
		}
		elseif($r['level']=='user_siswa'){
			$password = md5('siswa');
		}
		else {
			$password = md5('admin123');
		}
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card shadow">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<h6 class="font-weight-bold text-primary">Detail User</h6>
				<div class="dropdown no-arrow">
					<a href="?module=user" class="btn-sm btn-warning"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
				</div>
			</div>
			<div class="card-body">
				<table class="table">
					<tr><td width="30%">Username</td><td>: <?= $r['username'];?></td></tr>
					<tr><td>Nama Lengkap</td><td>: <?= $r['nama_lengkap'];?></td></tr>	
					<tr><td>Level User</td><td>: <?= $r['level'];?></td></tr>	
				</table>
			</div>
		</div>
	</div>
</div>
<hr class="sidebar-divider py-1">
<div class="row">
	<div class="col-lg-6">
		<div class="card">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-warning shadow">
		  		<h6 class="font-weight-bold text-white">RESET PASSWORD</h6>
			</div>
			<div class="card-body">
				<ul>
					<li><p class="align-items-center justify-content-between">Untuk User Dengan Level <b class="text-danger">user_guru</b> Password Default adalah : <b class="text-danger">guru</b> </p></li>
					<li><p class="align-items-center justify-content-between">Untuk User Dengan Level <b class="text-danger">user_siswa</b> Password Default adalah : <b class="text-danger">siswa</b> </p></li>
				</ul>
			</div>
			<div class="card-footer align-left">
				<a href="#" data-toggle="modal" data-target="#reset"><button class="btn btn-primary rounded"><i class="fas fa-process"></i>Proses</button></a>
			</div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="card">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-danger shadow">
		  		<h6 class="font-weight-bold text-white">BLOKIR USER</h6>
			</div>
			<div class="card-body">
				Status Blokir : &nbsp;&nbsp;&nbsp;

				<?php 
  				if($r['blokir']=='N') {
  				
  				?>	
      				<div class="custom-control custom-radio custom-control-inline">
			            <input type="radio" id="" name="data" class="custom-control-input" value="Y" readonly="readonly">
			            <label class="custom-control-label" for="y">Y</label>
			        </div>

			        <div class="custom-control custom-radio custom-control-inline">
			            <input type="radio" id="" name="data" class="custom-control-input" value="N" checked="checked" readonly="readonly">
			            <label class="custom-control-label" for="n">N</label>
			        </div>
			    <?php } else { ?>
			      	<div class="custom-control custom-radio custom-control-inline">
			            <input type="radio" id="" name="data" class="custom-control-input" value="Y" checked="checked" readonly="readonly">
			            <label class="custom-control-label" for="y">Y</label>
			        </div>

			        <div class="custom-control custom-radio custom-control-inline">
			            <input type="radio" id="" name="data" class="custom-control-input" value="N" readonly="readonly">
			            <label class="custom-control-label" for="n">N</label>
			        </div>
			    <?php } ?>
				<p class="align-items-center justify-content-between">Memblokir User Akan mengakibatkan user tidak bisa login ke system</p></li>
				<p><p>
			</div>
			<div class="card-footer align-left">
				<a href="#" data-toggle="modal" data-target="#blokir"><button class="btn btn-primary rounded"><i class="fas fa-process"></i>Proses</button></a>
			</div>
		</div>
	</div>
</div>

<!-- Modal Reset-->
<div class="modal fade" id="reset"  role="dialog" >
  	<div class="modal-dialog modal-sm" role="document">
    	<div class="modal-content">
        	<form method="POST" action="?module=user&act=save" enctype="multipart/form-data">
        		<div class="modal-header">
        			<h4 class="modal-title" id="myModalLabel"></h4>
        		</div>
      			<div class="modal-body">
      				<input type="hidden" name="id_user" value="<?= $r['id_user'];?>">
					<input type="hidden" name="password" value="<?= $password;?>">
					<center><i class="fas fa-exclamation-triangle fa-3x"></i><p class="text-danger font-weight-bold" style="text-align: center;">Apakah anda akan merset password : <b><?= $r['username']; ?></b></p> </center>
      			</div>
      			
      			<div class="modal-footer">
			        <button type="button" class="btn btn-warning" data-dismiss="modal">Tidak</button>
			        <button type="submit" name="reset" class="btn btn-primary">Ya</button>
		      </div>
		    </form>
  		</div>
	</div>
</div><!--end modal-->

<!-- Modal Blokir-->
<div class="modal fade" id="blokir"  role="dialog" >
  	<div class="modal-dialog modal-sm" role="document">
    	<div class="modal-content">
        	<form method="POST" action="?module=user&act=save" enctype="multipart/form-data">
        		<div class="modal-header" style="text-align: center;">
        			<h4 class="modal-title" id="myModalLabel">Blokir User.. ?</h4>
        		</div>
      			<div class="modal-body" style="text-align: center;">
      				<input type="hidden" name="id_user" value="<?= $r['id_user'];?>">
					<i class="fas fa-exclamation-triangle fa-3x"></i><p>
  				<?php 
  				if($r['blokir']=='N') {
  				
  				?>	
      				<div class="custom-control custom-radio custom-control-inline">
			            <input type="radio" id="blokir_1" name="blokir" class="custom-control-input" value="Y">
			            <label class="custom-control-label" for="blokir_1">Y</label>
			        </div>

			        <div class="custom-control custom-radio custom-control-inline">
			            <input type="radio" id="blokir_2" name="blokir" class="custom-control-input" value="N" checked="checked">
			            <label class="custom-control-label" for="blokir_2">N</label>
			        </div>
			    <?php } else { ?>
			      	<div class="custom-control custom-radio custom-control-inline">
			            <input type="radio" id="blokir_1" name="blokir" class="custom-control-input" value="Y" checked="checked">
			            <label class="custom-control-label" for="blokir_1">Y</label>
			        </div>

			        <div class="custom-control custom-radio custom-control-inline">
			            <input type="radio" id="blokir_2" name="blokir" class="custom-control-input" value="N">
			            <label class="custom-control-label" for="blokir_2">N</label>
			        </div>
			    <?php } ?>
      			</div>
      			
      			<div class="modal-footer">
			        <button type="button" class="btn btn-warning" data-dismiss="modal">Tidak</button>
			        <button type="submit" name="proses_blokir" class="btn btn-primary">Ya</button>
		      </div>
		    </form>
  		</div>
	</div>
</div><!--end modal-->

<?php 
	}

break;
case 'save':
include 'save.php';

break;
	}
}
?>
