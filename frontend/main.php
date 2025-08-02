<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);

// __DIR__ akan mengarah ke folder frontend/
include __DIR__ . '/../config/koneksi.php';
include __DIR__ . '/../config/library.php';
include __DIR__ . '/../config/fungsi_indotgl.php';

include __DIR__ . '/header.php';
include __DIR__ . '/navbar.php';

$menu = $_GET['menu'] ?? 'home';

switch ($menu) {
    case 'home':
        include __DIR__ . '/home.php';
        break;
    case 'register':
        include __DIR__ . '/register.php';
        break;
    case 'data_register':
        include __DIR__ . '/data_register.php';
        break;
    case 'proses_register':
        include __DIR__ . '/proses_register.php';
        break;
    default:
        include __DIR__ . '/eror_page.php';
        break;
}

include __DIR__ . '/footer.php';
?>
