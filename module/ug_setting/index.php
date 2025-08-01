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
      	if ($_SESSION['leveluser']=='user_guru' or $_SESSION['leveluser']=='user_siswa'){
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card bg-primaryshadow">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
		  		<h6 class="m-0 font-weight-bold text-primary">Rubah Password</h6>
		  		 <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    </a>
			  	</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<?php 
	            	$d2=mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM user WHERE username='$_SESSION[namauser]'"))
	            	?>
					<form role="form" method="POST" enctype="multipart/form-data" action="?module=ug_setting&act=save">
						 <div class="card-body">
		                  <div class="form-group">
		                    <label for="exampleInputEmail1">Password Lama</label>
		                    <input type="hidden" name="id_user" value="<?php echo $d2['id_user'];?>">
		                    <input type="password" name="old_pass" class="form-control" value="<?php echo $d2['password'];?>" readonly>
		                  </div>
		                  <div class="form-group">
		                    <label for="exampleInputPassword1">Password Baru</label>
		                    <input type="password" name="new_pass" class="form-control" value="" placeholder="Password" required="required">
		                  </div>
		                </div>
		                <div class="card-footer">
		                  <button type="submit" name="update" class="btn btn-primary">Submit</button>
		                </div>
					</form>
				</div>	
			</div>
		</div>
	</div>
</div>

<?php }
break;
case "save":
	if(isset($_POST['update']))
		{
			$pass2=md5($_POST['new_pass']);
			$d="UPDATE user SET password   = '$pass2' WHERE id_user = '$_POST[id_user]'";
			
			mysqli_query($koneksi,$d);
			save_alert('update','Data Di Update');
			htmlRedirect('media.php?module='.$module,1);
			//echo"$b";	
		}
break;
	}
}
?>
