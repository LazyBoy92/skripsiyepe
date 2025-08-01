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
		  		<h6 class="m-0 font-weight-bold text-primary">Data Guru Mata Pelajaran Tahun Pelajaran : <?= $tahun_p;?> </h6>
		  		 <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <button class="btn-sm btn-primary rounded" data-toggle="modal" data-target="#tambah_data"><i class="fas fa-plus"></i> Data</button>
                    </a>
			  	</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped" id="table_1"  width="100%" cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								<th>NO</th>
								<th>Kelas</th>
								<th>Nama Mapel</th>
								<th>Pengajar</th>
								<th>Edit</th>
								<th>Hapus</th>
							</tr>
						</thead>
						<tbody>
						<?php 
							$sql_data = mysqli_query($koneksi,"SELECT a.*,b.nama_mapel,c.nama_lengkap,d.nama_kelas FROM f_mapel a, m_mapel b, guru c,m_kelas d WHERE a.id_mapel = b.id_mapel AND a.nip=c.id AND a.id_kelas =d.id_kelas AND a.tp='$tahun_p' ORDER BY id DESC");
							$no=1;
							while($r=mysqli_fetch_array($sql_data)){
						?>
							<tr>
								<td><?php echo $no;?></td>
								<td><?php echo $r['nama_kelas'];?></td>
			    				<td><?php echo $r['nama_mapel'];?></td>
			    				<td><?php echo $r['nama_lengkap'];?></td>
			    				<td align="center"><a href="#" class="btn-sm btn-warning" data-toggle="modal" data-target="#edit<?php echo $r['id'];?>"><i class="fas fa-edit"></i></a></td>
			    				<td align="center"><a href="javascript:confirmdelete('?module=f_mapel&act=save&aksi=hapus&id=<?=$r['id'];?>')" class="btn-sm btn-danger"><i class="fas fa-trash"></i></a></td>
			    			</tr>

			    			<!-- Modal Edit-->
							<div class="modal fade" id="edit<?= $r['id'];?>"  role="dialog" >
							  	<div class="modal-dialog modal-lg" role="document">
							    	<form action="?module=f_mapel&act=save" method="POST" role="form">
								    	<div class="modal-content">
								      		<div class="modal-header">
								        		<h4 class="modal-title" id="myModalLabel">Edit Mata Pelajaran</h4>
								      		</div>
								      	
									      	<div class="modal-body">
									      		<input type="hidden" name="id_data" value="<?=$r['id'];?>">
									      		<div class="form-group">
										            <label for="">Cari Guru</label>
										            <select name="nip" class="select2 form-control" required="required">
										            	<option value="<?= $r['nip'];?>"> <?= $r['nama_lengkap'];?> </option>
										                <?php 
										                    $guru=mysqli_query($koneksi,"SELECT id,nama_lengkap,jabatan FROM guru ORDER BY nama_lengkap ASC ");
										                    while($dgr=mysqli_fetch_array($guru)) {
										                    	?>
										                        <option value="<?=$dgr['id'];?>"><?= $dgr['nama_lengkap'];?> <i class="text-danger">(<?=$dgr['jabatan'];?>)</i></option>';
										                        <?php
										                      }
										                ?>
										            </select>
										        </div>

									      		<div class="form-group">
										            <label for="">Pilih Kelas</label>
										            <select name="id_kelas" class="select2 form-control" required="required">
										            	<option value="<?= $r['id_kelas'];?>"> <?= $r['nama_kelas'];?> </option>
										                <?php 
										                    $kls=mysqli_query($koneksi,"SELECT id_kelas,nama_kelas FROM m_kelas ORDER BY id_kelas ASC ");
										                    while($dkl=mysqli_fetch_array($kls)) {
										                        echo '<option value='.$dkl['id_kelas'].'>'.$dkl['nama_kelas'].'</option>';
										                      }
										                ?>
										            </select>
										        </div>

										        <div class="form-group">
										            <label for="">Pilih Mata Pelajaran</label>
										            <select name="id_mapel" class="select2 form-control" required="required">
										                <option value="<?= $r['id_mapel'];?>"> <?= $r['nama_mapel'];?> </option>
										            <?php 
										               	$mpl=mysqli_query($koneksi,"SELECT id_mapel,nama_mapel FROM m_mapel ORDER BY id_mapel ASC ");
										                    
										                    while($dmp=mysqli_fetch_array($mpl)) {
										                        
										                        echo '<option value='.$dmp['id_mapel'].'>'.$dmp['nama_mapel'].'</option>';
										                    }
										            ?>
										            </select>
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
							<tr>
								<th>NO</th>
								<th>Kelas</th>
								<th>Nama Mapel</th>
								<th>Pengajar</th>
								<th>Edit</th>
								<th>Hapus</th>
							</tr>
						</tfoot>
					</table>
				</div>	
			</div>
		</div>
	</div>
</div>

<!-- Modal Tambah-->
<div class="modal fade" id="tambah_data"  role="dialog" >
  	<div class="modal-dialog modal-lg" role="document">
    	<form action="?module=f_mapel&act=save" method="POST" role="form">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<h4 class="modal-title" id="myModalLabel">Guru Mata Pelajaran</h4>
	      		</div>
	      	
		      	<div class="modal-body">
		      		<input type="hidden" name="tp" value="<?= $tahun_p;?>">
		      		<div class="form-group">
			            <label for="">Cari Guru</label>
			            <select name="nip" class="select2 form-control" required="required">
			            	<option value=""> Cari Pengajar / Guru </option>
			                <?php 
			                    $guru=mysqli_query($koneksi,"SELECT id,nama_lengkap,jabatan FROM guru ORDER BY nama_lengkap ASC ");
			                    while($dgr=mysqli_fetch_array($guru)) {
			                    	?>
			                        <option value="<?=$dgr['id'];?>"><?= $dgr['nama_lengkap'];?> <i class="text-danger">(<?=$dgr['jabatan'];?>)</i></option>';
			                        <?php
			                      }
			                ?>
			            </select>
			        </div>

		      		<div class="form-group">
			            <label for="">Pilih Kelas</label>
			            <select name="id_kelas" class="select2 form-control" required="required">
			            	<option value=""> Cari Kelas </option>
			                <?php 
			                    $kls=mysqli_query($koneksi,"SELECT id_kelas,nama_kelas FROM m_kelas ORDER BY id_kelas ASC ");
			                    while($dkl=mysqli_fetch_array($kls)) {
			                        echo '<option value='.$dkl['id_kelas'].'>'.$dkl['nama_kelas'].'</option>';
			                      }
			                ?>
			            </select>
			        </div>

			        <div class="form-group">
			            <label for="">Pilih Mata Pelajaran</label>
			            <select name="id_mapel" class="select2 form-control" required="required">
			                <option value=""> Cari Mata Pelajaran </option>
			            <?php 
			               	$mpl=mysqli_query($koneksi,"SELECT id_mapel,nama_mapel FROM m_mapel ORDER BY id_mapel ASC ");
			                    
			                    while($dmp=mysqli_fetch_array($mpl)) {
			                        
			                        echo '<option value='.$dmp['id_mapel'].'>'.$dmp['nama_mapel'].'</option>';
			                    }
			            ?>
			            </select>
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
