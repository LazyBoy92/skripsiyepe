<?php 

function id_guru()
{
	include 'koneksi.php';
	$kode 	= '1';
	$thn 	= date('y');
	$ind 	= $kode.$thn;
	$sql	= mysqli_fetch_array(mysqli_query($koneksi,"SELECT max(id) as max FROM guru WHERE id LIKE '%$ind%'"));
	$count 	= (int) substr($sql['max'],3,4);
	

	$id_max = $kode.$thn.sprintf("%'.04d", $count);

	return $id_max;
}

function id_siswa()
{
	include 'koneksi.php';
	$kode 	= '2';
	$thn 	= date('y');
	$ind 	= $kode.$thn;
	$sql	= mysqli_fetch_array(mysqli_query($koneksi,"SELECT max(id) as max FROM siswa WHERE id LIKE '%$ind%'"));
	$count 	= (int) substr($sql['max'],3,4);
	

	$id_max = $kode.$thn.sprintf("%'.04d", $count);

	return $id_max;
}
 ?>