<?php
$post = ["id_kategori_kegiatan" => 'id_kk',
		"judul" => 'judul',
		"id_afliasi" => 'id_pt',
		"tahun_usulan" => 'thn_usulan',
		"tahun_kegiatan" => 'thn_kegiatan',
		"tahun_pelaksanaan" => 'thn_pelaksanaan',
		"tahun_pelaksanaan_ke" => 'thn_pelaksanaan_ke',
		"lama_kegiatan" => 'lama_kegiatan',
		"dana_dikti" => 'dana_dikti',
		"dana_perguruan_tinggi" => 'dana_pt',
		"dana_institusi_lain" => 'dana_lain',
		"sk_penugasan" => 'sk_penugasan',
		"tanggal_sk_penugasan" => 'tgl_sk_penugasan',
		"no_induk" => 'mhs_nim',
		"nama" => 'mhs_nama',
		"aktif" => true,
		"peran" => 'mhs_peran'
];

echo "<pre>";
print_r($post);
echo "</pre><hr>";


$post['id_kategori_kegiatan'] ='id_kk';
$post['judul'] ='judul';
$post['id_afliasi'] ='id_pt';
$post['tahun_usulan'] ='thn_usulan';
$post['tahun_kegiatan'] ='thn_kegiatan';
$post['tahun_pelaksanaan'] ='thn_pelaksanaan';
$post['tahun_pelaksanaan_ke'] ='thn_pelaksanaan_ke';
$post['lama_kegiatan'] ='lama_kegiatan';
$post['dana_dikti'] ='dana_dikti';
$post['dana_perguruan_tinggi'] ='dana_pt';
$post['dana_institusi_lain'] ='dana_lain';
$post['sk_penugasan'] ='sk_penugasan';
$post['tanggal_sk_penugasan'] ='tgl_sk_penugasan';

echo "<pre>";
print_r($post);
echo "</pre>";

/*
session_start();
if (!isset($_SESSION['mulai'])) {
	$_SESSION['mulai'] = date('H:i:s');
	echo "session baru<br>";
} else {
	$mulai = $_SESSION['mulai'];
	$skrng = date("H:i:s");
	$diff  = date_diff(date_create($skrng), date_create($mulai));
	$jam = $diff->format("%h");
	$mnt = $diff->format("%i");
	
	if ( ($jam>0) || ($jam == 0 && $mnt>=58)) {
		$mulai = $_SESSION['mulai'] = date('H:i:s');
		$diff  = date_diff(date_create($skrng), date_create($mulai));
		$jam = $diff->format("%h");
		$mnt = $diff->format("%i");
		echo "session baru<br>";
	}

	echo "Mulai: ".$mulai."<br>Skrng: ".$skrng."<br>";
	echo $jam." Jam, ".$mnt." menit<br><br>";
	var_dump($diff);
}
*/