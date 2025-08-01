<?php
session_start();
error_reporting(0);
if (empty($_SESSION['id_user'])){
			 header('Location:index.php?module=home');
}
else{
include"config/koneksi.php";

$user 	= mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM user WHERE id_user='$_SESSION[id_user]'"));
$id 	= mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM chat ORDER BY idchat DESC"));
$id2 	= $id['idchat'] + 1;

				
$destination 	= 'file_uploads/'; // where our files will be stored

// PHP sets a global variable $_FILES['file'] which containes all information on the file
// The $_FILES['file'] is also an array, so to have the file name we're supposed to write $_FILES['file']['name']
// To shorten that I added the following line. With that I could just do $file['name']
$file 		 		= $_FILES['file'];
$filename 			= explode(".", $file['name']); 
$file_name 			= $file['name']; // file original name
$file_name_no_ext 	= isset($filename[0]) ? $filename[0] : null; // File name without the extension
$file_extension 	= $filename[count($filename)-1];
$file_weight 		= $file['size'];
$file_type 			= $file['type'];



// If there is no error
if( $file['error'] == 0 )
{
                // rename the file
                $fileNewName = $id2 ."_". $file_name_no_ext .'.'.$file_extension ;

                // and move it to the destination folder
                if(move_uploaded_file($file['tmp_name'], $destination.$fileNewName) ) {

                 //   echo" File uploaded ! name= $fileNewName";

                } else {

                    echo "can't upload file.";

                }

}

//save
$pesan = $_POST['pesan'];
$isi_pesan=str_replace("\"", "'", $pesan);
if($file_extension == "jpg" || $file_extension == "png" || $file_extension == "jpeg"|| $file_extension == "bmp" || $file_extension == "gif"){
	$html="<img src=\'file_uploads/$fileNewName\' style=\'width:100%;\' />";
	$unduh="<p><a href=\'file_uploads/$fileNewName\' style=\'color:#5555ff;\'><u>unduh</u></a></p>";
}elseif($file_extension == "mp3" || $file_extension == "wav" || $file_extension == "oog"){
	$html="<audio controls=\'controls\'><source src=\'file_uploads/$fileNewName\' ></audio>";
	$unduh="<p><a href=\'file_uploads/$fileNewName\' style=\'color:#5555ff;\'><u>unduh</u></a></p>";
}elseif($file_extension == "mp4" || $file_extension == "MP4" || $file_extension == "3gp" || $file_extension == "flv" || $file_extension == "avi"){
	$html="<video style=\'width:100%;\' controls=\'controls\'><source src=\'file_uploads/$fileNewName\' ></video>";
	$unduh="<p><a href=\'file_uploads/$fileNewName\' style=\'color:#5555ff;\'><u>unduh</u></a></p>";
}else{
	$html="<a href=\'file_uploads/$fileNewName\' style=\'color:#5555ff;\'><u>$fileNewName</u></a>";
}
mysqli_set_charset('utf8',$koneksi);
	
	date_default_timezone_set('Asia/Jakarta');
	$tgl_skr=date('Y-m-d H:i:s');
	$nama = $_POST['nama']; 
	
	$simpan = mysqli_query($koneksi,"INSERT INTO chat(idchat,
                                 dari,
                                 ke,
                                 isi_pesan,
                                 tgl_pesan)
	                       VALUES('',
                                '$_SESSION[id_user]',
								'$nama',
                                '$isi_pesan<hr style=\'margin:5px;\' />$html $unduh',
                                '$tgl_skr')"); 
	echo "INSERT INTO chat(idchat,
                                 dari,
                                 ke,
                                 isi_pesan,
                                 tgl_pesan)
	                       VALUES('',
                                '$_SESSION[id_user]',
								'$nama',
                                '$isi_pesan<hr style=\'margin:5px;\' />$html $unduh',
                                '$tgl_skr')";
/*
	if($simpan){
	//	header('Location:drzchat.php?id=$nama');
		//echo "<meta http-equiv='refresh' content='0; url=media.php?module=view_chat&id=$nama'>";
	}else{
		//echo "<meta http-equiv='refresh' content='0; url=media.php?module=view_chat&id=$nama'>";
		//echo"gagal";	
	}
*/
}
?>