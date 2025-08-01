  <!-- Topbar Navbar -->
  <ul class="navbar-nav ml-auto">
    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
    
    <li class="nav-item ml-500">
      <a class="nav-link text-primary" href="#" aria-haspopup="true" aria-expanded="false">
        <marquee behavior="scroll" direction="left" scrollamount="2"><?= $sis_panjang;?></marquee>
      </a>
    </li>
    <!-- Nav Item - Messages -->
    <li class="nav-item dropdown no-arrow mx-1">
      <a href="#" class="nav-link dropdown-toggle dropdown-pesan" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-envelope "></i>
        <span class="badge badge-danger badge-counter count"></span>
      </a>
      <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown" id="notif_pesan">
      
      </div>
    </li>
    <div class="topbar-divider d-none d-sm-block"></div>


    <!-- Nav Item - User Information -->
    <li class="nav-item dropdown no-arrow">
      <?php 

      if($_SESSION['leveluser']=='user_guru'){

        $ft   =mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_lengkap, foto FROM guru WHERE id='$_SESSION[id_user]'"));
        $foto ='<img class="img-profile rounded-circle" src="module/foto_pengajar/'.$ft['foto'].'">';
        $nama = $ft['nama_lengkap'];
      }
      elseif ($_SESSION['leveluser']=='user_siswa') {
        $ft   =mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_lengkap,foto FROM siswa WHERE id='$_SESSION[id_user]'"));
        $foto ='<img class="img-profile rounded-circle" src="module/foto_siswa/'.$ft['foto'].'">';
        $nama = $ft['nama_lengkap'];
      }
      else {
        $foto ='<img class="img-profile rounded-circle" src="dist/img/administrator.png">';
        $nama = 'Administrator';
      }

       ?>
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?= $foto; ?> &nbsp; <span class="mr-2 d-none d-lg-inline text-primary small"><b> Selamat Datang : <?= $nama;?></b></span>
      </a>
      <!-- Dropdown - User Information -->
      <?php 
      if($_SESSION['leveluser']=='user_guru')
      {
      ?>
      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="?module=ug_profile">
          <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
          Profile
        </a>
        <a class="dropdown-item" href="?module=ug_setting">
          <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
          Settings
        </a>
        <a class="dropdown-item" href="?module=ug_aktivitas">
          <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
          Activity Log
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
          <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
          Logout
        </a>
      </div>
    <?php } 
    elseif($_SESSION['leveluser']=='user_siswa')
      { ?>
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="?module=ug_profile">
          <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
          Profile
        </a>
        <a class="dropdown-item" href="?module=ug_setting">
          <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
          Settings
        </a>
        <a class="dropdown-item" href="?module=sw_aktivitas">
          <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
          Activity Log
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
          <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
          Logout
        </a>
      </div>
    <?php } 
    elseif($_SESSION['leveluser']=='admin')
      { ?>
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="?module=setting">
          <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
          Settings
        </a>
        <a class="dropdown-item" href="?module=aktivitas">
          <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
          Activity Log
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
          <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
          Logout
        </a>
      </div>
    <?php } ?>
    </li>

  </ul>

<!-- End of Topbar -->