<?php
 function anti_injection($data){
  global $koneksi;
  $filter = mysqli_real_escape_string($koneksi, stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
  return $filter;
}

// class paging untuk halaman administrator
class Paging{
	// Fungsi untuk mencek halaman dan posisi data
	function cariPosisi($batas){
		if(empty($_GET['hal'])){
			$posisi=0;
			$_GET['hal']=1;
		}
		else{
			$posisi = ($_GET['hal']-1) * $batas;
		}
	return $posisi;
	}

	// Fungsi untuk menghitung total halaman
	function jumlahHalaman($jmldata, $batas){
	$jmlhalaman = ceil($jmldata/$batas);
	return $jmlhalaman;
	}

	// Fungsi untuk link halaman 1,2,3 (untuk admin)
	function navHalaman($halaman_aktif, $jmlhalaman){
	$link_halaman = "
		<nav aria-label='Page navigation example'>
			<ul class='pagination'>";

	// Link ke halaman pertama (first) dan sebelumnya (prev)
	if($halaman_aktif > 1){
		$prev = $halaman_aktif-1;
		$link_halaman .= "	
				<li class='page-item'><a class='page-link' href='$_SERVER[PHP_SELF]?module=$_GET[module]&hal=1'> << </a></li> 
				<li class='page-item'><a class='page-link' href='$_SERVER[PHP_SELF]?module=$_GET[module]&hal=$prev'> < </a> </li> ";
	}
	else{ 
		$link_halaman .= "

				<li class='page-item'><a class='page-link' href='#'><< </a></li>
				<li class='page-item'><a class='page-link' href='#'> < </a></li> ";
	}

	// Link halaman 1,2,3, ...
	$angka = ($halaman_aktif > 3 ? " 
				<li class='page-item'><a class='page-link' href='#'>... </a></li>" : " "); 
	for ($i=$halaman_aktif-2; $i<$halaman_aktif; $i++){
	  if ($i < 1)
	  	continue;
		  $angka .= "
		  		<li class='page-item'><a class='page-link' href='$_SERVER[PHP_SELF]?module=$_GET[module]&hal=$i'>$i</a></li>";
	  }
		  $angka .= " 
		  		<li class='page-item'><a class='page-link' href'#'><b>$halaman_aktif</b></a> </li>";
		  
	    for($i=$halaman_aktif+1; $i<($halaman_aktif+3); $i++){
	    if($i > $jmlhalaman)
	      break;
		  $angka .= "
		  		<li class='page-item'><a class='page-link' href='$_SERVER[PHP_SELF]?module=$_GET[module]&hal=$i'>$i</a></li>";
	    }
		  $angka .= ($halaman_aktif+2<$jmlhalaman ? " 
		  		<li class='page-item'>... </li> 
		  		<li class='page-item'> <a class='page-link' href='$_SERVER[PHP_SELF]?module=$_GET[module]&hal=$jmlhalaman'>$jmlhalaman</a> </li> " : " ");

	$link_halaman .= "$angka";

	// Link ke halaman berikutnya (Next) dan terakhir (Last) 
	if($halaman_aktif < $jmlhalaman){
		$next = $halaman_aktif+1;
		$link_halaman .= " 
				<li class='page-item'><a class='page-link' href='$_SERVER[PHP_SELF]?module=$_GET[module]&hal=$next'> ></a> </li> 
				<li class='page-item'><a class='page-link' href='$_SERVER[PHP_SELF]?module=$_GET[module]&hal=$jmlhalaman'> >> </a> </li> ";
	}
	else{
		$link_halaman .= " 
				<li class='page-item'><a class='page-link' href='#'> > </a></li>
				<li class='page-item'><a class='page-link' href='#'> >> </a></li>
				
			</ul>
		</nav>";
	}
	return $link_halaman;
	}
}

?>