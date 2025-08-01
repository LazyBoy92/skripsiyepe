<?php 
error_reporting(0);
include 'config/koneksi.php';
include 'config/library.php';
include 'config/fungsi_indotgl.php';

include 'frontend/header.php';
include 'frontend/navbar.php';
	
	if($_GET['menu']=='home'){
		include 'home.php';
	}
	elseif ($_GET['menu']=='register') {
		include 'register.php';
	}
	elseif ($_GET['menu']=='data_register') {
		include 'data_register.php';
	}
	elseif ($_GET['menu']=='proses_register') {
		include 'proses_register.php';
	}
	
	else {
		include 'eror_page.php';
	}

include 'frontend/footer.php';
?>