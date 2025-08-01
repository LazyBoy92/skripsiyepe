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

      		$sql_user=mysqli_query($koneksi,"SELECT * FROM user WHERE blokir ='A'");
      		$no = 1;
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card bg-primaryshadow">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
		  		<h6 class="m-0 font-weight-bold text-primary">Data User</h6>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table  table-striped" id="table_1"  width="100%" cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								<th>No</th>
								<th>Username</th>
								<th>Nama Lengkap</th>
								<th>Level</th>
								<th>Aktifasi</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($sql_user as $r ) {
							?>
							<tr>
								<td><?= $no;?></td>
								<td><?= $r['username'];?></td>
								<td><?= $r['nama_lengkap'];?></td>
								<td><?= $r['level'];?></td>
								<td><a href="#" data-toggle="modal" data-target="#aktifasi_<?= $r['id_user'];?>" class="btn-sm btn-primary rounded"><i class="fas fa-info-circle"></i></a></td>
							</tr>

							<!-- Modal Edit-->
							<div class="modal fade" id="aktifasi_<?= $r['id_user'];?>"  role="dialog" >
							  	<div class="modal-dialog modal-md" role="document">
							    	<form action="?module=user_aktivasi&act=save" method="POST" role="form">
							    		<input type="hidden" class="form-control" name="id_user" value="<?= $r['id_user'];?>">
								    	<div class="modal-content">
								      		<div class="modal-header">
								        		<h4 class="modal-title" id="myModalLabel">Aktifasi User</h4>
								      		</div>
									      	<div class="modal-body mx-5" style="text-align: center;">
										        <div class="form-group">
										          	<h4 class="text-primary">Username : <?= $r['username'];?></h4>
										          	<h4 class="text-primary">Level : <?= $r['level'];?></h4>
										        </div>
									      	</div>
									      	<div class="modal-footer">
									        	<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
									        	<button type="submit" name="proses" class="btn btn-primary">Proses</button>
									      	</div>
								    	</div>
							    	</form>
							  	</div>
							</div><!--end modal-->
							<?php 
							}
							 ?>
						</tbody>
						<tfoot>
							<tr>
								<th>No</th>
								<th>Username</th>
								<th>Nama Lengkap</th>
								<th>Level</th>
								<th>Aktifasi</th>
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
case 'save':
include 'save.php';

break;
	}
}
?>
