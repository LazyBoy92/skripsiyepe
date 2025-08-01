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
		  		<h6 class="m-0 font-weight-bold text-primary">Data Master Kelas</h6>
		  		 <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <button class="btn-sm btn-primary rounded" data-toggle="modal" data-target="#tambah_kelas"><i class="fas fa-plus"></i> Tambah Data</button>
                    </a>
			  	</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped" id="table_1"  width="100%" cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								<td>No</td>
								<td>Id Kelas</td>
								<td>Nama Kelas</td>
								<td>Edit</td>
								<td>Hapus</td>
							</tr>
						</thead>
						<tbody>
						<?php 
							$sql_data = mysqli_query($koneksi,"SELECT * FROM m_kelas ORDER BY nama_kelas ASC");
							$no=1;
							while($r=mysqli_fetch_array($sql_data)){
						?>
							<tr>
								<td><?php echo $no;?></td>
			    				<td><?php echo $r['id_kelas'];?></td>
			    				<td><?php echo $r['nama_kelas'];?></td>
			    				<td align="center"><a href="#" class="btn-sm btn-warning rounded" data-toggle="modal" data-target="#edit_<?= $r['id_kelas'];?>"><i class="fas fa-edit"></i></a></td>
			    				<td align="center"><a href="javascript:confirmdelete('?module=m_kelas&act=save&aksi=hapus&id=<?=  $r['id_kelas'];?>')" class="btn-sm btn-danger rounded"><i class="fas fa-trash"></i></a></td>
			    			</tr>

			    			<!-- Modal Edit-->
							<div class="modal fade" id="edit_<?= $r['id_kelas'];?>"  role="dialog" >
							  	<div class="modal-dialog modal-lg" role="document">
							    	<form action="?module=m_kelas&act=save" method="POST" role="form">
								    	<div class="modal-content">
								      		<div class="modal-header">
								        		<h4 class="modal-title" id="myModalLabel">Edit Master Kelas</h4>
								      		</div>
									      	<div class="modal-body">
										        <div class="form-group">
										          	<label for="">Nama Mata Pelajaran</label>
										          	<input type="hidden" class="form-control" name="id_kelas" value="<?= $r['id_kelas'];?>">
										          	<input type="text" class="form-control" name="nama_kelas" value="<?= $r['nama_kelas'];?>">
										         </div>
									      	</div>
									      	<div class="modal-footer">
									        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									        	<button type="submit" name="update" class="btn btn-primary">Update</button>
									      	</div>
								    	</div>
							    	</form>
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

<!-- Modal Tambah-->
<div class="modal fade" id="tambah_kelas"  role="dialog" >
  	<div class="modal-dialog modal-lg" role="document">
    	<form action="?module=m_kelas&act=save" method="POST" role="form">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<h4 class="modal-title" id="myModalLabel">Tambah Master Kelas</h4>
	      		</div>
	      	
	      		<div class="modal-body">
		         	<div class="form-group">
		          		<label for="">Kode Kelas</label>
		          		<input type="text" class="form-control" name="id_kelas" value="" placeholder="isikan kode kelas ex.. mpl001">
		         	</div>
		         	<div class="form-group">
		          		<label for="">Nama Kelas</label>
		          		<input type="text" class="form-control" name="nama_kelas" value="" placeholder="Nama Kelas">
		         	</div>
	      		</div>

		      	<div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        	<button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
		      	</div>
	    	</div>
		</form>
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
