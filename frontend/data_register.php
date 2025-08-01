<section class="ftco-section bg-light  ftco-slant ftco-slant-white">
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center mb-5 ftco-animate">
        <h2 class="text-uppercase ftco-uppercase">Registrasi User</h2>
        <div class="row justify-content-center">
          <div class="col-md-7">
            <p class="lead">Registrasi User Untuk Guru dan Siswa</p>
          </div>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="media d-block mb-0 text-center ftco-media p-md-5 p-4 ftco-animate">
          <div class="ftco-icon mb-3"></div>
          <div class="media-body">
            <?php
              // error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
              include 'config/koneksi.php';
              include 'config/fungsi.php';
               
              $jenis = $_POST['jenis']   OR $jenis   = $_GET['id'];
              $id    = $_POST['id_data'] OR $id    = $_GET['id_data'];

              // echo $jenis.' '.$id;
              if($jenis =='1') {

                $sql_data = mysqli_query($koneksi,"SELECT * FROM guru WHERE nip='$id'");
                $cek = mysqli_num_rows($sql_data);

                if($cek == 0){
                  save_alert('error','Data Guru Tidak Ditemukan..!!');
                  htmlRedirect('register.html',2);
                }
                else {
                  $r = mysqli_fetch_array($sql_data);
                  ?>
                  <form method="POST" name="register_guru" action="proses-register.html" enctype="multipart/form-data">
                    <table class="table table-striped" width="100%" cellspacing="2" cellpadding="2">
                      <tr><td>Username </td><td><input type="text" name="username" class="form-control" value="<?=$r['nip'];?>" required="required" readonly="readonly"></td></tr>
                      <tr><td>Password </td><td><input type="password" name="password" class="form-control" value="" required="required"></td></tr>
                      <tr><td>Level User </td><td><input type="text" name="level" class="form-control" value="user_guru" required="required" readonly="readonly"></td></tr>
                      <tr><td>Nama Lengkap </td><td><input type="text" name="nama_lengkap" class="form-control" value="<?= $r['nama_lengkap'];?>" required="required"></td></tr>
                      <tr><td>NIP/NUPT/NO KTP </td><td><input type="text" name="nip" class="form-control" value="<?= $r['nip'];?>" required="required"></td></tr>
                      <tr><td>Jabatan </td><td><input type="text" name="jabatan" class="form-control" value="<?= $r['jabatan'];?>" required="required"></td></tr>
                      <tr><td>No Telp </td><td><input type="text" name="no_telp" class="form-control" value="<?= $r['no_telp'];?>" required="required"></td></tr>
                      <tr><td>E-Mail </td><td><input type="email" name="email" class="form-control" value="<?= $r['email'];?>" required="required"></td></tr>
                      <tr><td colspan="2">
                          <input type="hidden" name="id_user" value="<?=$r['id'];?>">
                          <input type="hidden" name="jenis" value="<?=$jenis;?>">
                          <input type="submit" name="submit" value="Proses" class="btn btn-primary"></td></tr>
                    </table>
                  </form>
                  <?php
                }
              }
              else {
                $sql_data = mysqli_query($koneksi,"SELECT * FROM siswa WHERE nis='$id'");
                $cek = mysqli_num_rows($sql_data);

                if($cek == 0){
                  save_alert('error','Data Siswa Tidak Ditemukan..!!');
                  htmlRedirect('register.html',2);
                }
                else {
                  $r = mysqli_fetch_array($sql_data);

                  ?>
                  <form method="POST" name="register_guru" action="proses-register.html" enctype="multipart/form-data">
                    <table class="table table-striped" width="100%" cellspacing="2" cellpadding="2">
                      <tr><td>Username </td><td><input type="text" name="username" class="form-control" value="<?= $r['nis'];?>" readonly="readonly"></td></tr>
                      <tr><td>Password </td><td><input type="password" name="password" class="form-control" value="" required="required"></td></tr>
                      <tr><td>Level User </td><td><input type="text" name="level" class="form-control" value="user_siswa" required="required" readonly="readonly"></td></tr>
                      <tr><td>Nama Lengkap </td><td><input type="text" name="nama_lengkap" class="form-control" value="<?= $r['nama_lengkap'];?>" required="required"></td></tr>
                      <tr><td>NIS </td><td><input type="text" name="nip" class="form-control" value="<?= $r['nis'];?>" readonly="readonly"></td></tr>
                      <tr><td>No Telp </td><td><input type="text" name="no_telp" class="form-control" value="<?= $r['no_telp'];?>" required="required"></td></tr>
                      <tr><td>E-Mail </td><td><input type="email" name="email" class="form-control" value="<?= $r['email'];?>" required="required"></td></tr>
                      <tr><td colspan="2">
                          <input type="hidden" name="id_user" value="<?=$r['id'];?>">
                          <input type="hidden" name="jenis" value="<?=$jenis;?>">
                          <input type="submit" name="submit" value="Proses" class="btn btn-primary"></td></tr>
                    </table>
                  </form>
              <?php   
                }
              }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
    <!-- END section -->