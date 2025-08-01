<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../index.php');
}

else { 

	$id = 1;

	if(isset($_POST['btn_namap'])) {

		mysqli_query($koneksi,"UPDATE sis_identitas SET nama_panjang = '$_POST[nama_panjang]' WHERE id='$id'");
		save_alert('save','Edit Data Berhasil');
        htmlRedirect('media.php?module='.$module,1);
		
	}

	elseif(isset($_POST['btn_tahunp'])) {

		mysqli_query($koneksi,"UPDATE sis_identitas SET tahun_p = '$_POST[tahun_p]' WHERE id='$id'");
		save_alert('save','Edit Data Berhasil');
        htmlRedirect('media.php?module='.$module,1);
		
	}

	elseif(isset($_POST['btn_namas'])) {

		mysqli_query($koneksi,"UPDATE sis_identitas SET nama_singkat = '$_POST[nama_singkat]' WHERE id='$id'");
		save_alert('save','Edit Data Berhasil');
        htmlRedirect('media.php?module='.$module,1);
		
	}

	elseif(isset($_POST['btn_logo'])) {

		$lokasi_file = $_FILES['flogo']['tmp_name'];
		$nama_file   = $_FILES['flogo']['name'];
		$tipe_file   = $_FILES['flogo']['type'];
		  

		$extensionList = array("jpg", "jpeg", "png");
		$pecah = explode(".", $nama_file);
		$ekstensi = $pecah[1];

		if(!empty($nama_file)) {
		    if (!in_array($ekstensi, $extensionList)){
		                 
		        save_alert('error','Type File tidak di izinkan');
		        htmlRedirect('media.php?module='.$module);
		    }
		    
		    else{ 
		        
		        $r = mysqli_fetch_array(mysqli_query($koneksi,"SELECT logo_nav FROM sis_identitas WHERE id='$id'"));
		        $exist_file = 'dist/img/'.$r['logo_nav'];
		        //unlink($exist_file);

		        Uploadlogo($nama_file);
		          mysqli_query($koneksi,"UPDATE sis_identitas SET logo_nav = '$nama_file' WHERE id='$id'");
		        save_alert('save','Edit Data Berhasil');
		        htmlRedirect('media.php?module='.$module);
		    }
		}

	}

}