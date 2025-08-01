<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../index.php');
}

else { 

	if(isset($_POST['simpan'])) {
		$id_kelas 	= $_POST['id_kelas'];
		$nama_kelas = $_POST['nama_kelas'];
		$sql_save 	= "INSERT INTO m_kelas(id_kelas,nama_kelas) VALUES('$id_kelas','$nama_kelas')";

		if(mysqli_query($koneksi,$sql_save)) {

			save_alert('save','Data Tersimpan');
            htmlRedirect('media.php?module='.$module,1);
		}

		else {

			save_alert('error','Gagal disimpan');
            htmlRedirect('media.php?module='.$module,1);
		}
	}

	elseif(isset($_POST['update'])) {

		$id_kelas = $_POST['id_kelas'];
		$nama_kelas = $_POST['nama_kelas'];
		$sql_edit = "UPDATE m_kelas SET nama_kelas = '$nama_kelas' WHERE id_kelas = '$id_kelas'";

		if (mysqli_query($koneksi,$sql_edit)) {

			save_alert('save','Edit Data Berhasil');
            htmlRedirect('media.php?module='.$module,1);
		}

		else {

			save_alert('error','Edit Data Gagal');
            htmlRedirect('media.php?module='.$module,1);
		}
	
	}

	elseif ($_GET['aksi']=='hapus') {
		$id_kelas = $_GET['id'];
		$sql_hapus = "DELETE FROM m_kelas WHERE id_kelas = '$id_kelas'";

		if (mysqli_query($koneksi,$sql_hapus)) {

			save_alert('save','Hapus Data Berhasil');
            htmlRedirect('media.php?module='.$module,1);
			
		}

		else {

			save_alert('error','Hapus Data Gagal');
            htmlRedirect('media.php?module='.$module,1);
		}
	}


}