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
								<td>No</td>
								<td>Username</td>
								<td>Nama Lengkap</td>
								<td>Level</td>
								<td>Blokir</td>
								<td>Edit</td>
								<!--<td>Hapus</td>-->
							</tr>
						</thead>
						<tbody>
							<?php 
								$sql_data = mysqli_query($koneksi,"SELECT * FROM user ORDER BY level ASC");
								$no=1;

								foreach ($sql_data as $r ) {

									
								/*}
								
								while($r=mysqli_fetch_array($sql_data)){
									if($r['level']=='user_guru')
									{
										$d=mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM guru WHERE id = '$r[id_user]'"));
										$nama=$d['nama_lengkap'];
									}
									elseif($r['level']=='user_siswa')
									{
										$d1=mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM siswa WHERE id = '$r[id_user]'"));
										$nama=$d1['nama_lengkap'];
									}
									elseif($r['level']=='superadmin' or $r['level']=='admin')
									{
										$nama=$r['nama_lengkap'];
									}*/
							?>
								<tr>
									<td><?php echo $no;?></td>
				    				<td><?php echo $r['username'];?></td>
				    				<td><?php echo $r['nama_lengkap'];?></td>
				    				<td><?php echo $r['level'];?></td>
				    				<td><i class="btn-sm btn-info"><?php echo $r['blokir'];?></i></td>
				    				<td align="center"><a href="#" class="btn-sm btn-warning" data-toggle="modal" data-target="#edit<?= $r['username'];?>"><i class="fas fa-edit"></i></a></td>
				    				<!--<td align="center"><a href="javascript:confirmdelete('?module=user&act=save&post=hapus&id=<?=$r['username'];?>')" class="btn-sm btn-danger"><i class="fas fa-trash"></i></a></td>-->
				    			</tr>

				    			<!-- Modal Edit-->
								<div class="modal fade" id="edit<?php echo $r['username'];?>"  role="dialog" >
								  	<div class="modal-dialog modal-lg" role="document">
								    	<div class="modal-content">
								      		<div class="modal-header">
								        	<h4 class="modal-title" id="myModalLabel">Edit User</h4>
								      		</div>
								      	<form action="?module=user&act=save" method="POST" role="form">
								      	<div class="modal-body">
									        <div class="form-group">
									          	<label for="">Username</label>
									          		<input type="text" class="form-control" name="username" value="<?= $r['username'];?>" readonly>
									         </div>

									         <div class="form-group">
									          	<label for="">Password</label>
									          		<input type="password" name="password" class="form-control" id="" placeholder="password" value="<?= $r['password'];?>" required="required">
									         </div>

									         <div class="form-group">
									          	<label for="">Blokir</label>
									          		<select name="blokir" class="form-control select2bs4" required="required">
									          			<option value="<?= $r['blokir'];?>" selected><?= $r['blokir'];?></option>
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
							<?php
								$no++;
								}

							 ?>
						</tbody>
						<tfoot>
							
						</tfoot>
					</table>
				</div>	
			</div>
		</div>
	</div>
</div>

<?php }
	}
}
?>
