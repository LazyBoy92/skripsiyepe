<?php 
//Deteksi hanya bisa diinclude, tidak bisa langsung dibuka (direct open)
if(count(get_included_files())==1)
{
	echo "<meta http-equiv='refresh' content='0; url=http://$_SERVER[HTTP_HOST]'>";
	exit("Direct access not permitted.");
}
//status informasi pada saat eksekusi database
function alert($type,$text = null){
	if($type=='info'){
		echo "<font color='white'><div class='infofly go-front' id='status'>$text</div></font>";
	}
	else if($type=='error')	{
		echo "<font color='white'><div class='errorfly go-front' id='status'>$text</div></font>";
	}
	else if($type=='download')	{
		echo "<font color='black'><center>$text</center></font>";
	}
	else if($type=='loading')		
		echo "<div id='loading'><center>$text</center></div>";
	
}
function login_alert($type,$text = null){
	if($type=='info'){
		echo "<font color='black'><div class='infofly info_login' id='status'>$text</div></font>";
	}
	else if($type=='error')	{
		echo "<font color='black'><div class='errorfly error_login' id='status'>$text</div></font>";
	}
	else if($type=='loading')		
		echo "<div id='loading'><center>$text</center></div>";
}
function save_alert($type,$text = null){
	if($type=='save'){
	echo"<div class='card-body'><div class='alert alert-success alert-dismissable'><h5><i class='icon fas fa-check'></i>$text</h5></div></div>";
	}
	else if($type=='error')	{
	echo"<div class='card-body'><div class='alert alert-danger alert-dismissable'><h5><i class='icon fas fa-ban'></i>$text</h5></div></div>";
	}
	else if($type=='delete')	{
	echo"<div class='card-body'><div class='alert alert-danger alert-dismissable'><h5><i class='icon fas fa-check'></i>$text</h5></div></div>";
	}
	else if($type=='update')
	echo"<div class='card-body'><div class='alert alert-success alert-dismissable'><h5><i class='icon fas fa-check'></i>$text</h5></div></div>";
}
//fungsi redirect menggunakan php
function redirect($url) {
	header("location:".$url);
}

//fungsi redirect menggunakan html
function htmlRedirect($link,$time = null) {
	if($time) $time = $time; else $time = 1;
	echo "<meta http-equiv='REFRESH' content='$time; url=$link'>";
}
//fungsi redirect menggunakan html
function LongRedirect($link,$time = null) {
	if($time) $time = $time; else $time = 5;
	echo "<meta http-equiv='REFRESH' content='$time; url=$link'>";
}
//fungsi redirect menggunakan html
function Redirect_Login($link,$time = null) {
	if($time) $time = $time; else $time = 2;
	echo "<meta http-equiv='REFRESH' content='$time; url=$link'>";
}
//fungsi redirect menggunakan html
function dlRedirect($link,$time = null) {
	if($time) $time = $time; else $time = 5;
	echo "<meta http-equiv='REFRESH' content='$time; url=$link'>";
}

function antiinjection($data){
  include "koneksi.php";
  //global $koneksi;
  $filter_sql = mysqli_real_escape_string($koneksi,stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
  return $filter_sql;
}

function filter($data) {
	include "koneksi.php";
  	//global $koneksi;
    $data = trim(htmlentities(strip_tags($data)));
 
    if (get_magic_quotes_gpc())
        $data = stripslashes($data);
 
    $data = mysqli_real_escape_string($koneksi,$data);
 
    return $data;
}
function url_origin($s, $use_forwarded_host=false)
{
    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    $sp = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port = $s['SERVER_PORT'];
    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
    $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}
function full_url($s, $use_forwarded_host=false)
{
    return url_origin($s, $use_forwarded_host) . $s['REQUEST_URI'];
}
$absolute_url = full_url($_SERVER);
?>
