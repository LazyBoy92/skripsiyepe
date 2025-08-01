<head>
<link href="style.css" rel="stylesheet" type="text/css" />
<style>
    @page { size: A4 }
 
    h1 {
        font-weight: bold;
        font-size: 20pt;
        text-align: center;
    }
 
    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 12pt;
    }
 
    .table th {
        padding: 8px 8px;
        border:1px solid #000000;
        text-align: center;
		background-color: #f5f5f5;
    }
 
    .table td {
        padding: 3px 3px;
        border:1px solid #000000;
		text-align: left;
    }

    .ttd2{
    	text-align:justify; 
		margin-left:300px;
	 }

	 .ttd1{
    	text-align:justify; 
		margin-left:80px;
	 }
	.data{
    	text-align:justify; 
    	margin:20px 20px;
	}
 
</style>
</head>
<body class="sheet" font-size="12pt">
<center>
<?php
//Deteksi hanya bisa diinclude, tidak bisa langsung dibuka (direct open)
include "../../config/koneksi.php";
include "../../config/fungsi_indotgl.php";
include "../../config/library.php";
include "../../config/fungsi.php";

$id_kelas = $_POST['id_kelas'];
$id_ujian = $_POST['id_ujian'];

$sql_head=mysqli_query($koneksi,"SELECT a.*,b.nama_mapel,c.nama_lengkap FROM topik_ujian a, m_mapel b, guru c WHERE a.id_mapel=b.id_mapel AND a.pembuat=c.id AND a.id='$id_ujian'");
$r =mysqli_fetch_array($sql_head);
$r2=mysqli_fetch_array(mysqli_query($koneksi,"SELECT nama_kelas FROM m_kelas WHERE id_kelas ='$id_kelas'"));
$sql_data=mysqli_query($koneksi,"SELECT a.nama_lengkap,a.id,b.id_kelas FROM siswa a, f_kelas b WHERE a.nis=b.nis AND b.id_kelas='$id_kelas'");
//echo "SELECT a.nama_lengkap,b.id_kelas FROM siswa a, f_kelas b WHERE a.nis=b.nis AND b.id_kelas='$id_kelas'";

//CARI ID SOAL PG DAN ESSAY
/*
$pg=mysqli_num_rows(mysqli_query($koneksi,"SELECT id_nilai FROM nilai WHERE id_ujian='$id_ujian'"));
$es=mysqli_num_rows(mysqli_query($koneksi,"SELECT id_nesay FROM nilai_esay WHERE id_ujian='$id_ujian'"));

if($pg>0 AND $es>0) {
    $sql_data=mysqli_query($koneksi,"SELECT id_ujian,id_siswa,nilai FROM nilai WHERE id_ujian='$id_ujian' ");
}
elseif($pg>0 AND $es==0) {
    $sql_data=mysqli_query($koneksi,"SELECT id_ujian,id_siswa,nilai FROM nilai WHERE id_ujian='$id_ujian' ");
}
elseif($pg==0 AND $es>0) {
    $sql_data=mysqli_query($koneksi,"SELECT id_ujian,id_siswa FROM nilai_esay WHERE id_ujian='$id_ujian' ");
}*/
?>
	<!--KOP SURAT ARRAHMAN -->

	
			<!--<img src="../../dist/img/kop.jpg" width="100%" >-->
		

	<u><h3 class="box-title">HASIL UJIAN ONLINE</h3></u>
	<table class="" width="100%">
		<tr><td>Judul Ujian</td><td>: <?=$r['judul'];?></td></tr>
		<tr><td>Mapel</td><td>: <?=$r['nama_mapel'];?></td></tr>
		<tr><td>Guru</td><td>: <?=$r['nama_lengkap'];?></td></tr>
		<tr><td>Kelas</td><td>: <?=$r2['nama_kelas'];?></td></tr>
	</table>
	</center>
	<br><br>
	<table class="table table-bordered table-striped" width="80%" style="font-size: 11px;">
		<thead>
			<tr><th>NO</th><th>NAMA LENGKAP</th><th>NILAI PIL GANDA</th><th>NILAI ESAY</th><th>NILAI</th></tr>
		</thead>
		<tbody>
			<?php 
			$no = 1;
			while($dt=mysqli_fetch_array($sql_data)){
				$row1 = mysqli_fetch_array(mysqli_query($koneksi,"SELECT sum(nilai) as jum FROM nilai_esay WHERE id_ujian='$id_ujian' AND id_siswa='$dt[id]'"));
				$row2 = mysqli_fetch_array(mysqli_query($koneksi,"SELECT nilai FROM nilai WHERE id_ujian='$id_ujian' AND id_siswa='$dt[id]'"));
				@$n_pilg = $r['bobot_pg']/100 * $row2['nilai'];
				$n_esay = $r['bobot_esay']/100 * $row1['jum'];
				$jumlah = $n_pilg + $n_esay;

			 ?>

			 <tr>
			 	<td style="text-align: center;"><?=$no;?></td>
			 	<td><?=$dt['nama_lengkap'];?></td>
			 	<td style="text-align: center;" width="15%"><?= $n_pilg;?></td>
			 	<td style="text-align: center;" width="15%"><?= $n_esay;?></td>
			 	<td style="text-align: center;" width="30%"><?=$jumlah;?></td>

			<?php $no++; } ?>
			
		</tbody>
	</table>
	<br>
	<table>
		<tr>
			<td><br>Mengetahui, <br>Guru Mapel<br><br><br><br><br>  <?=$r['nama_lengkap'];?> </td>
		</tr>
	</table>
	</div>
</body>

<script>
	 window.print();
</script>