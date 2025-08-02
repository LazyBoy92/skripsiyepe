<?php
if (count(get_included_files()) == 1) {
    echo "<meta http-equiv='refresh' content='0; url=http://$_SERVER[HTTP_HOST]'>";
    exit("Direct access not permitted.");
}

error_reporting(0);
session_start();
if (empty($_SESSION['namauser']) && empty($_SESSION['passuser'])) {
    header('location:../error_login.php');
    exit();
}

switch ($_GET['act']) {
    default:
        if ($_SESSION['leveluser'] == 'user_siswa') {

            include "style_menu.php";

            $id_ujian = $_GET['id'];
            $rujian = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM topik_ujian WHERE id='$id_ujian'"));

            $pg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(id_soalpg) as jum FROM soal_pilganda WHERE id_tujian='$id_ujian'"));
            $qsoal = mysqli_query($koneksi, "SELECT * FROM soal_pilganda WHERE id_tujian='$id_ujian' ORDER BY rand() LIMIT {$pg['jum']}");
            $q2soal = mysqli_query($koneksi, "SELECT * FROM soal_pilganda WHERE id_tujian='$id_ujian' ORDER BY id_soalpg");

            if (mysqli_num_rows($qsoal) == 0) {
                die('<div class="alert alert-warning">Belum ada soal pada ujian ini</div>');
            }

            $arr_soal = [];
            $arr_jawaban = [];
            while ($rsoal = mysqli_fetch_array($qsoal)) {
                $arr_soal[] = $rsoal['id_soalpg'];
                $arr_jawaban[] = 0;
            }

            $soalid = [];
            while ($r2soal = mysqli_fetch_array($q2soal)) {
                $soalid[] = $r2soal['id_soalpg'];
            }

            $acak_soal = implode(",", $arr_soal);
            $jawaban = implode(",", $arr_jawaban);

            // Ambil kelas siswa
            $nis = mysqli_fetch_array(mysqli_query($koneksi, "SELECT nis FROM siswa WHERE id='{$_SESSION['id_user']}'"));
            $kelas_data = mysqli_fetch_array(mysqli_query(
                $koneksi,
                "SELECT k.id 
                 FROM f_kelas f
                 JOIN kelas k ON f.id_kelas = k.kode_kelas
                 WHERE f.nis='{$nis['nis']}' AND f.tp='$tahun_p'"
            ));
            $kelas = $kelas_data['id'];

            $qnilai = mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_siswa='{$_SESSION['id_user']}' AND id_ujian='$id_ujian'");
            if (mysqli_num_rows($qnilai) < 1) {
                $jam  = date("H:i:s");
                $jm1  = substr($jam, 0, 2);
                $mn1  = substr($jam, 3, 2);
                $dt1  = substr($jam, 6, 2);

                $w_a  = $rujian['waktu_pengerjaan'];
                $j_w = floor($w_a / 3600);
                $m_w = floor(($w_a % 3600) / 60);
                $d_w = 0;
                $wak = sprintf("%02d:%02d:%02d", $j_w, $m_w, $d_w);

                $jm2   = substr($wak, 0, 2);
                $mn2   = substr($wak, 3, 2);
                $jam12 = $jm2 + $jm1;
                $menit = $mn2 + $mn1;

                if ($menit > 60) {
                    $hr = $jam12 + 1;
                    $mn = $menit - 60;
                } else {
                    $hr = $jam12;
                    $mn = $menit;
                }

                $waktuselesai = sprintf("%02d:%02d:%02d", $hr, $mn, $dt1);

                mysqli_query(
                    $koneksi,
                    "INSERT INTO nilai 
                     SET id_siswa='{$_SESSION['id_user']}',
                         id_ujian='$id_ujian',
                         kelas='$kelas',
                         acak_soal='$acak_soal',
                         jawaban='$jawaban',
                         sisa_waktu='$wak',
                         waktu_selesai='$waktuselesai',
                         status='mengerjakan',
                         jml_benar=0,
                         jml_kosong=0,
                         jml_salah=0,
                         nilai=0"
                ) or die(mysqli_error($koneksi));

                foreach ($soalid as $kelas_soal) {
                    mysqli_query(
                        $koneksi,
                        "INSERT INTO analisis 
                         SET id_siswa='{$_SESSION['id_user']}',
                             id_ujian='$id_ujian',
                             id_soal='$kelas_soal',
                             jawaban='0'"
                    );
                }
            } else {
                $nil = mysqli_fetch_array($qnilai);

                $jam = date("H:i:s");
                $jm1 = substr($jam, 0, 2);
                $mn1 = substr($jam, 3, 2);
                $dt1 = substr($jam, 6, 2);

                $selesai = $nil['waktu_selesai'];
                $jm2 = substr($selesai, 0, 2);
                $mn2 = substr($selesai, 3, 2);
                $dt2 = substr($selesai, 6, 2);

                $mulai   = mktime($jm1, $mn1, $dt1);
                $selesai = mktime($jm2, $mn2, $dt2);

                $lama = $selesai - $mulai;
                $hr = (int)($lama / 3600);
                $mn = (int)(($lama % 3600) / 60);
                $sc = $lama % 60;

                if ($mn < 0) {
                    mysqli_query($koneksi, "UPDATE nilai SET sisa_waktu = '00:00:01' 
                                             WHERE id_siswa='{$_SESSION['id_user']}' 
                                               AND id_ujian='$id_ujian'");
                } else {
                    mysqli_query($koneksi, "UPDATE nilai SET sisa_waktu = '$hr:$mn:$sc' 
                                             WHERE id_siswa='{$_SESSION['id_user']}' 
                                               AND id_ujian='$id_ujian'");
                }
            }

            $qnilai = mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_siswa='{$_SESSION['id_user']}' AND id_ujian='$id_ujian'");
            $rnilai = mysqli_fetch_array($qnilai);
            $sisa_waktu = explode(":", $rnilai['sisa_waktu']);
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">UJIAN : <?=$rujian['judul'];?> </h6>
            <div class="dropdown no-arrow">
                <b>Sisa Waktu : </b>
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
$arr_soal = explode(",", $rnilai['acak_soal']);
$arr_jawaban = explode(",", $rnilai['jawaban']);
for($s=0; $s<count($arr_soal); $s++) {
    $rsoal = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM soal_pilganda WHERE id_soalpg='$arr_soal[$s]'"));
    $no = $s+1;
    $soal = $rsoal['pertanyaan'];
    $active = ($no==1) ? "active" : "";

    echo '<div class="col-lg-12">
        <div class="blok-soal soal-'.$no.' '.$active.'">
        <div id="fontlembarsoal" class="fontlembarsoal">
            <span id="hurufsoal" class="bg-warning shadow"> Soal Nomor : '.$no.'</span>
        </div>
        <div id="lembaran">
            <div class="cc-selector">
                <div class="card shadow">
                    <div class="card-body">   
                        <p class="soal">'.$soal.'</p><br> 
                        <table>';

    $arr_pilihan = [
        ["no"=>1,"pilihan"=>$rsoal['pil_a']],
        ["no"=>2,"pilihan"=>$rsoal['pil_b']],
        ["no"=>3,"pilihan"=>$rsoal['pil_c']],
        ["no"=>4,"pilihan"=>$rsoal['pil_d']],
        ["no"=>5,"pilihan"=>$rsoal['pil_e']]
    ];
    $arr_huruf = ["A","B","C","D","E"];

    for($i=0;$i<=4;$i++){
        $checked = ($arr_jawaban[$s] == $arr_pilihan[$i]['no']) ? "checked" : "";
        echo '<tr>
            <td valign="top">
                <input type="radio" name="jawab-'.$no.'" id="huruf-'.$no.'-'.$i.'" '.$checked.'>
                <label for="huruf-'.$no.'-'.$i.'" class="huruf" onclick="kirim_jawaban('.$s.', '.$arr_pilihan[$i]['no'].')">'.$arr_huruf[$i].'</label>
            </td>
            <td class="pilihanjawaban" valign="top">'.$arr_pilihan[$i]['pilihan'].'</td>
        </tr>';
    }
    echo '</table>
        </div>
        <div class="card-footer">
            <div class="kakisoal">';
    if($no != 1){
        echo '<a onclick="tampil_soal('.($no-1).')"><button class="btn-sm btn-default">SOAL SEBELUMNYA</button></a>
              <label class="btn-sm btn-warning"><input type="checkbox" onchange="ragu_ragu('.$no.')"> RAGU-RAGU</label>';
    }
    if($no != count($arr_soal)){
        echo '<a onclick="tampil_soal('.($no+1).')"><button class="btn-sm btn-primary">SOAL BERIKUTNYA</button></a>';
    } else {
        echo '<a onclick="selesai()"><button class="btn-sm btn-danger">SELESAI</button></a>';
    }
    echo '</div></div></div></div></div></div>';
}
?>
</div>
<?php
        }
    break;
}
?>
