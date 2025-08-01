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
	<div class="col-md-12">
		<div class="card shadow">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
		  		<h6 class="m-0 font-weight-bold text-primary">Data Pendidik / Guru</h6>
		  		 <div class="dropdown no-arrow">
		  		 	<a href="module/m_guru/form_guru.xls" target="_blank" role="button">
                      <button class="btn-sm btn-success"><i class="fas fa-file-excel"></i> Form Guru</button>
                    </a>
                    <a  href="#" role="button">
                      <button class="btn-sm btn-primary" data-toggle="modal" data-target="#upload_data"><i class="fas fa-upload"></i> Upload Data</button>
                    </a>
			  	</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped" id="table_1"  width="100%" cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								<th>No</th>
								<th>NIP/NUPTK</th>
								<th>Nama Lengkap</th>
								<th>Jenis Kelamin</th>
								<th>Jabatan</th>
								<th>View</th>
							</tr>
						</thead>
						<tbody>
						<?php 
							$sql_data = mysqli_query($koneksi,"SELECT * FROM guru ORDER BY nama_lengkap ASC");
							$no=1;
							while($r=mysqli_fetch_array($sql_data)){
						?>
							<tr>
								<td><?php echo $no;?></td>
			    				<td><?php echo $r['nip'];?></td>
			    				<td><?php echo $r['nama_lengkap'];?></td>
			    				<td><?php echo $r['jenis_kelamin'];?></td>
			    				<td><?php echo $r['jabatan'];?></td>
			    				<td align="center"><a href="?module=m_guru&act=view_data&id=<?= $r['id'];?>" class="btn-sm btn-info"><i class="fas fa-info"></i></a></td>
			    			</tr>

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

<!-- Modal Tambah-->
<div class="modal fade" id="upload_data"  role="dialog" >
  	<div class="modal-dialog modal-md" role="document">
    	<form action="?module=m_guru&act=save" method="POST" role="form" enctype="multipart/form-data">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<h4 class="modal-title" id="myModalLabel">Upload Data</h4>
	      		</div>
		      	<div class="modal-body">
			         <div class="form-group">
			          	<label for="">Upload File</label>
			          		<input type="file" class="form-control" name="fguru"  placeholder="NISN">
			         </div>
		      	</div>
		      	<div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        	<button type="submit" name="upload_guru" class="btn btn-primary">Simpan</button>
		      	</div>
    		</div>
    	</form>
  	</div>
</div>
<!--end modal-->

<?php }

break;
case 'view_data':
$id_guru = $_GET['id'];
$d=mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM guru WHERE id='$id_guru'"))
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
		        	<div class="row">
		          		<div class="col-sm-3">
			            	<!-- Profile Image -->
			           	 	<div class="card card-primary card-outline">
			              		<div class="card-body box-profile">
			                		<div class="text-center">
			                  			<img src="module/foto_pengajar/medium_<?= $d['foto'];?>" width="100%" alt="User profile picture"><br><h5><?= $d['nama_lengkap'];?></h5>
			                		</div>
			                	</div>
			           	 	</div>
		           		</div>
		           		<div class="col-sm-9">

			           		<div class="card card-primary">
				              	<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				                	<h5>Detail Data</h5>
				             	</div><!-- /.card-header -->

					            <div class="card-body">
				                  	<div class="form-group row">
				                        <div class="col-sm-3"><b>NIP/NUPTK </b></div>
				                        <div class="col-sm-6">: <?= $d['nip'];?></div>
				                    </div>
				                  	<div class="form-group row">
				                        <div class="col-sm-3"><b>Nama Lengkap </b></div>
				                        <div class="col-sm-6">: <?= $d['nama_lengkap'];?></div>
				                    </div>
				                    <div class="form-group row">
				                        <div class="col-sm-3"><b>Jenis Kelamin </b></div>
				                        <div class="col-sm-6">: <?= $d['jenis_kelamin'];?></div>
				                    </div>
				                    <div class="form-group row">
				                        <div class="col-sm-3"><b>TTL </b></div>
				                        <div class="col-sm-6">: <?= $d['tempat_lahir'].', '.tgl_indo($d['tgl_lahir']);?></div>
				                    </div>
				                    <div class="form-group row">
				                        <div class="col-sm-3"><b>Alamat </b></div>
				                        <div class="col-sm-6">: <?= $d['alamat'];?></div>
				                    </div>
				                    <div class="form-group row">
				                        <div class="col-sm-3"><b>No Telp/HP(WA)  </b></div>
				                        <div class="col-sm-6">: <?= $d['no_telp'];?></div>
				                    </div>
				                  	<div class="form-group row">
				                  		<div class="col-sm-3"><b>Tahun Masuk  </b></div>
				                        <div class="col-sm-6">: <?= $d['th_masuk'];?></div>
				                    </div>
				                    <div class="form-group row">
				                        <div class="col-sm-3"><b>Jabatan  </b></div>
				                        <div class="col-sm-6">: <?= $d['jabatan'];?></div>
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
break;

case 'save':
include 'save.php';
break;
	
	}
}
?>
