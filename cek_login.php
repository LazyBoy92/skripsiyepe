<?php
  error_reporting(0);
  include "config/koneksi.php";
  include "config/library.php";
  include "config/fungsi_indotgl.php";
  include "config/fungsi_get_ip.php";

  function anti_injection($data){
  global $koneksi;
  $filter = mysqli_real_escape_string($koneksi, stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
  return $filter;
}


  $username = anti_injection($_POST['username']);
  $pass     = anti_injection(md5($_POST['password']));
  //$pass     = hash("sha512",$data);
  if (!ctype_alnum($username) OR !ctype_alnum($pass)){
       include "error_login.php";
    //echo $username;
      }
  else{
        $result = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username' AND password='$pass' AND blokir='N'");
        $ketemu=mysqli_num_rows($result);
        $r= mysqli_fetch_array($result);
        // Apabila username dan password ditemukan
        if ($ketemu > 0){
          include "timeout.php";
          session_start();
          $_SESSION['namauser']     = $r['username'];
          $_SESSION['namalengkap']  = $r['nama_lengkap'];
          $_SESSION['passuser']     = $r['pass'];
          $_SESSION['mailuser']     = $r['email'];
          $_SESSION['leveluser']    = $r['level'];
          $_SESSION['id_user']      = $r['id_user'];
          // session timeout
          $_SESSION['login'] = 1;
          timer();

          $sid_lama = session_id();
          session_regenerate_id();
          $sid_baru = session_id();
          
          $ip = IP_Client();
          mysqli_query($koneksi,"INSERT INTO log_user(id_user,ip_user,browser,os,tgl,jam) VALUE('$_SESSION[id_user]','$ip','$browser','$os','$tgl_sekarang','$jam_sekarang')");
          
          mysqli_query($koneksi,"INSERT INTO session_login (id_session,nama_lengkap,level,date,time,ip) VALUE ('$sid_lama','$_SESSION[namalengkap]','$_SESSION[leveluser]','$tgl_sekarang','$jam_sekarang','$ip')");
          mysqli_query($koneksi,"UPDATE user SET id_session='$sid_baru', login='1', batas_log='0' WHERE username='$username'");
          
          if($_SESSION['leveluser']=='admin')
          {
          header('location:media.php?module=home');
          }
          elseif($_SESSION['leveluser']=='user_guru')
          {
          header('location:media.php?module=ug_home');
          }
          elseif($_SESSION['leveluser']=='user_siswa')
          {
          header('location:media.php?module=ug_profile');
          }
          //header('location:media.php');
          //echo"Login Berhasil";
          //mysql_query("UPDATE users SET id_session='$sid_baru', hits=hits+1 WHERE username='$username'"";
        }
        else
        {
          $rb = mysqli_fetch_array(mysqli_query($koneksi,"SELECT batas_log FROM user WHERE username = '$username'"));

          //echo "SELECT batas_log FROM user WHERE username = '$username'";
          $batas_log = $rb['batas_log']+1;
          //echo $batas_log;
          if($batas_log <= 3) {
          
            mysqli_query($koneksi,"UPDATE user SET batas_log = '$batas_log' WHERE username = '$username'");
            include "error_login.php";
          }
          else {
            
            mysqli_query($koneksi,"UPDATE user SET blokir = 'Y' WHERE username = '$username'");
            include "error_login.php";

          }
        }
      }
?>