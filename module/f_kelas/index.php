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
      		//echo '2'.$tahun_p;
?>
<div class="row">
	<div class="col-md-12">
		<div class="card shadow">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
		  		<h6 class="m-0 font-weight-bold text-primary">Data Siswa Per Kelas TP <?= $tahun_p;?></h6>
		  		 <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    </a>
			  	</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped" id="table_1"  width="100%" cellspacing="0" cellpadding="0">
						<thead>
							<tr style="text-align: center;">
								<th>No</th>
								<th>Id Kelas</th>
								<th>Nama Kelas</th>
								<th>Jumlah Siswa</th>
								<th align="center">View</th>
							</tr>
						</thead>
						<tbody>
						<?php 
							$sql_data = mysqli_query($koneksi,"SELECT a.*, COUNT(b.nis) as jum FROM m_kelas a LEFT JOIN f_kelas b ON a.id_kelas = b.id_kelas AND b.tp='$tahun_p' GROUP BY a.id_kelas ORDER BY a.nama_kelas");
							$no=1;
							while($r=mysqli_fetch_array($sql_data)){
								// $jum_sis = mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM f_kelas WHERE id_kelas='$r[id_kelas]' AND tp = '$tahun_p'"));
						?>
							<tr>
								<td><?php echo $no;?></td>
			    				<td><?php echo $r['id_kelas'];?></td>
			    				<td><?php echo $r['nama_kelas'];?></td>
			    				<td><?php echo $r['jum'];?></td>
			    				<td align="center"><a href="?module=f_kelas&act=detail&id_kelas=<?=$r['id_kelas'];?>" class="btn-sm btn-info"><i class="fas fa-binoculars"></i></a></td>
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
<?php 
}
break;
case "detail":

$id_kelas = $_GET['id_kelas'];
$a = mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_kelas FROM m_kelas WHERE id_kelas ='$id_kelas'"));
?>
<div class="row">
	<div class="col-md-12">
		<div class="card shadow">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
		  		<h6 class="m-0 font-weight-bold text-primary">Data Siswa Kelas <?= $a['nama_kelas'];?> TP <?=$tahun_p;?></h6>
		  		 <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    </a>
                    <a href="?module=f_kelas" role="button"><button class="btn-sm btn-warning rounded"><i class="fas fa-arrow-alt-circle-left"></i> Back</button></a>
                    <a href="module/f_kelas/form_kelas.xls" target="_blank" role="button">
                      <button class="btn-sm btn-success rounded"><i class="fas fa-file-excel"></i> Form Siswa</button>
                    </a>
                    <a  href="#" role="button">
                      <button class="btn-sm btn-primary rounded" data-toggle="modal" data-target="#upload_data"><i class="fas fa-upload"></i> Upload Data</button>
                    </a>
                    <a  href="#" role="button">
                      <button class="btn-sm btn-danger rounded" data-toggle="modal" data-target="#hapus_data"><i class="fas fa-times"></i> Hapus Data</button>
                    </a>
			  	</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped" id="table_1"  width="100%" cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								<td>No</td>
								<td>NISN</td>
								<td>Nama Lengkap Siswa</td>
								<!-- <th></th> -->
							</tr>
						</thead>
						<tbody>
							<?php 
								$sql_data = mysqli_query($koneksi,"SELECT DISTINCT a.*,b.nama_lengkap FROM f_kelas a, siswa b WHERE a.nis=b.nis AND a.id_kelas = '$_GET[id_kelas]' AND a.tp='$tahun_p' ORDER BY b.nama_lengkap ASC");
								$no=1;
								while($r=mysqli_fetch_array($sql_data)){
							?>
								<tr>
									<td><?php echo $no++;?></td>
				    				<td><?php echo $r['nis'];?></td>
				    				<td><?php echo $r['nama_lengkap'];?></td>
				    				<!-- <td align="center"><a href="#" data-toggle="modal" data-target= class="btn-sm btn-info"><i class="fas fa-search"></i></a></td> -->
				    			</tr>
							<?php
								// $no++;
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
    	<form action="?module=f_kelas&act=save" method="POST" role="form" enctype="multipart/form-data">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<h4 class="modal-title" id="myModalLabel">Upload Data</h4>
	      		</div>
		      	<div class="modal-body">
			         <div class="form-group">
			          	<label for="">Upload File</label>
			          	<input type="file" class="form-control" name="fkelas">
			          	<input type="hidden" class="form-control" name="id_kelas" value="<?= $id_kelas;?>">
			         </div>
		      	</div>
		      	<div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        	<button type="submit" name="upload_kelas" class="btn btn-primary">Simpan</button>
		      	</div>
    		</div>
    	</form>
  	</div>
</div>
<!--end modal-->

<!-- Modal Hapus-->
<div class="modal fade" id="hapus_data"  role="dialog" >
  	<div class="modal-dialog modal-sm" role="document">
    	<form action="?module=f_kelas&act=save" method="POST" role="form" enctype="multipart/form-data">
	    	<div class="modal-content bg-danger text-white">
	      		<div class="modal-header">
	        		
	      		</div>
		      	<div class="modal-body">
		      		<h4 class="modal-title" id="myModalLabel">Yakin akan Hapus Data</h4>
			         <div class="form-group">
			          	<input type="hidden" class="form-control" name="id_kelas" value="<?= $id_kelas;?>">
			          	<input type="hidden" class="form-control" name="tp" value="<?= $tahun_p;?>">
			         </div>
		      	</div>
		      	<div class="modal-footer">
		        	<button type="button" class="btn btn-default text-white" data-dismiss="modal">Close</button>
		        	<button type="submit" name="hapus_siswa_kelas" class="btn btn-primary">Hapus</button>
		      	</div>
    		</div>
    	</form>
  	</div>
</div>
<!--end modal-->
<?php
break;

case 'save':
include 'save.php';
break;
?>

<?php 
	}
}
?>
