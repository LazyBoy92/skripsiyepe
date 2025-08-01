<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../index.php');
}

else { 

	if(isset($_POST['simpan'])) {

		$nama_mapel = $_POST['nama_mapel'];
		$sql_save 	= "INSERT INTO m_mapel(nama_mapel) VALUES('$nama_mapel')";

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

		$id_mapel = $_POST['id_mapel'];
		$nama_mapel = $_POST['nama_mapel'];
		$sql_edit = "UPDATE m_mapel SET nama_mapel = '$nama_mapel' WHERE id_mapel = '$id_mapel'";

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
		$id_mapel = $_GET['id'];
		$sql_hapus = "DELETE FROM m_mapel WHERE id_mapel = '$id_mapel'";

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