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

if ($_SESSION['leveluser']=='admin'){
?>
      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="?module=home">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">Admin Panel</div>

      <li class="nav-item">
        <a class="nav-link" href="?module=user_aktivasi">
          <i class="fas fa-fw fa-user-plus"></i>
          <span>User Aktivasi</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="?module=user">
          <i class="fas fa-fw fa-users-cog"></i>
          <span>User Pengguna</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="?module=sistem">
          <i class="fas fa-fw fa-wrench"></i>
          <span>Setting System</span></a>
      </li>


      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Master
      </div>

      <li class="nav-item">
        <a class="nav-link" href="?module=m_siswa">
          <i class="fas fa-fw fa-book-reader"></i>
          <span>Data Siswa</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="?module=m_guru">
          <i class="fas fa-fw fa-user-graduate"></i>
          <span>Data Pendidik</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="?module=m_mapel">
          <i class="fas fa-fw fa-book"></i>
          <span>Master Mapel</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="?module=m_kelas">
          <i class="fas fa-fw fa-book"></i>
          <span>Master Kelas</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#utama" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-folder"></i>
          <span>Menu Utama</span>
        </a>
        <div id="utama" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="?module=f_kelas"><i class="fas fa-fw fa-school"></i>&nbsp;Kelas</a>
            <a class="collapse-item" href="?module=f_mapel"><i class="fas fa-fw fa-chalkboard-teacher"></i>&nbsp;Guru Mata Pelajaran</a>
            <a class="collapse-item" href="?module=f_materi"><i class="fas fa-fw fa-book-open"></i>&nbsp;Materi / Modul</a>
            <a class="collapse-item" href="?module=f_ujian"><i class="fas fa-fw fa-bell"></i>&nbsp;Ujian</a>
            <a class="collapse-item" href="?module=f_video"><i class="fas fa-fw fa-video"></i><span>&nbsp;Video Interaktif</span></a>
          </div>
        </div>
      </li>

      <!--
      <li class="nav-item">
        <a class="nav-link" href="?module=f_video"><i class="fas fa-fw fa-video"></i><span>Video Interaktif</span></a>
      </li>

      <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#laporan" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-folder"></i>
          <span>Menu Laporan</span>
        </a>
        <div id="laporan" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="?module=f_laporan_siswa">Rekap Siswa</a>
            <a class="collapse-item" href="?module=f_laporan_ujian">Rekap Nilai</a>
          </div>
        </div>
      </li>
      -->

  <?php }
  elseif ($_SESSION['leveluser']=='user_guru'){
  ?>
      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="?module=ug_home">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">Setting Panel</div>

      <li class="nav-item">
        <a class="nav-link" href="?module=ug_setting">
          <i class="fas fa-fw fa-user-cog"></i>
          <span>User Pengguna</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Menu Utama
      </div>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#bank_soal" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-tasks"></i>
          <span>Manajemen Bank Soal</span>
        </a>
        <div id="bank_soal" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="?module=ug_bank&act=pil_ganda"><i class="fas fa-chess-queen"></i> Pilihan Ganda</a>
            <a class="collapse-item" href="?module=ug_bank&act=esay"><i class="fas fa-chess-knight"></i> Essay</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="?module=ug_ujian">
          <i class="fas fa-fw fa-bell"></i>
          <span>Manajemen Ujian</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="?module=ug_materi">
          <i class="fas fa-fw fa-book-open"></i>
          <span>Manajemen Materi</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="?module=ug_video">
          <i class="fas fa-fw fa-video"></i>
          <span>Manajemen Video</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="?module=ug_forum">
          <i class="fas fa-fw fa-users"></i>
          <span>Manajemen Forum</span></a>
      </li>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#utama" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-mail-bulk"></i>
          <span>Kotak Pesan</span>
        </a>
        <div id="utama" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="?module=ug_pesan&act=pesan_masuk"><i class="fas fa-inbox"></i> Pesan Masuk</a>
            <a class="collapse-item" href="?module=ug_pesan&act=pesan_keluar"><i class="fas fa-share"></i> Pesan Dikirim</a>
            <a class="collapse-item" href="?module=ug_pesan&act=kirim_pesan"><i class="fas fa-paper-plane"></i> Kirim Pesan</a>
          </div>
        </div>
      </li>

      <!--
      <li class="nav-item">
        <a class="nav-link" href="?module=ug_chat">
          <i class="fas fa-fw fa-comments"></i>
          <span>Manajemen Chat</span></a>
      </li>
    -->


  <?php } 
  elseif ($_SESSION['leveluser']=='user_siswa'){
  ?>
      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="?module=ug_profile">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">Setting Panel</div>

      <li class="nav-item">
        <a class="nav-link" href="?module=ug_setting">
          <i class="fas fa-fw fa-user-cog"></i>
          <span>User Pengguna</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Menu Utama
      </div>

      <li class="nav-item">
        <a class="nav-link" href="?module=sis_ujian">
          <i class="fas fa-fw fa-bell"></i>
          <span>Ujian</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="?module=sis_materi">
          <i class="fas fa-fw fa-book-open"></i>
          <span>Materi</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="?module=sis_video">
          <i class="fas fa-fw fa-video"></i>
          <span>Video</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="?module=sis_forum">
          <i class="fas fa-fw fa-users"></i>
          <span>Forum Diskusi</span></a>
      </li>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#utama" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-mail-bulk"></i>
          <span>Kotak Pesan</span>
        </a>
        <div id="utama" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="?module=ug_pesan&act=pesan_masuk"><i class="fas fa-inbox"></i> Pesan Masuk</a>
            <a class="collapse-item" href="?module=ug_pesan&act=pesan_keluar"><i class="fas fa-share"></i> Pesan Dikirim</a>
            <a class="collapse-item" href="?module=ug_pesan&act=kirim_pesan"><i class="fas fa-paper-plane"></i> Kirim Pesan</a>
          </div>
        </div>
      </li>

      

  <?php } ?>
<?php 
}
?>