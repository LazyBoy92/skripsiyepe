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
        text-align: left;
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
    	margin:40px 40px;
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

$id_siswa = $_GET['siswa'];
$id_ujian = $_GET['topik'];

$sql_data = "SELECT a.*,b.nama_mapel, c.nama_lengkap FROM topik_ujian a, m_mapel b, guru c WHERE a.id_mapel=b.id_mapel AND a.pembuat=c.id AND a.id = '$id_ujian' ";

$sql_siswa= "SELECT a.nama_lengkap, b.nama_kelas, c.nis FROM siswa a,m_kelas b, f_kelas c WHERE a.nis=c.nis AND b.id_kelas=c.id_kelas AND a.id='$id_siswa' ";
$r 		  = mysqli_fetch_array(mysqli_query($koneksi,$sql_data));
$r2 	  = mysqli_fetch_array(mysqli_query($koneksi,$sql_siswa));

$nil_pg   = mysqli_fetch_array(mysqli_query($koneksi,"SELECT nilai FROM nilai WHERE id_ujian='$id_ujian' AND id_siswa ='$id_siswa'"));
$nil_es   = mysqli_fetch_array(mysqli_query($koneksi,"SELECT SUM(nilai) as jum FROM nilai_esay WHERE id_ujian='$id_ujian' AND id_siswa ='$id_siswa'"));

	if($nil_es['jum']==0){
		$nil_esay = '0';
	}
	else{
		$nil_esay = (int) $nil_es['jum'];
	}

$jum_nilai = $nil_pg['nilai'] + $nil_esay;
?>
	<!--KOP SURAT ARRAHMAN -->

	
			<!--<img src="../../dist/img/kop.jpg" width="100%" >-->
		

	<br><br>
	<u><h3 class="box-title">HASIL UJIAN ONLINE</h3></u>
	<table class="table table-bordered table-striped" width="100%">
		<tr><td>Judul Ujian</td><td>: <?=$r['judul'];?></td></tr>
		<tr><td>Mapel</td><td>: <?=$r['nama_mapel'];?></td></tr>
		<tr><td>Guru</td><td>: <?=$r['nama_lengkap'];?></td></tr>
		<tr><td>NISN</td><td>: <?=$r2['nis'];?></td></tr>
		<tr><td>Nama Siswa</td><td>: <?=$r2['nama_lengkap'];?></td></tr>
		<tr><td>Kelas</td><td>: <?=$r2['nama_kelas'];?></td></tr>
	</table>
	</center>
	
	<table class="table table-bordered table-striped" width="100%">
		<thead>
			<tr><th>Jenis Ujian</th><th>Nilai</th></tr>
		</thead>
		<tbody>
			<tr><td>Ujian Pilihan Ganda</td><td><?= $nil_pg['nilai'];?></td></tr>
			<tr><td>Ujian Essay</td><td><?= $nil_esay;?></td></tr>
			<tr><td>Jumlah Nilai</td><td><?= $jum_nilai;?></td></tr>
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