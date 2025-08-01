<section class="ftco-section bg-light  ftco-slant ftco-slant-white">
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center mb-5 ftco-animate">
        <h2 class="text-uppercase ftco-uppercase">Registrasi User</h2>
        <div class="row justify-content-center">
          <div class="col-md-7">
            <p class="lead">Proses Registrasi User</p>
          </div>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="media d-block mb-0 ftco-media p-md-5 p-4 ftco-animate">
          <div class="ftco-icon mb-3"></div>
          <div class="media-body">
            <?php
              // error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
              include 'config/koneksi.php';
              include 'config/fungsi.php';
              function anti_injection($data){
                global $koneksi;
                
                $filter = mysqli_real_escape_string($koneksi, stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
                
                return $filter;
              }

                $id_user    = $_POST['id_user'];
                $username   = anti_injection($_POST['username']);
                $password   = md5($_POST['password']);
                $nama       = anti_injection($_POST['nama_lengkap']);
                $no_telp    = anti_injection($_POST['no_telp']);
                $email      = anti_injection($_POST['email']);
                $level      = $_POST['level'];
                $blokir     = 'A';
                $id         = $_POST['jenis'];

                $sql_save   = "INSERT INTO user(id_user, username, password, nama_lengkap, level, no_telp, email, blokir) VALUES('$id_user','$username','$password','$nama','$level','$no_telp','$email','$blokir')";

                $cek_1 = mysqli_num_rows(mysqli_query($koneksi,"SELECT id_user FROM user WHERE id_user = '$id_user'"));
                $cek_2 = mysqli_num_rows(mysqli_query($koneksi,"SELECT username FROM user WHERE username = '$username'"));
                // $cek_3 = mysqli_num_rows(mysqli_query($koneksi,"SELECT email FROM user WHERE email='$email'"));

                //VALIDASI 
                if($cek_1 !=0) {
                  save_alert('error','User Sudah Terdaftar..!');
                  htmlRedirect('register.html',2);
                }

                elseif ($cek_2 !=0) {
                  save_alert('error','Username Sudah terdaftar...!');
                  htmlRedirect('beranda.html');
                }

                // elseif ($cek_3 !=0) {
                //   save_alert('error','Email Sudah terdaftar...!');
                //   htmlRedirect('beranda.html');
                // }

                else {

                  //echo $sql_save;
                  mysqli_query($koneksi,$sql_save);
                   
                 ?>
                   <table class="table table-striped">
                     <tr><td colspan="2" align="center"><h3 class="text-primary"><i class="fas fa-check-circle"></i> Pendaftaran Anda Berhasil</h3></td></tr>
                     <tr><td>Nomor Register </td> <td> : <?= $id_user;?></td></tr>
                     <tr><td>Username </td> <td> : <?= $username;?></td></tr>
                     <tr><td>Password </td> <td> : <?= $_POST['password'];?></td></tr>
                     <tr><td>Level User </td> <td> : <?= $level;?></td></tr>
                     <tr><td colspan="2">
                            <ol class="text-info">
                              <li>Segera Melakukan Permohonan Aktifasi</li>
                              <li>Aktifasi User Siswa Hubungi : <b>Wali Kelas Masing-Masing</b></li>
                              <li>Aktifasi User Guru Hubungi : <b>Admin</b></li>
                            </ol>
                          </td></tr>
                    <tr><td colspan="2" align="right"><button onclick="print_data()" class="btn btn-sm btn-primary rounded"><i class="fas fa-print"> </i></button></td></tr>
                   </table>
                   

                  <script>
                      function print_data() {
                        window.print();
                      }
                  </script>
                  <script type="text/javascript">
                      $(function() {
                          setTimeout(function() {
                              $.bootstrapGrowl("<h5><i class='icon fas fa-check'></i>     Anda Berhasil Mendaftar</h5>", {
                                  ele: "body",
                                  type: "success",
                                  offset: {
                                    from: "top",
                                    amount: 100
                                  },
                                  align: "center",
                                  width: 800,
                                  delay: 2000,
                                  allow_dismiss: true,
                                  stackup_spacing: 5
                              });
                          });
                      });
                  </script>
                 <?php  //echo '<input type="text" name="info" id="swalinfo" value="swalinfo">';
                }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
    <!-- END section -->