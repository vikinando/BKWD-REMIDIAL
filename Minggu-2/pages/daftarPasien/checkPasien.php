<?php

require '../../config/koneksi.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tahun = date('Y');
    $bulan = date("m");

    $nama = $_POST['nama'];
    $no_ktp = $_POST['no_ktp'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];

    // mendapatkan data tahun dan bulan saat ini
    $searchData = "SELECT * FROM pasien";
    $query = mysqli_query($koneksi, $searchData);
    $no_rm = $tahun . $bulan . '-' . '001';

    // cek ktp tersedia ? atau tidak
    $cekNoKTP = "SELECT * FROM pasien WHERE no_ktp = '$no_ktp'";
    $queryCekKTP = mysqli_query($koneksi, $cekNoKTP);

    // cek jika nomor ktp sudah terdaftar
    if (mysqli_num_rows($queryCekKTP) > 0) {
        echo '<script>alert("No KTP telah terdaftar sebelumnya");</script>';

        // cek jika nomor ktp belum terdaftar
    } else {
        // cek jika belum ada data pada tabel pasien
        if (mysqli_num_rows($query) < 1) {
            $insertData = "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm) VALUES ('$nama', '$alamat', '$no_ktp', '$no_hp', '$no_rm')";
            $queryInsert = mysqli_query($koneksi, $insertData);
            if ($queryInsert) {
                echo '<script>alert("Pendaftaran pasien berhasil");window.location.href="../../index.php";</script>';
            } else {
                echo '<script>alert("Pendaftaran pasien gagal");window.location.href="../../index.php";</script>';
            }
        }
        // cek jika sudah ada data pada tabel pasien
        else {
            $getLastData = 'SELECT * FROM pasien ORDER BY no_rm DESC limit 1';
            $querygetData = mysqli_query($koneksi, $getLastData);
            $lastData = mysqli_fetch_assoc($querygetData);
            $substring = substr($lastData['no_rm'], 7);
            $urutanTerakhir = (int) $substring;
            $urutanTerakhir += 1;

            if ($urutanTerakhir > 99) {
                $no_rm_baru = $tahun . $bulan . '-' . $urutanTerakhir;
            } else if ($urutanTerakhir > 9 && $urutanTerakhir < 100) {
                $no_rm_baru = $tahun . $bulan . '-' . '0' . $urutanTerakhir;
            } else if ($urutanTerakhir <= 9) {
                $no_rm_baru = $tahun . $bulan . '-' . '00' . $urutanTerakhir;
            }
            $insertDataBaru = "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm) VALUES ('$nama', '$alamat', '$no_ktp', '$no_hp', '$no_rm_baru')";
            $queryInsertBaru = mysqli_query($koneksi, $insertDataBaru);

            if ($queryInsertBaru) {
                echo '<script>alert("Pendaftaran pasien berhasil");window.location.href="../../index.php";</script>';
            } else {
                echo '<script>alert("Pendaftaran pasien gagal");window.location.href="../../index.php";</script>';
            }
        }
    }
}
mysqli_close($koneksi);
