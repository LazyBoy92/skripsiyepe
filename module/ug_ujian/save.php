<?php
session_start();
if (empty($_SESSION['namauser']) && empty($_SESSION['passuser'])) {
    header('location:../index.php');
    exit();
} else {

    // SIMPAN UJIAN BARU
    if (isset($_POST['simpan_tu'])) {

        // Ambil id terbaru lalu increment
        $id = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MAX(id) as jum FROM topik_ujian"));
        $idmax = $id['jum'] + 1;

        // Konversi menit ke detik
        $waktu = $_POST['waktu_pengerjaan'] * 60;

        // Insert data ujian ke topik_ujian
        $data = "INSERT INTO topik_ujian(
                    id, judul, id_mapel, tgl_buat, pembuat, waktu_pengerjaan, info, 
                    bobot_pg, bobot_esay, terbit
                 ) VALUES (
                    '$idmax', 
                    '{$_POST['judul']}', 
                    '{$_POST['id_mapel']}', 
                    '$tgl_sekarang', 
                    '{$_SESSION['id_user']}', 
                    '$waktu', 
                    '{$_POST['info']}', 
                    '{$_POST['bobot_pg']}', 
                    '{$_POST['bobot_esay']}', 
                    '{$_POST['terbit']}'
                 )";

        if (mysqli_query($koneksi, $data)) {

            // Pastikan id_kelas diambil dari form dan benar-benar ada
            if (!empty($_POST['id_kelas']) && is_array($_POST['id_kelas'])) {
                foreach ($_POST['id_kelas'] as $kelas) {
                    $kelas = trim($kelas);
                    if (!empty($kelas)) {
                        // Ambil ID terakhir di kelas_ujian
                        $last_id_row = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MAX(id) as max_id FROM kelas_ujian"));
                        $new_id = $last_id_row['max_id'] + 1;

                        // Insert hubungan kelas-ujian
                        mysqli_query($koneksi, "INSERT INTO kelas_ujian (id, id_kelas, id_topik) VALUES ('$new_id', '$kelas', '$idmax')");
                    }
                }
            }

            save_alert('save', 'Ujian Berhasil di Tambahkan');
            htmlRedirect('media.php?module=' . $module . '&act=ug_ujian');

        } else {
            save_alert('error', 'Gagal Tersimpan');
            htmlRedirect('media.php?module=' . $module . '&act=ug_ujian');
        }
    }

    // UPDATE UJIAN
    elseif (isset($_POST['update_tu'])) {

        $waktu = $_POST['waktu_pengerjaan'] * 60;

        $data  = "UPDATE topik_ujian 
                  SET judul = '{$_POST['judul']}', 
                      id_mapel = '{$_POST['id_mapel']}', 
                      waktu_pengerjaan = '$waktu', 
                      info = '{$_POST['info']}', 
                      bobot_pg = '{$_POST['bobot_pg']}', 
                      bobot_esay = '{$_POST['bobot_esay']}', 
                      terbit = '{$_POST['terbit']}' 
                  WHERE id='{$_POST['id_tujian']}'";

        if (mysqli_query($koneksi, $data)) {

            // Hapus semua kelas lama
            mysqli_query($koneksi, "DELETE FROM kelas_ujian WHERE id_topik='{$_POST['id_tujian']}'");

            // Masukkan kelas baru
            if (!empty($_POST['id_kelas']) && is_array($_POST['id_kelas'])) {
                foreach ($_POST['id_kelas'] as $kelas) {
                    $kelas = trim($kelas);
                    if (!empty($kelas)) {
                        $last_id_row = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MAX(id) as max_id FROM kelas_ujian"));
                        $new_id = $last_id_row['max_id'] + 1;

                        mysqli_query($koneksi, "INSERT INTO kelas_ujian (id, id_kelas, id_topik) VALUES ('$new_id', '$kelas', '{$_POST['id_tujian']}')");
                    }
                }
            }

            save_alert('save', 'Ujian Berhasil di Update');
            htmlRedirect('media.php?module=' . $module . '&act=ug_ujian');

        } else {
            save_alert('error', 'Gagal Tersimpan');
            htmlRedirect('media.php?module=' . $module . '&act=ug_ujian');
        }
    }

    // PROSES KOREKSI
    elseif (isset($_POST['proses_koreksi'])) {
        $data = "UPDATE nilai_esay SET nilai = '{$_POST['nilai']}' WHERE id_nesay='{$_POST['id_nesay']}'";
        if (mysqli_query($koneksi, $data)) {
            save_alert('save', 'Soal dikoreksi');
        } else {
            save_alert('error', 'Gagal dikoreksi');
        }
        htmlRedirect('media.php?module=' . $module . '&act=koreksi&ujian=' . $_POST['ujian'] . '&siswa=' . $_POST['siswa']);
    }

    // SIMPAN PILIHAN GANDA
    elseif (isset($_POST['simpan_pilganda'])) {
        $lokasi_file = $_FILES['fupload']['tmp_name'];
        $nama_file = $_FILES['fupload']['name'];
        $tipe_file = $_FILES['fupload']['type'];

        if (!empty($lokasi_file)) {
            if ($tipe_file != "image/jpeg" && $tipe_file != "image/jpg") {
                save_alert('error', 'Type File Tidak diizinkan');
                htmlRedirect('media.php?module=' . $module . '&act=pil_ganda&id=' . $_POST['id_tujian'], 1);
            } else {
                UploadImage_soal_pilganda($nama_file);
                mysqli_query($koneksi, "INSERT INTO soal_pilganda (
                    id_tujian, pertanyaan, gambar, pil_a, pil_b, pil_c, pil_d, pil_e, kunci, tgl_buat, jenis_soal
                ) VALUES (
                    '{$_POST['id_tujian']}', '{$_POST['pertanyaan']}', '$nama_file',
                    '{$_POST['pil_a']}', '{$_POST['pil_b']}', '{$_POST['pil_c']}',
                    '{$_POST['pil_d']}', '{$_POST['pil_e']}', '{$_POST['kunci']}',
                    '$tgl_sekarang', 'pil_ganda'
                )");
                save_alert('save', 'Soal Tersimpan');
                htmlRedirect('media.php?module=' . $module . '&act=pil_ganda&id=' . $_POST['id_tujian'], 1);
            }
        } else {
            mysqli_query($koneksi, "INSERT INTO soal_pilganda (
                id_tujian, pertanyaan, gambar, pil_a, pil_b, pil_c, pil_d, pil_e, kunci, tgl_buat, jenis_soal
            ) VALUES (
                '{$_POST['id_tujian']}', '{$_POST['pertanyaan']}', '',
                '{$_POST['pil_a']}', '{$_POST['pil_b']}', '{$_POST['pil_c']}',
                '{$_POST['pil_d']}', '{$_POST['pil_e']}', '{$_POST['kunci']}',
                '$tgl_sekarang', 'pil_ganda'
            )");
            save_alert('save', 'Soal Tersimpan');
            htmlRedirect('media.php?module=' . $module . '&act=pil_ganda&id=' . $_POST['id_tujian'], 1);
        }
    }

    // SIMPAN ESSAY
    elseif (isset($_POST['simpan_essay'])) {
        mysqli_query($koneksi, "INSERT INTO soal_esay (
            id_tujian, pertanyaan, tgl_buat, jenis_soal
        ) VALUES (
            '{$_POST['id_tujian']}', '{$_POST['pertanyaan']}', '$tgl_sekarang', 'essay'
        )");
        save_alert('save', 'Soal Essay Tersimpan');
        htmlRedirect('media.php?module=' . $module . '&act=essay&id=' . $_POST['id_tujian'], 1);
    }

}
?>
