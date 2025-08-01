<script>
function confirmdelete(delUrl) {
if (confirm("Anda yakin ingin menghapus?")) {
document.location = delUrl;
}
}
</script>
<?php 
 ?>
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
  if ($_SESSION['leveluser']=='user_guru'){
  //CARI MAPEL
    


?>
<div class="row">
  <div class="col-md-12">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Soal yang anda buat</h6>
       <div class="dropdown no-arrow">
          <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <button class="btn-sm btn-primary" data-toggle="modal" data-target="#tambah_soal"><i class="fas fa-plus"></i> Add Soal </button>
            <button class="btn-sm btn-info" data-toggle="modal" data-target="#upload_excel"><i class="fas fa-upload"></i> Upload Excel </button>
            <a href="module/ug_bank/bank_pilganda.xls" target="_blank"><button class="btn-sm btn-success"><i class="fas fa-file-excel"></i> Contoh Excel </button></a>
          </a>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="card card-solid">
      <div class="card-header with-border">
      </div>
      <div class="card-body" style="font-size: 13px;">
        <div class="table-responsive">
          <table class="table table-striped" id="table_1">
            <thead>
              <tr><th width="5%">No</th><th width="25%">Soal</th><th width="10">Mapel</th><th width="10%">Pil A</th><th width="10%">Pil B</th><th width="10%">Pil C</th><th width="10%">Pil D</th><th width="10%">Pil E</th><th width="10%">Kunci</th><th width="3%"><i class="fas fa-edit"></i></th><th width="3%"><i class="fas fa-trash"></i></th></tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $sql_data=mysqli_query($koneksi,"SELECT * FROM bank_pilganda WHERE pembuat = '$_SESSION[id_user]' ORDER BY id DESC");
                //echo "SELECT * FROM bank_pilganda WHERE pembuat = '$_SESSION[id_user]'";
              foreach ($sql_data as $r ) {
                //KUNCI ANGKA KE HURUF
                
                if($r['kunci']==1){$kunci = 'A';} elseif($r['kunci']==2){$kunci = 'B';} elseif($r['kunci']==3){$kunci = 'C';} elseif($r['kunci']==4){$kunci = 'D';} else{$kunci = 'E';}
                $d=mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_mapel FROM m_mapel WHERE id_mapel = '$r[id_mapel]'")); 
              ?>
              <tr>
                <td><?= $no;?></td>
                <td><?= $r['pertanyaan'];?></td>
                <td><?= $d['nama_mapel'];?></td>
                <td><?= $r['pil_a'];?></td>
                <td><?= $r['pil_b'];?></td>
                <td><?= $r['pil_c'];?></td>
                <td><?= $r['pil_d'];?></td>
                <td><?= $r['pil_e'];?></td>
                <td><?= $kunci;?></td>
                <td><a href="#" data-toggle="modal" data-target="#edit_<?=$r['id'];?>"><i class="fas fa-edit"></i></a></td>
                <td><a href="javascript:confirmdelete('?module=ug_bank&act=save&post=hapus&id=<?=$r['id'];?>')"><i class="fas fa-trash"></i></a></td>
              </tr>

              <!-- Modal Add Soal Pilihan Ganda-->
              <div class="modal fade" id="edit_<?=$r[id];?>"  role="dialog" >
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                      <h4 class="modal-title" id="myModalLabel">Tambahkan ke Bank Soal Pilihan Ganda</h4>
                      </div>
                    <form action="?module=ug_bank&act=save" method="POST" role="form" enctype="multipart/form-data">
                    <div class="modal-body">

                      <div class="form-group">
                        <label for="">Pertanyaan</label>
                          <input type="hidden" class="form-control" name="id" value="<?= $r['id'];?>">
                          <textarea name="pertanyaan"  cols="75" rows="3" class="editor_soal"><?=$r['pertanyaan'];?></textarea>
                      </div>

                      <div class="form-group">
                        <label for="">Jawaban A</label>
                        <textarea name="pil_a"  cols="75" rows="3" class="editor"><?=$r['pil_a'];?></textarea>
                        
                      </div>

                      <div class="form-group">
                        <label for="">Jawaban B</label>
                        <textarea name="pil_b"  cols="75" rows="3" class="editor"><?=$r['pil_b'];?></textarea>
                      </div>

                      <div class="form-group">
                        <label for="">Jawaban C</label>
                        <textarea name="pil_c"  cols="75" rows="3" class="editor"><?=$r['pil_c'];?></textarea>
                      </div>

                      <div class="form-group">
                        <label for="">Jawaban D</label>
                        <textarea name="pil_d"  cols="75" rows="3" class="editor"><?=$r['pil_d'];?></textarea>
                      </div>

                      <div class="form-group">
                        <label for="">Jawaban E</label>
                        <textarea name="pil_e"  cols="75" rows="3" class="editor"><?=$r['pil_e'];?></textarea>
                      </div>

                      <div class="form-group">
                        <label for="">Kunci Jawaban</label>
                        <select name="kunci" class="form-control">
                          <?php 
                            if($dt['kunci']==1){$kunci = 'A';} elseif($dt['kunci']==2){$kunci = 'B';} elseif($dt['kunci']==3){$kunci = 'C';} elseif($dt['kunci']==4){$kunci = 'D';} else{$kunci = 'E';}   
                          ?>
                          <option value="<?= $dt['kunci'];?>">Pilihan <?=$kunci;?></option>
                          <option value="1">Pilihan A</option>
                          <option value="2">Pilihan B</option>
                          <option value="3">Pilihan C</option>
                          <option value="4">Pilihan D</option>
                          <option value="5">Pilihan E</option>
                        </select>
                      </div>  

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                      <button type="submit" name="edit_pilganda" class="btn btn-primary">Simpan</button>
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
              <tr><th width="5%">No</th><th width="25%">Soal</th><th>Mapel</th><th width="10%">Pil A</th><th width="10%">Pil B</th><th width="10%">Pil C</th><th width="10%">Pil D</th><th width="10%">Pil E</th><th width="10%">Kunci</th><th width="5%"><i class="fas fa-edit"></i></th><th width="5%"><i class="fas fa-trash"></i></th></tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Add Soal Pilihan Ganda-->
<div class="modal fade" id="tambah_soal"  role="dialog" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Tambahkan ke Bank Soal Pilihan Ganda</h4>
        </div>
      <form action="?module=ug_bank&act=save" method="POST" role="form" enctype="multipart/form-data">
      <div class="modal-body">
      <?php
      $sql_mapel = mysqli_query($koneksi,"SELECT DISTINCT id_mapel FROM f_mapel WHERE nip = '$_SESSION[id_user]'"); 
      ?>
        <div class="form-group">
          <label>Mata Pelajaran</label>
            <select name="id_mapel" class="select2 form-control" required="required">
              <option value="">--Pilih Mata Pelajaran--</option>
              <?php 

                foreach ($sql_mapel as $d) {
                $nm_mapel = mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_mapel FROM m_mapel WHERE id_mapel='$d[id_mapel]'"));
                echo '<option value="'.$d['id_mapel'].'">'.$nm_mapel['nama_mapel'].'</option>';
              }

               ?>
            </select>
        </div>

        <div class="form-group">
          <label for="">Pertanyaan</label>
            <input type="hidden" class="form-control" name="pembuat" value="<?= $_SESSION['id_user'];?>">
            <textarea name="pertanyaan"  cols="75" rows="3" class="editor_soal"></textarea>
        </div>

        <div class="form-group">
          <label for="">Jawaban A</label>
          <textarea name="pil_a"  cols="75" rows="3" class="editor"></textarea>
          
        </div>

        <div class="form-group">
          <label for="">Jawaban B</label>
          <textarea name="pil_b"  cols="75" rows="3" class="editor"></textarea>
        </div>

        <div class="form-group">
          <label for="">Jawaban C</label>
          <textarea name="pil_c"  cols="75" rows="3" class="editor"></textarea>
        </div>

        <div class="form-group">
          <label for="">Jawaban D</label>
          <textarea name="pil_d"  cols="75" rows="3" class="editor"></textarea>
        </div>

        <div class="form-group">
          <label for="">Jawaban E</label>
          <textarea name="pil_e"  cols="75" rows="3" class="editor"></textarea>
        </div>

        <div class="form-group">
          <label for="">Kunci Jawaban</label><p>
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="pil_1" name="kunci" class="custom-control-input" value="1">
            <label class="custom-control-label" for="pil_1">Pilihan A</label>
          </div>

          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="pil_2" name="kunci" class="custom-control-input" value="2">
            <label class="custom-control-label" for="pil_2">Pilihan B</label>
          </div>

          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="pil_3" name="kunci" class="custom-control-input" value="3">
            <label class="custom-control-label" for="pil_3">Pilihan C</label>
          </div>

          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="pil_4" name="kunci" class="custom-control-input" value="4">
            <label class="custom-control-label" for="pil_4">Pilihan D</label>
          </div>

          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="pil_5" name="kunci" class="custom-control-input" value="5">
            <label class="custom-control-label" for="pil_5">Pilihan E</label>
          </div>
        </div>  

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
        <button type="submit" name="simpan_pilganda" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
</div><!--end modal-->

<!-- Modal Upload Excel Pil Ganda-->
<div class="modal fade" id="upload_excel"  role="dialog" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Tambahkan ke Bank Soal Pilihan Ganda</h4>
        </div>
      <form action="?module=ug_bank&act=save" method="POST" enctype="multipart/form-data">
      <div class="modal-body">
        <?php
        $sql_mapel2 = mysqli_query($koneksi,"SELECT DISTINCT id_mapel FROM f_mapel WHERE nip = '$_SESSION[id_user]'"); 
        ?>
        <div class="form-group">
          <label>Mata Pelajaran</label>
            <select name="id_mapel" class="select2 form-control" required="required">
              <option value="">--Pilih Mata Pelajaran--</option>
              <?php 

                foreach ($sql_mapel2 as $d2) {
                $nm_mapel2 = mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_mapel FROM m_mapel WHERE id_mapel='$d2[id_mapel]'"));
                echo '<option value="'.$d2['id_mapel'].'">'.$nm_mapel2['nama_mapel'].'</option>';
              }

               ?>
            </select>
            <input type="hidden" class="form-control" name="pembuat" value="<?= $_SESSION['id_user'];?>">
        </div>
        <div class="form-group">
          <label>Upload File Excel</label>
          <input type="file" name="file" class="form-control" required="required">     
        </div>
        Keterangan : 
        <small>
        <ul>
          <li>Harap Download File Template Soalnya </li>
          <li>Tulis Pertanyaan dan jawabannya pada template </li>
          <li>Untuk Kolom Kunci (Kunci A = 1, Kunci B = 2, Kunci C = 3, Kunci D = 4, Kunci E = 5, )</li>
          <li>Upload Soal Excel Tidak Bisa memasukan Soal Gambar dan Rumus</li>
        </ul>
        </small> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
        <button type="submit" name="upload" class="btn btn-primary">Proses</button>
      </div>
    </form>
  </div>
</div>
</div><!--end modal-->


<?php 
  }
} 
?>
