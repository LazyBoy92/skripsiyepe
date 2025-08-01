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

      		$sql_data = mysqli_query($koneksi,"SELECT DISTINCT a.*,b.nama_mapel,c.nama_lengkap FROM file_materi a, m_mapel b, guru c WHERE a.id_mapel = b.id_mapel AND a.pembuat=c.id AND a.tgl_posting BETWEEN '$thn_lalu' AND '$thn_skrg' ORDER BY a.id_file DESC");

      		$no=1;
?>
<div class="row">
	<div class="col-md-12">
		<div class="card shadow">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
		  		<h6 class="m-0 font-weight-bold text-primary">Daftar Materi Pelajaran</h6>
		  		 <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <button class="btn-sm btn-primary rounded" data-toggle="modal" data-target="#tambah_data"><i class="fas fa-plus"></i> Data</button>
                    </a>
			  	</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped" id="table_1"  width="100%" cellspacing="0" cellpadding="0" style="font-size: 13px">
						<thead>
							<tr>
								<th>NO</th>
								<th>Judul</th>
								<th>Mapel</th>
								<th>Kelas</th>
								<th>Pembuat</th>
								<th>File</th>
								<th>Edit</th>
								<th>Hapus</th>
							</tr>
						</thead>
						<tbody>
						<?php 
							while($r=mysqli_fetch_array($sql_data)){
								$file      ='module/files_materi/'.$r[nama_file];
				                $info_file = pathinfo($file);
				                $ext       = $info_file['extension'];
				                  
				                  if($ext=='doc' or $ext=='docx'){
				                    $jenis_file='<i class="fas fa-file-word"><i>';
				                  }
				                  elseif($ext=='xls' or $ext=='xslx'){
				                    $jenis_file='<i class="fas fa-file-excel"><i>';
				                  }
				                  elseif($ext=='ppt' or $ext=='pptx'){
				                    $jenis_file='<i class="fas fa-file-powerpoint"><i>';
				                  }
				                  elseif($ext=='pdf'){
				                    $jenis_file='<i class="fas fa-file-pdf"><i>';
				                  }

				                $kelas=mysqli_query($koneksi,"SELECT a.nama_kelas, b.id_kelas FROM m_kelas a, file_materi_det b  WHERE a.id_kelas =b.id_kelas AND  b.id_file ='$r[id_file]'");
						?>
							<tr>
								<td><?= $no;?></td>
			    				<td><?= $r['judul'];?></td>
			    				<td><?= $r['nama_mapel'];?></td>
			    				<td>
			                      <ol class="ml-0">
			                        <?php foreach ($kelas as $kls ) {
			                          ?>
			                        <li><?= $kls['nama_kelas'];?></li>
			                        <?php } ?>
			                        
			                      </ol>
			                    </td>
			    				<td><?= $r['nama_lengkap'];?></td>
			    				<td><a href="module/files_materi/<?=$r['nama_file'];?>" class="btn-sm btn-info"><?= $jenis_file;?></a></td>
			    				<td align="center"><a href="#" class="btn-sm btn-warning" data-toggle="modal" data-target="#edit_<?= $r['id_file'];?>"><i class="fas fa-edit"></i></a></td>
			    				<td align="center"><a href="javascript:confirmdelete('?module=f_materi&act=save&aksi=hapus&id=<?= $r['id_file'];?>')" class="btn-sm btn-danger"><i class="fas fa-trash"></i></a></td>
			    			</tr>

			    			<!-- Modal Edit-->
							<div class="modal fade" id="edit_<?= $r['id_file'];?>"  role="dialog" >
							  	<div class="modal-dialog modal-lg" role="document">
							  		<form action="?module=f_materi&act=save" method="POST" role="form" enctype="multipart/form-data">
								    	<div class="modal-content">
								      		<div class="modal-header">
								        		<h4 class="modal-title" id="myModalLabel">Edit Materi Pelajaran</h4>
								      		</div>
							      			
									      	<div class="modal-body">
									      		<input type="hidden" name="id_file" value="<?= $r['id_file'];?>">
										        
										        <div class="form-group">
										          	<label for="">Judul</label>
										          	<input type="text" name="judul" class="form-control" value="<?=$r['judul'];?>">
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

										        <div class="form-group">
					                              	<label for="">Kelas</label>
					                                <select name="id_kelas[]" class="select2 form-control" multiple="multiple">
					                                  <?php 
					                                    $kelas1 = mysqli_query($koneksi,"SELECT a.nama_kelas, b.id_kelas FROM m_kelas a, file_materi_det b WHERE a.id_kelas=b.id_kelas AND b.id_file = '$r[id_file]'");
					                                    $kelas2 = mysqli_query($koneksi,"SELECT * FROM m_kelas ORDER BY id_kelas ASC");

					                                      while($kls=mysqli_fetch_array($kelas1)){
					                                  ?>
					                                    <option value="<?=$kls['id_kelas'];?>" selected><?=$kls['nama_kelas'];?></option>
					                                  
					                                  <?php 
					                                      }
					                                  
					                                  while ($kls2=mysqli_fetch_array($kelas2)) {
					                                    echo "<option value='$kls2[id_kelas]'>$kls2[nama_kelas]</option>";
					                                  }

					                                   ?>
					                                </select>
					                           </div>

										        <div class="form-group">
										            <label for="">Pembuat Materi</label>
										            <select name="pembuat" class="select2 form-control" required="required">
										            	<option value="<?= $r['pembuat'];?>"> <?= $r['nama_lengkap'];?> </option>
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
										          	<label for="">File Terupload</label>
										          	<p><a href="module/files_materi/<?=$r['nama_file'];?>" class="btn btn-warning rounded"><i class="fas fa-file"></i>&nbsp;<?=$r['nama_file'];?></a></p>
										        </div>
										        <div class="form-group">
										          	<label for="">Ganti File</label>
										          	<input type="file" name="fupload" class="form-control">
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
<div class="modal fade" id="tambah_data"  role="dialog" >
  	<div class="modal-dialog modal-lg" role="document">
  		<form action="?module=f_materi&act=save" method="POST" role="form" enctype="multipart/form-data">
    		<div class="modal-content">
	      		<div class="modal-header">
	        		<h4 class="modal-title" id="myModalLabel">Materi Pelajaran</h4>
	      		</div>
	      	
		      	<div class="modal-body">
		      		<div class="form-group">
			          	<label for="">Judul</label>
			          	<input type="text" name="judul" class="form-control" value="<?=$r['judul'];?>">
			        </div>

			        <div class="form-group">
			            <label for="">Pilih Mata Pelajaran</label>
			            <select name="id_mapel" class="select2 form-control" required="required">
			                <option value=""> --Cari Mapel-- </option>
			            <?php 
			               	$mpl=mysqli_query($koneksi,"SELECT id_mapel,nama_mapel FROM m_mapel ORDER BY id_mapel ASC ");
			                    
			                    while($dmp=mysqli_fetch_array($mpl)) {
			                        
			                        echo '<option value='.$dmp['id_mapel'].'>'.$dmp['nama_mapel'].'</option>';
			                    }
			            ?>
			            </select>
			        </div>

			        <div class="form-group">
		              	<label for="">Kelas</label>
		                <select name="id_kelas[]" class="select2 form-control" multiple="multiple" required="required">
		                  <option value="">-- Cari Kelas--</option>
		                  <?php 
		                    $kelas1 = mysqli_query($koneksi,"SELECT * FROM m_kelas ORDER BY id_kelas ASC");
		                  
		                  while ($kls=mysqli_fetch_array($kelas1)) {
		                    echo "<option value='$kls[id_kelas]'>$kls[nama_kelas]</option>";
		                  }

		                   ?>
		                </select>
		           </div>

			        <div class="form-group">
			            <label for="">Pembuat Materi</label>
			            <select name="pembuat" class="select2 form-control" required="required">
			            	<option value=""> --Pembuat Materi-- </option>
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
			          	<label for="">File Materi</label>
			          	<input type="file" name="fupload" class="form-control">
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
