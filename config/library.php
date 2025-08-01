<?php
include 'koneksi.php';

/* DATA TAHUN */
date_default_timezone_set('Asia/Jakarta'); // PHP 6 mengharuskan penyebutan timezone.
$seminggu = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
$hari = date("w");
$hari_ini = $seminggu[$hari];

$tgl_sekarang = date("Ymd");
$tgl_skrg     = date("d");
$bln_sekarang = date("m");
$thn_sekarang = date("Y");
$jam_sekarang = date("H:i:s");
$date_time 	  = date("Y-m-d H:i:s");

$nama_bln=array(1=> "Januari", "Februari", "Maret", "April", "Mei", 
                    "Juni", "Juli", "Agustus", "September", 
                    "Oktober", "November", "Desember");


/* DATA SISTEM */
$r=mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM sis_identitas WHERE id='1'"));
$tahun_p  	 = $r['tahun_p'];
$sis_panjang = $r['nama_panjang'];
$sis_singkat = $r['nama_singkat'];
$logo_nav 	 = $r['logo_nav'];

// $tahun_skrg = date('Y');
// $tahun_lalu = date('Y',strtotime('-1 year', strtotime($tahun_skrg)));

$tahun_skrg = substr($tahun_p, 5,9);
$tahun_lalu = substr($tahun_p, 0,4);
$thn_lalu = $tahun_lalu.'-07-01';
$thn_skrg = $tahun_skrg.'-06-30';
?>
