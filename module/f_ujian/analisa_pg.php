<!-- CONFIRM DELETE-->
<script type="text/javascript">
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
header('location:../index.php');
	}
else{
      if ($_SESSION['leveluser']=='admin' OR $_SESSION['leveluser']=='user_guru'){
      $id_ujian = $_GET['ujian'];
      $id_siswa = $_GET['siswa'];
      $sql_head=mysqli_query($koneksi,"SELECT * FROM topik_ujian WHERE id='$id_ujian'");
      $sql_data=mysqli_query($koneksi,"SELECT DISTINCT a.pertanyaan,a.kunci,b.jawaban FROM soal_pilganda a, analisis b WHERE a.id_soalpg=b.id_soal AND b.id_ujian='$id_ujian' AND b.id_siswa='$id_siswa'");
      
      $r=mysqli_fetch_array($sql_head);
      $r2=mysqli_fetch_array(mysqli_query($koneksi,"SELECT jml_benar,jml_kosong,jml_salah FROM nilai WHERE id_ujian='$id_ujian' AND id_siswa='$id_siswa'"));

      
?>
<div class="row">
      <div class="col-md-8">
            <div class="card shadow">
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Jawaban Pilihan Ganda</b></h6>
                        <div class="dropdown no-arrow">
                              <a href="?module=f_ujian&act=detail_peserta&id=<?=$id_ujian;?>" class="btn-sm btn-warning"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
                        </div>
                  </div>
                  <div class="card-body">
                        <div class="table-responsive">
                              <table class="table table-bordered table-striped" id="table_1"  width="100%" cellspacing="0" cellpadding="0">
                                    <thead>
                                          <tr align="center" class="bg-info" style="color: white;">
                                                <th>NO</th>
                                                <th>Soal</th>
                                                <th>Kunci</th>
                                                <th>Jawaban</th>
                                                <th>Analisa</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php 
                                          $no = 1;
                                          while($row=mysqli_fetch_array($sql_data)) {
                                           
                                           if($row['kunci']==1){$kunci="A";}
                                           elseif($row['kunci']==2){$kunci="B";}
                                           elseif($row['kunci']==3){$kunci="C";}
                                           elseif($row['kunci']==4){$kunci="D";}
                                           elseif($row['kunci']==5){$kunci="E";}

                                           if($row['jawaban']==1){$jawaban="A";}
                                           elseif($row['jawaban']==2){$jawaban="B";}
                                           elseif($row['jawaban']==3){$jawaban="C";}
                                           elseif($row['jawaban']==4){$jawaban="D";}
                                           elseif($row['jawaban']==5){$jawaban="E";}

                                           if($row['kunci']==$row['jawaban']){
                                            $analisa = "<b class='text-success'> Benar</b>";
                                           }
                                           else {
                                            $analisa = "<b class='text-danger'> Salah</b>";
                                           }


                                           //$jwb_benar= "SELECT COUNT(IF(jawaban) ="
                                           ?>
                                          
                                          <tr>
                                                <td><?=$no;?></td>
                                                <td><?=$row['pertanyaan'];?></td>
                                                <td align="center"><?=$kunci;?></td>
                                                <td align="center"><?=$jawaban;?></td>
                                                <td align="center"><?=$analisa;?> </td>
                                          </tr>
                                          <!-- Modal Koreksi-->
                                            <div class="modal fade" id="koreksi_<?= $row['id_nesay'];?>"  role="dialog" >
                                              <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                      <h4 class="modal-title" >Koreksi Jawaban</h4>
                                                    </div>
                                                  <div class="modal-body" align="justify-content-between">
                                                  <?php 
                                                    
                                                  ?>
                                                      <fieldset style="border: 1px;">
                                                            <div class="form-group">
                                                                  <label>Soal : </label>
                                                                  <h6 class="font-weight-bold text-danger"><?= $row['pertanyaan'];?></h6>
                                                                  <br><img src="module/foto_soal/medium_<?=$row['gambar'];?>">
                                                            </div>
                                                            <div class="form-group">
                                                                  <label>Jawaban :</label> 
                                                                  <h6 class="font-weight-bold text-primary"><?= $row['jawaban_esay'];?></h6>
                                                            </div>
                                                      </fieldset>
                                                  </div>
                                                  <div class="modal-footer">

                                                      <form method="POST" action="?module=f_ujian&act=save" enctype="multipart/form-data">

                                                      <div class="col-sm-6 text-left">
                                                            <div class="form-group">
                                                                  <label>Nilai (diambil dari Nilai 100 / Jumlah Soal)</label>
                                                                  <input type="hidden" name="id_nesay" value="<?= $row['id_nesay'];?>">
                                                                  <input type="hidden" name="ujian" value="<?= $id_ujian;?>">
                                                                  <input type="hidden" name="siswa" value="<?= $id_siswa;?>">
                                                                  <select name="nilai" class="select2 form-control" required="required">
                                                                        <option value="">--Pilih Nilai--</option>

                                                                        <?php 
                                                                        for ($i=0; $i <=$xb ; $i++) { 
                                                                              echo "<option value=$i>$i</option>";
                                                                        }
                                                                         ?>
                                                                  </select>
                                                            </div>
                                                      </div>
                                                      <div class="col-sm-6 text-right">
                                                            <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                                                            <button type="submit" name="proses_koreksi" class="btn btn-primary">Proses</button>
                                                      </div>
                                                    
                                                  </form>
                                                  </div>
                                                  
                                              </div>
                                            </div>
                                            </div><!--end modal-->
                                          <?php $no++; 
                                    } ?>
                                    </tbody>
                                    <tfoot>
                                          <tr>
                                                <td colspan="7">
                                                <ul>
                                                  <li>Jumlah Soal Benar : <?=$r2['jml_benar'];?></li>
                                                  <li>Jumlah Soal Salah : <?=$r2['jml_salah'];?></li>
                                                  <li>Tidak Dijawab : <?=$r2['jml_kosong'];?></li>
                                                </ul>    
                                                </td>
                                          </tr>
                                    </tfoot>
                              </table>
                        </div>
                  </div>
                  <div class="card-footer">
                  </div>      
            </div>
      </div>
      <div class="col-lg-4">
            <div class="card bg-deafult shadow">
                  <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Data Siswa</h6>
                  </div>
                  <div class="card-body">
                        <?php 

                        $rs=mysqli_fetch_array(mysqli_query($koneksi,"SELECT a.*,b.nama_kelas,c.id_kelas FROM siswa a, m_kelas b, f_kelas c WHERE a.nis=c.nis AND b.id_kelas=c.id_kelas AND a.id='$id_siswa'"));
                        //echo "SELECT a.*,b.nama_kelas,c.id_kelas FROM siswa a, m_kelas b, f_kelas c WHERE a.nis=b.nis AND b.id_kelas=c.id_kelas AND a.id='$id_siswa'";

                         ?>
                        
                        <table class="table table-bordered">
                              <tr><td colspan="2" align="center"><img src="module/foto_siswa/medium_<?=$rs['foto'];?>" width="100%"></td></tr>
                              <tr><td>NIS</td><td><?=$rs['nis'];?></td></tr>
                              <tr><td>Nama Lengkap</td><td><?=$rs['nama_lengkap'];?></td></tr>
                              <tr><td>Kelas</td><td><?=$rs['nama_kelas'];?></td></tr>
                        </table>
                        
                  </div>
                  <div class="card-footer">

                  </div>
            </div>
      </div>
</div>

<?php
      }
}
?>