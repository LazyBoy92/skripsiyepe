<!-- Content Row -->
  <div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Siswa Laki-laki</div>
              <?php 

                $sis_l = mysqli_query($koneksi,"SELECT COUNT(a.nis) as jum_l FROM siswa a, f_kelas b WHERE a.nis = b.nis AND a.jenis_kelamin='L' AND b.tp = '$tahun_p'");
                $juml = mysqli_fetch_array($sis_l);
               ?>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$juml['jum_l'];?></div>
            </div>
            <div class="col-auto">
              <ion-icon size="large" name="man-sharp"></ion-icon>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Siswa Perempuan</div>
              <?php 

                $sis_p = mysqli_query($koneksi,"SELECT COUNT(a.nis) as jum_p FROM siswa a, f_kelas b WHERE a.nis = b.nis AND a.jenis_kelamin='P' AND b.tp = '$tahun_p'");
                $jump = mysqli_fetch_array($sis_p);
               ?>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$jump['jum_p'];?></div>
            </div>
            <div class="col-auto">
              <ion-icon size="large" name="woman-sharp"></ion-icon>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah Ujian <?= $tahun_p;?></div>
              <?php 
                
                
                $ujian = mysqli_query($koneksi,"SELECT COUNT(id) as jum_u FROM topik_ujian WHERE tgl_buat BETWEEN '$thn_lalu' AND '$thn_skrg'");
                $ju = mysqli_fetch_array($ujian);
                
               ?>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?=$ju['jum_u'];?></div>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-tasks fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Soal Tersedia</div>
              <?php 
                
                $bank_pg = mysqli_query($koneksi,"SELECT COUNT(id) as jum_pg FROM bank_pilganda");
                $ju_pg = mysqli_fetch_array($bank_pg);

                $bank_es = mysqli_query($koneksi,"SELECT COUNT(id) as jum_es FROM bank_esay");
                $ju_es = mysqli_fetch_array($bank_es);
                
                $jumall = (int) $ju_pg['jum_pg'] + $ju_es['jum_es'];
                
               ?>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$jumall;?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!-- Content Row -->
  <div class="row">

    <div class="col-xl-8 col-lg-7">

      <!-- Area Chart -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Statistik Login User</h6>
        </div>
        <div class="card-body">
          <div class="chart-area">
            <canvas id="log_user"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4 col-lg-3">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">User Rank By Log</h6>
        </div>
        <div class="card-body">
          <?php 
          $no = 1;
          $sql_user = mysqli_query($koneksi,"SELECT a.nama_lengkap,a.level, COUNT(b.id_user) as count FROM user a, log_user b WHERE a.id_user=b.id_user GROUP BY b.id_user ORDER BY count DESC LIMIT 10");
           ?>
          
          <table class="table table-striped">
            <thead>
              <tr><th>No</th><th>Nama Lengkap</th><th>Level</th><th>Jum Login</th></tr>
            </thead>
            <tbody style="font-size: 13px;">
              <?php 
              foreach ($sql_user as $u ) :
              ?>
              <tr><td><?=$no;?></td><td><?=$u['nama_lengkap'];?></td><td><?= $u['level'];?></td><td><?= $u['count'];?></td></tr>
              <?php 
              $no++;
              endforeach;
               ?>
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>