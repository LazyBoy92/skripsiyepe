<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../index.php');
}

else { 

	if(isset($_POST['simpan'])) {

		$sql_save 	= "INSERT INTO f_mapel(id_mapel,id_kelas,nip,deskripsi,tp) VALUES('$_POST[id_mapel]', '$_POST[id_kelas]', '$_POST[nip]', '', '$tahun_p')";

		$cek = mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM f_mapel WHERE id_mapel='$_POST[id_mapel]' AND id_kelas='$_POST[id_kelas]' AND nip='$_POST[nip]' AND tp='$tahun_p'"));

		if($cek == 0) {

			mysqli_query($koneksi,$sql_save);
			save_alert('save','Data Tersimpan');
            htmlRedirect('media.php?module='.$module,1);
		}

		else {

			save_alert('error','Gagal Tersimpan Dobel Data');
            htmlRedirect('media.php?module='.$module,1);
		}
	}

	elseif(isset($_POST['update'])) {

		$id_mapel = $_POST['id_mapel'];
		$id_kelas = $_POST['id_kelas'];
		$nip 	  = $_POST['nip'];
		$id_data  = $_POST['id_data'];
		
		$sql_edit = "UPDATE f_mapel SET id_mapel = '$id_mapel', id_kelas='$id_kelas', nip = '$nip' WHERE id = '$id_data'";

		//echo $sql_edit;
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
		$id_data = $_GET['id'];
		$sql_hapus = "DELETE FROM f_mapel WHERE id = '$id_data'";

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