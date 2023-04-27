<?php


function nomor_pemesanan_auto($tgl)
{
    $db = db_connect();

    $quer = "SELECT max(right(no_pemesanan, 2)) AS kode FROM pemesanan WHERE tanggal = '$tgl'";
    $query = $db->query($quer)->getRowArray();

    if ($query) {
        $no = ((int)$query['kode']) + 1;
        $kd = sprintf("%02s", $no);
    } else {
        $kd = "01";
    }
    date_default_timezone_set('Asia/Jakarta');
    $nomor_auto = 'PMS' . date('dmy', strtotime($tgl)) . $kd;

    return $nomor_auto;
}

function nomor_pembelian_auto($tgl)
{
    $db = db_connect();

    $quer = "SELECT max(right(no_pembelian, 2)) AS kode FROM pembelian WHERE tanggal = '$tgl'";
    $query = $db->query($quer)->getRowArray();

    if ($query) {
        $no = ((int)$query['kode']) + 1;
        $kd = sprintf("%02s", $no);
    } else {
        $kd = "01";
    }
    date_default_timezone_set('Asia/Jakarta');
    $nomor_auto = 'PMB' . date('dmy', strtotime($tgl)) . $kd;

    return $nomor_auto;
}

function nomor_stockopname_auto($tgl)
{
    $db = db_connect();

    $quer = "SELECT max(right(nomor, 2)) AS kode FROM stock_opname WHERE tanggal = '$tgl'";
    $query = $db->query($quer)->getRowArray();

    if ($query) {
        $no = ((int)$query['kode']) + 1;
        $kd = sprintf("%02s", $no);
    } else {
        $kd = "01";
    }
    date_default_timezone_set('Asia/Jakarta');
    $nomor_auto = 'SON' . date('dmy', strtotime($tgl)) . $kd;

    return $nomor_auto;
}
