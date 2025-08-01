<?php


//session_start();
//error_reporting(0);
//include"timeout.php";

//include"config/koneksi.php";
//echo "aa";
$user=mysqli_fetch_array(mysqli_query($koneksi,"SELECT * from user where id_user='$_SESSION[id_user]'"));
$read=mysqli_query($koneksi,"UPDATE chat SET status='R' WHERE dari='$_GET[nama]' and ke='$_SESSION[id_user]'");

//echo "UPDATE chat SET status='R' WHERE dari='$_GET[nama]' and ke='$_SESSION[id_user]'";

if (empty($_SESSION['id_user'])){
			 header('Location:index.php?module=home');
}
else{

	//include"config/koneksi.php";
	mysqli_set_charset('utf8',$koneksi);
	
	date_default_timezone_set('Asia/Jakarta');
	$tgl_skr=date('Y-m-d H:i:s');
	$nama = $_GET['nama']; 
	$pesan = $_GET['pesan']; 
	$waktu = date("H:i"); 
	$akhir = $_GET['akhir']; 
	
	$json = '{"messages": {'; 
	
	    if($pesan){ 
		$isi_pesan=str_replace("\"", "'", $pesan);
				
	        $masuk = mysqli_query($koneksi,"INSERT INTO chat(idchat,
                                 dari,
                                 ke,
                                 isi_pesan,
                                 tgl_pesan)
	                       VALUES('',
                                '$user[id_user]',
								'$nama',
                                '$isi_pesan',
                                '$tgl_skr')"); 
	
	    } 
	    $query = mysqli_query($koneksi,"SELECT c.* from chat c where ((c.ke='$user[id_user]' and c.dari='$nama') or (c.ke='$nama' and c.dari='$user[id_user]')) and c.idchat > $akhir order by idchat"); 

	    //echo "SELECT c.* from chat c where ((c.ke='$user[id_user]' and c.dari='$nama') or (c.ke='$nama' and c.dari='$user[id_user]')) and c.idchat > $akhir order by idchat";
		
		$query2 = mysqli_query($koneksi,"SELECT c.* from chat c where ((c.ke='$user[id_user]' and c.dari='$nama') or (c.ke='$nama' and c.dari='$user[id_user]')) and c.idchat > $akhir order by idchat"); 
		$r = mysqli_fetch_array($query2);
		
		$sekarang=substr("$r[tgl_pesan]",0,10);
		
	    $json .= '"pesan":[ '; 
	    while($x = mysqli_fetch_array($query)){
				$jam=substr("$x[tgl_pesan]",11,5);
				
				$get_t=substr("$x[tgl_pesan]",0,10);
				if($get_t!=$sekarang){
				if($get_t!=$get_t2){
					$get_t2=$get_t;
					
					$th=substr("$x[tgl_pesan]",0,4);
					$bln=substr("$x[tgl_pesan]",5,2);
					$d=substr("$x[tgl_pesan]",8,2);
					
					$tampil=$d.'-'.$bln.'-'.$th;
					
				}else{
					$get_t2=$get_t;
					
					$tampil="";
				}
				}else{
					$tampil="";
				}
				
	        $json .= '{'; 
	        $json .= '"id":"'.$x['idchat'].'", 
	                  "nama":"'.$x['dari'].'",
	                  "ke":"'.$x['ke'].'", 
	                  "teks":"'.$x['isi_pesan'].'",
					  "status":"'.$x[status].'",
	                  "jam":"'.$jam.'",
					  "skr":"'.$sekarang.'",
	                  "level":"'.$x['level'].'",
	                  "waktu":"'.$tampil.'" 
	                  },'; 
	    } 
	    $json = substr($json,0,strlen($json)-1); 
	    $json .= ']'; 
	 
	
	$json .= '}}'; 
	echo $json; 
	
}
?> 