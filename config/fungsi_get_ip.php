<?php
function IP_Client() {
    $ipaddress = $_SERVER['REMOTE_ADDR'];
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'IP Tidak Dikenali';
 
    return $ipaddress;
}

// Mendapatkan jenis web browser dan os
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$browser = 'Unknown';
	$os = 'Unknown';
	if (preg_match('/linux/i', $user_agent)) {
		$os = 'Linux';
	}
	elseif (preg_match('/macintosh|mac os x/i', $user_agent)) {
		$os = 'Mac';
	}
	elseif (preg_match('/windows|win32/i', $user_agent)) {
		$os = 'Windows';
	}
	if(preg_match('/MSIE/i', $user_agent) && !preg_match('/Opera/i', $user_agent)) {
		$browser = 'Internet Explorer';
	}
	elseif(preg_match('/Firefox/i', $user_agent)) {
		$browser = 'Mozilla Firefox';
	}
	elseif(preg_match('/Chrome/i', $user_agent)) {
		$browser = 'Google Chrome';
	}
	elseif(preg_match('/Safari/i', $user_agent)) {
		$browser = 'Apple Safari';
	}
	elseif(preg_match('/Opera/i', $user_agent)) {
		$browser = 'Opera';
	}
	elseif(preg_match('/Netscape/i', $user_agent)) {
		$browser = 'Netscape';
	}
?>
