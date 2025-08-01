<?php
//error_reporting(0);
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../index.php');
}
 else {	
	
	if (isset($_POST['simpan_essay'])) {
		$lokasi_file = $_FILES['fupload']['tmp_name'];
	  	$nama_file   = $_FILES['fupload']['name'];
	  	$tipe_file 	 = $_FILES['fupload']['type'];

	  	// Apabila ada gambar yang diupload
	  	if (!empty($lokasi_file)) {
	        if (file_exists($direktori_file)) {
	            save_alert('error','Nama File Gambar Sudah Ada');
				htmlRedirect('media.php?module='.$module.'&act=essay&id='.$_POST[id_tujian],1);
	        }else{
	            if ($tipe_file != "image/jpeg" AND $tipe_file != "image/jpg" )
	            {
		            save_alert('error','Type File Tidak di Izinkan');
					htmlRedirect('media.php?module='.$module.'&act=essay&id='.$_POST[id_tujian],1);
	            }
	            else
	            {
	                //echo $nama_file;
	                UploadImage_soal($nama_file);
	                mysqli_query($koneksi,"INSERT INTO soal_esay(id_tujian,pertanyaan,gambar,tgl_buat,jenis_soal) VALUES('$_POST[id_tujian]','$_POST[pertanyaan]','$nama_file','$tgl_sekarang','essay')");
	                save_alert('save','Soal Tersimpan');
					htmlRedirect('media.php?module='.$module.'&act=essay&id='.$_POST[id_tujian],1);
	            }
	        }     
	    }
	    else {
	        mysqli_query($koneksi,"INSERT INTO soal_esay(id_tujian,pertanyaan,tgl_buat,jenis_soal)
	                   VALUES('$_POST[id_tujian]','$_POST[pertanyaan]','$tgl_sekarang','essay')");
	        save_alert('save','Soal Tersimpan');
			htmlRedirect('media.php?module='.$module.'&act=essay&id='.$_POST[id_tujian],1);
	    	}
	}

	elseif (isset($_POST['update_essay'])) {
		$lokasi_file = $_FILES['fupload']['tmp_name'];
	  	$nama_file   = $_FILES['fupload']['name'];
	  	$tipe_file 	 = $_FILES['fupload']['type'];
		
		// Apabila ada gambar yang diupload
	  	if (!empty($lokasi_file)){
	        if (file_exists($direktori_file)){
	            save_alert('error','Nama File Gambar Sudah Ada');
				htmlRedirect('media.php?module='.$module.'&act=essay&id='.$_POST[id_tujian],1);
	        }
	        else{
	            if ($tipe_file != "image/jpeg" AND $tipe_file != "image/jpg" )
	            {
		            save_alert('error','Type File Tidak di Izinkan');
					htmlRedirect('media.php?module='.$module.'&act=essay&id='.$_POST[id_tujian],1);
	            }
	            else {
	                $cek = mysqli_query($koneksi,"SELECT * FROM soal_esay WHERE id_soal = '$_POST[id_soal]'");
	                //echo "SELECT * FROM soal_esay WHERE id_soal = '$_POST[id_soal]'";
	                $rd = mysqli_fetch_array($cek);

	                if(!empty($rd[gambar]))
	                {
		                $img = "module/foto_soal/$rd[gambar]";
		                unlink($img);
		                $img2 = "module/foto_soal/medium_$rd[gambar]";
		                unlink($img2);
		                UploadImage_soal($nama_file);
		                mysqli_query($koneksi,"UPDATE soal_esay SET pertanyaan = '$_POST[pertanyaan]',
	                                                  gambar     = '$nama_file'
	                                            WHERE id_soal 	 = '$_POST[id_soal]'");
		                save_alert('save','Soal Tersimpan');
						htmlRedirect('media.php?module='.$module.'&act=essay&id='.$_POST[id_tujian],1);
	            		}
	            	else {
	            		UploadImage_soal($nama_file);
		                mysqli_query($koneksi,"UPDATE soal_esay SET pertanyaan = '$_POST[pertanyaan]', gambar     = '$nama_file' WHERE id_soal 	 = '$_POST[id_soal]'");
		                save_alert('save','Soal Tersimpan');
						htmlRedirect('media.php?module='.$module.'&act=essay&id='.$_POST[id_tujian],1);
	            	}
	        	}
	        }     
	    }
    	else {
        mysqli_query($koneksi,"UPDATE soal_esay SET pertanyaan = '$_POST[pertanyaan]' WHERE id_soal  = '$_POST[id_soal]'");
        //echo "UPDATE soal_esay SET pertanyaan = '$_POST[soal]', tgl_buat   = '$tgl_sekarang' WHERE id_soal  = '$_POST[id_soal]'";
        save_alert('save','Soal Berhasil di rubah');
		htmlRedirect('media.php?module='.$module.'&act=essay&id='.$_POST[id_tujian],1);
    	}
	}

	elseif (isset($_POST['simpan_pilganda'])) {
		$lokasi_file = $_FILES['fupload']['tmp_name'];
	  	$nama_file   = $_FILES['fupload']['name'];
	  	$tipe_file 	 = $_FILES['fupload']['type'];
	  	$direktori_file = 'module/foto_soal_pilganda/$nama_file';

	  	// Apabila ada gambar yang diupload
	  	if (!empty($lokasi_file)) {
	        if (file_exists($direktori_file)) {
	            save_alert('error','Nama File Gambar Sudah Ada');
				htmlRedirect('media.php?module='.$module.'&act=pil_ganda&id='.$_POST[id_tujian],1);
	        }else{
	            if ($tipe_file != "image/jpeg" AND $tipe_file != "image/jpg" )
	            {
		            save_alert('error','Type File Tidak di Izinkan');
					htmlRedirect('media.php?module='.$module.'&act=pil_ganda&id='.$_POST[id_tujian],1);
	            }
	            else
	            {
	                //echo $nama_file;
	                UploadImage_soal_pilganda($nama_file);
	                mysqli_query($koneksi,"INSERT INTO soal_pilganda(id_tujian,pertanyaan,gambar,pil_a,pil_b,pil_c,pil_d,pil_e,kunci,tgl_buat,jenis_soal) VALUES('$_POST[id_tujian]','$_POST[pertanyaan]','$nama_file','$_POST[pil_a]','$_POST[pil_b]','$_POST[pil_c]','$_POST[pil_d]','$_POST[pil_e]','$_POST[kunci]','$tgl_sekarang','pil_ganda')");
	                save_alert('save','Soal Tersimpan');
					htmlRedirect('media.php?module='.$module.'&act=pil_ganda&id='.$_POST[id_tujian],1);
	            }
	        }     
	    }
	    else {
	        mysqli_query($koneksi,"INSERT INTO soal_pilganda(id_tujian,pertanyaan,gambar,pil_a,pil_b,pil_c,pil_d,pil_e,kunci,tgl_buat,jenis_soal) VALUES('$_POST[id_tujian]','$_POST[pertanyaan]','','$_POST[pil_a]','$_POST[pil_b]','$_POST[pil_c]','$_POST[pil_d]','$_POST[pil_e]','$_POST[kunci]','$tgl_sekarang','pil_ganda')");
	        save_alert('save','Soal Tersimpan');
			htmlRedirect('media.php?module='.$module.'&act=pil_ganda&id='.$_POST[id_tujian],1);
	    	}
	}

	elseif (isset($_POST['update_pilganda'])) {
		$lokasi_file = $_FILES['fupload']['tmp_name'];
	  	$nama_file   = $_FILES['fupload']['name'];
	  	$tipe_file 	 = $_FILES['fupload']['type'];
		
		// Apabila ada gambar yang diupload
	  	if (!empty($lokasi_file)){
	        if (file_exists($direktori_file)){
	            save_alert('error','Nama File Gambar Sudah Ada');
				htmlRedirect('media.php?module='.$module.'&act=pil_ganda&id='.$_POST[id_tujian],1);
	        }
	        else{
	            if ($tipe_file != "image/jpeg" AND $tipe_file != "image/jpg" )
	            {
		            save_alert('error','Type File Tidak di Izinkan');
					htmlRedirect('media.php?module='.$module.'&act=pil_ganda&id='.$_POST[id_tujian],1);
	            }
	            else {
	                $cek = mysqli_query($koneksi,"SELECT * FROM soal_pilganda WHERE id_soalpg = '$_POST[id_soalpg]'");
	                //echo "SELECT * FROM soal_esay WHERE id_soal = '$_POST[id_soal]'";
	                $rd = mysqli_fetch_array($cek);

	                if(!empty($rd[gambar]))
	                {
		                $img = "module/foto_soal_pilganda/$rd[gambar]";
		                unlink($img);
		                $img2 = "module/foto_soal_pilganda/medium_$rd[gambar]";
		                unlink($img2);
		                UploadImage_soal_pilganda($nama_file);
		                mysqli_query($koneksi,"UPDATE soal_pilganda SET pertanyaan = '$_POST[soal]', pil_a = '$_POST[pil_a]', pil_b = '$_POST[pil_b]', pil_c = '$_POST[pil_c]', pil_d = '$_POST[pil_d]', pil_e = '$_POST[pil_e]', kunci = '$_POST[kunci]', gambar = '$nama_file',tgl_buat = '$tgl_sekarang' WHERE id_soalpg = '$_POST[id_soalpg]'");
		                save_alert('save','Soal Tersimpan');
						htmlRedirect('media.php?module='.$module.'&act=pil_ganda&id='.$_POST[id_tujian],1);
	            		}
	            	else {
	            		UploadImage_soal_pilganda($nama_file);
		                mysqli_query($koneksi,"UPDATE soal_pilganda SET pertanyaan = '$_POST[soal]', pil_a = '$_POST[pil_a]', pil_b = '$_POST[pil_b]', pil_c = '$_POST[pil_c]', pil_d = '$_POST[pil_d]', pil_e = '$_POST[pil_e]', kunci = '$_POST[kunci]', gambar = '$nama_file',tgl_buat = '$tgl_sekarang' WHERE id_soalpg = '$_POST[id_soalpg]'");
		                save_alert('save','Soal Tersimpan');
						htmlRedirect('media.php?module='.$module.'&act=pil_ganda&id='.$_POST[id_tujian],1);
	            	}
	        	}
	        }     
	    }
    	else {
       mysqli_query($koneksi,"UPDATE soal_pilganda SET pertanyaan = '$_POST[soal]', pil_a = '$_POST[pil_a]', pil_b = '$_POST[pil_b]', pil_c = '$_POST[pil_c]', pil_d = '$_POST[pil_d]', pil_e = '$_POST[pil_e]', kunci = '$_POST[kunci]',tgl_buat = '$tgl_sekarang' WHERE id_soalpg 	 = '$_POST[id_soalpg]'");
        //echo "UPDATE soal_esay SET pertanyaan = '$_POST[soal]', tgl_buat   = '$tgl_sekarang' WHERE id_soal  = '$_POST[id_soal]'";
        save_alert('save','Soal Berhasil di rubah');
		htmlRedirect('media.php?module='.$module.'&act=pil_ganda&id='.$_POST[id_tujian],1);
    	}
	}

	elseif(isset($_POST['simpan_tu'])) {

		$id=mysqli_fetch_array(mysqli_query($koneksi,"SELECT MAX(id) as jum FROM topik_ujian"));
		$idmax = $id['jum']+1;
		$waktu = $_POST['waktu_pengerjaan']*60;
        $data = "INSERT INTO topik_ujian(id,judul,id_mapel,tgl_buat,pembuat,waktu_pengerjaan,info,bobot_pg,bobot_esay,terbit) VALUES('$idmax','$_POST[judul]','$_POST[id_mapel]','$tgl_sekarang','$_SESSION[id_user]','$waktu','$_POST[info]','$_POST[bobot_pg]', '$_POST[bobot_esay]','$_POST[terbit]')";
        if(mysqli_query($koneksi,$data))
        {
        	$n 		= count($_POST['id_kelas']);
			$kelas	= $_POST['id_kelas'];
			
			for($x=0;$x<$n;$x++)
			{
				$a2="INSERT INTO kelas_ujian VALUES('$idmax','$kelas[$x]')";
				mysqli_query($koneksi,$a2);
				//echo $a2;
			}
			save_alert('save','Ujian Berhasil di Tambahkan');
			htmlRedirect('media.php?module='.$module.'&act=ug_ujian');
        }
        else {
        	save_alert('error','Gagal Tersimpan');
			htmlRedirect('media.php?module='.$module.'&act=ug_ujian');
        }
	}	

	elseif(isset($_POST['update_tu'])) {
		$waktu = $_POST['waktu_pengerjaan']*60;
        $data  = "UPDATE topik_ujian SET judul = '$_POST[judul]', id_mapel = '$_POST[id_mapel]', waktu_pengerjaan = '$waktu', info = '$_POST[info]', bobot_pg = '$_POST[bobot_pg]',bobot_esay = '$_POST[bobot_esay]', terbit = '$_POST[terbit]' WHERE id='$_POST[id_tujian]'";
        //echo $data;
        if(mysqli_query($koneksi,$data))
        {
        	mysqli_query($koneksi,"DELETE FROM kelas_ujian WHERE id='$_POST[id_tujian]'");
        	$n 		= count($_POST['id_kelas']);
			$kelas	= $_POST['id_kelas'];
			
			for($x=0;$x<$n;$x++)
			{
				
				$a2="INSERT INTO kelas_ujian VALUES('$_POST[id_tujian]','$kelas[$x]')";
				mysqli_query($koneksi,$a2);
				//echo $a2;
			}
			save_alert('save','Ujian Berhasil di Update');
			htmlRedirect('media.php?module='.$module.'&act=ug_ujian');
        }
        else {
        	save_alert('error','Gagal Tersimpan');
			htmlRedirect('media.php?module='.$module.'&act=ug_ujian');
        }
	}


	elseif(isset($_POST['proses_koreksi'])) {
		//$waktu = $_POST['waktu_pengerjaan']*60;
        $data  = "UPDATE nilai_esay SET nilai = '$_POST[nilai]' WHERE id_nesay='$_POST[id_nesay]'";
        //echo $data;
        if(mysqli_query($koneksi,$data))
        {
			save_alert('save','Soal dikoreksi');
			htmlRedirect('media.php?module='.$module.'&act=koreksi&ujian='.$_POST[ujian].'&siswa='.$_POST[siswa]);
        }
        else {
        	save_alert('error','Gagal dikoreksi');
			htmlRedirect('media.php?module='.$module.'&act=koreksi&ujian='.$_POST[ujian].'&siswa='.$_POST[siswa]);
        }
	}


	elseif(isset($_POST['essay_bank'])) {
		
		$id_soal 	= $_POST['id'];
		$id_tujian 	= $_POST['id_tujian'];
		$n = count($id_soal);

		for ($i=0; $i < $n; $i++) { 
			$cd = mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM bank_esay WHERE id='$id_soal[$i]'"));

			mysqli_query($koneksi,"INSERT INTO soal_esay(id_tujian, pertanyaan, tgl_buat, jenis_soal) VALUES('$id_tujian','$cd[pertanyaan]','$tgl_sekarang', 'essay')");
	               
			//echo $cd['pertanyaan'];
		}

		save_alert('save','Soal Tersimpan');
		htmlRedirect('media.php?module='.$module.'&act=essay&id='.$_POST[id_tujian],1);
	}

	elseif(isset($_POST['pg_bank'])) {
		
		$id_soal 	= $_POST['id'];
		$id_tujian 	= $_POST['id_tujian'];
		$n = count($id_soal);

		for ($i=0; $i < $n; $i++) { 
			$cd = mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM bank_pilganda WHERE id='$id_soal[$i]'"));

			mysqli_query($koneksi,"INSERT INTO soal_pilganda(id_tujian, pertanyaan, pil_a, pil_b, pil_c, pil_d, pil_e, kunci, tgl_buat, jenis_soal) VALUES('$id_tujian','$cd[pertanyaan]','$cd[pil_a]','$cd[pil_b]','$cd[pil_c]','$cd[pil_d]','$cd[pil_e]','$cd[kunci]','$tgl_sekarang','pil_ganda')");
	               
			//echo $cd['pertanyaan'];
		}

		save_alert('save','Soal Tersimpan');
		htmlRedirect('media.php?module='.$module.'&act=pil_ganda&id='.$_POST[id_tujian],1);
	}		



	


}
?>
