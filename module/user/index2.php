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
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card bg-primaryshadow">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
		  		<h6 class="m-0 font-weight-bold text-primary">Data User</h6>
		  		 <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <button class="btn-sm btn-primary" data-toggle="modal" data-target="#tambah_user"><i class="fas fa-plus"></i> User</button>
                    </a>
			  	</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped" id="table_1"  width="100%" cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								
								<td>Username</td>
								<td>Nama Lengkap</td>
								<td>Level</td>
								<td>Blokir</td>
								
							</tr>
						</thead>
						<tbody>
							
						</tbody>
						<tfoot>
							
						</tfoot>
					</table>
				</div>	
			</div>
		</div>
	</div>
</div>

<!-- Modal Tambah-->
<div class="modal fade" id="tambah_user"  role="dialog" >
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        	<h4 class="modal-title" id="myModalLabel">Edit User</h4>
      		</div>
      	<form action="?module=user&act=save" method="POST" role="form">
      	<div class="modal-body">
      	<?php 
	      	$us= $r['username'];
	      	$edit=mysqli_query($koneksi,"SELECT * FROM user WHERE username ='$us'");
	      	$ed=mysqli_fetch_array($edit);
      	?>
	        <div class="form-group">
	          	<label for="">Username</label>
	          		<input type="text" class="form-control" name="username" value="<?php echo $ed['username'];?>" readonly>
	         </div>

	         <div class="form-group">
	          	<label for="">Nama Lengkap</label>
	          		<input type="text" name="nama_lengkap" class="form-control" id="" placeholder="nama lengkap" value="<?php echo $ed['nama_lengkap'];?>" required="required">
	         </div>

	         <div class="form-group">
	          	<label for="">Password</label>
	          		<input type="password" name="password" class="form-control" id="" placeholder="password" value="<?php echo $ed['password'];?>" required="required">
	         </div>

	         <div class="form-group">
	          	<label for="">Blokir</label>
	          		<select name="nip" class="form-control select2bs4" required="required">
	          			<option value="<?php echo $ed['blokir'];?>" selected><?php echo $ed['blokir'];?></option>
	          			<option>Y</option>
	          			<option>N</option>
	          		</select>
	         </div>
      	</div>
      	<div class="modal-footer">
        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        	<button type="submit" name="update" class="btn btn-primary">Update</button>
      	</div>
  		</form>
    </div>
  </div>
</div><!--end modal-->


<?php }
	}
}
?>
