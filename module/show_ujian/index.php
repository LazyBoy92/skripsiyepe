<?php
//Deteksi hanya bisa diinclude, tidak bisa langsung dibuka (direct open)
if(count(get_included_files())==1){
	echo "<meta http-equiv='refresh' content='0; url=http://$_SERVER[HTTP_HOST]'>";
	exit("Direct access not permitted.");
	}
error_reporting(0);
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
header('location:../error_login.php');
	}
else{
	switch($_GET['act']){
		default:
      	if ($_SESSION['leveluser']=='user_siswa'){

include "style_menu.php";
//1 Update status siswa dan membuat array data untuk dimasukkan ke tabel nilai
//mysqli_query($koneksi, "UPDATE siswa SET status_ujian='mengerjakan' WHERE id='$_SESSION[id_user]'");
$id_ujian = $_GET['id'];
$rujian = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM topik_ujian WHERE id='$id_ujian'"));

$pg = mysqli_fetch_array(mysqli_query($koneksi,"SELECT COUNT(id_soalpg) as jum FROM soal_pilganda WHERE id_tujian='$id_ujian'"));
$qsoal = mysqli_query($koneksi, "SELECT * FROM soal_pilganda WHERE id_tujian='$id_ujian' ORDER BY rand() LIMIT $pg[jum]");
$q2soal = mysqli_query($koneksi, "SELECT * FROM soal_pilganda WHERE id_tujian='$id_ujian' ORDER BY id_soalpg");

if(mysqli_num_rows($qsoal)==0) die('<div class="alert alert-warning">Belum ada soal pada ujian ini</div>');
	
$arr_soal = array();
$arr_jawaban = array();
while($rsoal = mysqli_fetch_array($qsoal)){
   $arr_soal[] = $rsoal['id_soalpg'];
   $arr_jawaban[] = 0;
}
$soalid = array();
while($r2soal = mysqli_fetch_array($q2soal)){
	   $soalid[] = $r2soal['id_soalpg'];
}

$acak_soal = implode(",", $arr_soal);
$jawaban = implode(",", $arr_jawaban);

//2 Memasukkan data ke tabel nilai jika data nilai belum ada
$qnilai = mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_siswa='$_SESSION[id_user]' AND id_ujian='$id_ujian'");
if(mysqli_num_rows($qnilai) < 1){

	$jam 	= date("H:i:s");
	$jm1 	= substr($jam,0,2);
	$mn1 	= substr($jam,3,2);
	$dt1 	= substr($jam,6,2);
	//$dt_waktu = floor($rujian['waktu_pengerjaan']/3600);
	//$waktu 	= date("$dt_waktu");
	$w_a 	= $rujian['waktu_pengerjaan'];
	
	if($w_a < 3600 ) { 
		$j_w = '00';
		$m_w = floor($w_a/60);
		$d_w = floor($sisa/1);
		$wak= $j_w.':'.$m_w.':'.$d_w;
	} 
	else {
		$j_w = floor($w_a/3600);
		$m_w = floor($j_w/60);
		$d_w = floor($sisa/1);
		$wak= $j_w.':'.$m_w.':'.$d_w;
	}
	//echo $wak;
	$waktu 	= date("$wak");
	$jm2 	= substr($waktu,0,2);
	$mn2 	= substr($waktu,3,2);
	$dt2 	= substr($waktu,6,2);
	$jam12 	= $jm2+$jm1;
	$menit 	= $mn2 + $mn1 ;
	$detik 	= $dt1;
	
	if($menit>60){	
		$hr = $jam12 + 1;
		$mn = $menit -60;
		}
	else {	
		$hr = $jam12;
		$mn = $menit;		
	}

$waktuselesai = date ("$hr:$mn:$detik");

mysqli_query($koneksi, "INSERT INTO nilai SET id_siswa='$_SESSION[id_user]', id_ujian='$id_ujian', acak_soal='$acak_soal', jawaban='$jawaban', sisa_waktu='$waktu',waktu_selesai='$waktuselesai', status='mengerjakan' ");
 
$kls = $soalid;
  foreach($kls as $kelas) {
   mysqli_query($koneksi, "INSERT INTO analisis SET id_siswa='$_SESSION[id_user]', id_ujian='$id_ujian', id_soal='$kelas', jawaban='0'");
	}   
} 
else {

$nil = mysqli_fetch_array($qnilai);

$jam = date("H:i:s");
$jm1 = substr($jam,0,2);
$mn1 = substr($jam,3,2);
$dt1 = substr($jam,6,2);


$selesai = date("$nil[waktu_selesai]");
$jm2 = substr($selesai,0,2);
$mn2 = substr($selesai,3,2);
$dt2 = substr($selesai,6,2);

$mulai = mktime($jm1,$mn1,$dt1); 
$selesai = mktime($jm2,$mn2,$dt2);  

$lama = $selesai - $mulai;

$hr = (int) ($lama / 3600);
$mn = (int) (($lama - ($hr * 3600) ) / 60);
$sc =  $lama - ($hr * 3600) - ($mn * 60) ; 

if($mn < 0){
	mysqli_query($koneksi, "UPDATE nilai SET sisa_waktu = '00:00:01' WHERE id_siswa='$_SESSION[id_user]' AND id_ujian='$id_ujian'"); 
	} 
else {
	mysqli_query($mysqli, "UPDATE nilai SET sisa_waktu = '$hr:$mn:$sc' WHERE id_siswa='$_SESSION[id_user]' AND id_ujian='$id_ujian'"); 
	}	
}

//3 Menampilkan judul mapel dan sisa waktu
$qnilai = mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_siswa='$_SESSION[id_user]' AND id_ujian='$id_ujian'");
$rnilai = mysqli_fetch_array($qnilai);
$sisa_waktu = explode(":", $rnilai['sisa_waktu']);

?>
<div class="row">
	<div class="col-lg-12">
		<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
		  		<h6 class="m-0 font-weight-bold text-primary">UJIAN : <?=$rujian['judul'];?> </h6>
		  		 <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    </a><b>Sisa Waktu : </b>
                    <button id="h_timer" class="btn-sm btn-danger"></button>
                    <input type="hidden" id="ujian" value="<?= $id_ujian;?>">
					<input type="hidden" id="jam" value="<?= $sisa_waktu[0];?>">
					<input type="hidden" id="menit" value="<?= $sisa_waktu[1];?>">
					<input type="hidden" id="detik" value="<?= $sisa_waktu[2];?>">
			  	</div>
			</div>
	</div>
</div>
<div class="row">
	
<?php 
//4 Mengambil data soal dari database
$arr_soal = explode(",", $rnilai['acak_soal']);
$arr_jawaban = explode(",", $rnilai['jawaban']);
$arr_class = array();

for($s=0; $s<count($arr_soal); $s++) {
   $rsoal = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM soal_pilganda WHERE id_soalpg='$arr_soal[$s]'"));
//5 Menampilkan no. soal dan soal	
$no = $s+1;
$soal = str_replace("../media", "../media", $rsoal['pertanyaan']);
$active = ($no==1) ? "active" : "";

//echo $active; 
echo '
	<div class="col-lg-12">
		<div class="blok-soal soal-'.$no.' '.$active.'">
		<div id="fontlembarsoal" class="fontlembarsoal">
			<span id="hurufsoal" class="bg-warning shadow"> Soal Nomor :  <a id="jfontsize-d2" style="font-size: 16px; text-decoration: none; cursor: pointer;">&nbsp; '.$no.' &nbsp;</a> </span>
	 	</div>
	 		<div id="lembaran">
	 			<div class="cc-selector">
	 				<div class="card shadow">
						<div class="card-body">   
  							<p class="soal">'.$soal.'</p><br> 
							<table cellspacing="0px" cellpadding="0px" border="0">
'; 
//6 Membuat array pilihan dan mengacak pilihan
$arr_pilihan = array();
$arr_pilihan[] = array("no" => 1, "pilihan" => $rsoal['pil_a']);
$arr_pilihan[] = array("no" => 2, "pilihan" => $rsoal['pil_b']);
$arr_pilihan[] = array("no" => 3, "pilihan" => $rsoal['pil_c']);
$arr_pilihan[] = array("no" => 4, "pilihan" => $rsoal['pil_d']);
$arr_pilihan[] = array("no" => 5, "pilihan" => $rsoal['pil_e']);

//7 Menampilkan pilihan	
$arr_huruf = array("A","B","C","D","E");	
$arr_class[$no] = ($arr_jawaban[$s]!=0) ? "ijo" : "";

for($i=0; $i<=4; $i++){
  $checked = ($arr_jawaban[$s] == $arr_pilihan[$i]['no']) ? "checked" : "";
  $pilihan = str_replace("../media", "../media", $arr_pilihan[$i]['pilihan']);
  $pilihan = str_replace("p>", "b>", $arr_pilihan[$i]['pilihan']);

echo '
									<tr>
										<td valign="top">
											<input type="radio" name="jawab-'.$no.'" id="huruf-'.$no.'-'.$i.'" '.$checked.'> <label for="huruf-'.$no.'-'.$i.'" class="huruf" onclick="kirim_jawaban('.$s.', '.$arr_pilihan[$i]['no'].')">  '.$arr_huruf[$i].'  </label>
										</td>   
							        <td class="pilihanjawaban" valign="top">&nbsp; '.$pilihan.' </td></tr>';
	}
echo'
        						</table>
        					</div>
        					<div class="card-footer">
        						<div class="kakisoal" id="kakisoal" style="width: 97.7%;">';
//8 Menampilkan tombol sebelumnya, ragu-ragu dan berikutnya
										$sebelumnya = $no-1;
									echo '	<tr>';
											if($no != 1) 
									echo '	  	<td>
													<a onclick="tampil_soal('.$sebelumnya.')"><button class="btn-sm btn-default" >SOAL SEBELUMNYA</button></a>
											  	</td>
												<td>
													<label class="btn-sm btn-warning"> <input type="checkbox" autocomplete="off" onchange="ragu_ragu('.$no.')">&nbsp;RAGU-RAGU</label>
												</td>';
														
										$berikutnya = $no+1;
											if($no != count($arr_soal)) 
									echo ' 	
												<td>
													<a onclick="tampil_soal('.$berikutnya.')"><button class="btn-sm btn-primary btn-next activebutton"> SOAL BERIKUTNYA</button> </a>
												</td>';
											else 
									echo ' 		<td>
													<a  onclick="selesai()"><button class="btn-sm btn-danger btn-next activebutton">SELESAI</button></a>
												</td>';
									echo '	</tr>';
			echo '						
								</div>';
echo'    					</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
 	}

 ?> 

</div>

<div class="modal fade" id="modal-selesai" role="dialog">
    <form method="POST" action="?module=sis_ujian&act=selesai_ujian" enctype="multipart/form-data" role="form">
    	<div class="modal-dialog">
           <!-- Modal content-->
        	<div class="modal-content">
            	<div class="modal-header">
                    <h4 class="modal-title">Konfirmasi Tes</h4>
                </div>
                <div class="modal-body">
                	<input type="hidden" name="ujian" value="<?=$id_ujian;?>">
	                <p>
	                    Terimakasih telah berpartisipasi dalam tes ini.<br>
	                    Silahkan klik tombol SELESAI untuk mengakhiri test.
	                </p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"  name="simpan">SELESAI</button>
                    <button type="submit" class="btn btn-danger" data-dismiss="modal">TIDAK</button>
                </div>
            </div>
        </div>
   </form>
 </div>

<?php

		}
break;
	}
}
?>