<!--
// =====================
// detasering
// =====================
-->
<?php 
include 'ws.php';
$param = isset($_GET['p']) ? $_GET['p'] : "";
function do_get_detasering($id_sdm) {
	$post = ["id_sdm" => $id_sdm ];
	$result_string = wsGET("detasering", $post);
	return json_decode($result_string, true);
}
// ==============================
// mulai dr sini
// ==============================
if (isset($_POST['simpan'])) {
    $data = $doks = array();
    
    $data['id_sdm']                 = $id_sdm;
    $data['id_kategori_kegiatan']   = (int) $_POST['id_kegiatan'];
    $data['id_perguruan_tinggi']    = $id_pt;
    $data['tanggal_mulai']          = $_POST['tgl_mulai'];
    $data['tanggal_selesai']        = $_POST['tgl_selesai'];
    $data['bidang_tugas']           = $_POST['bidang_tugas'];
    $data['deskripsi_kegiatan']     = $_POST['deskripsi_kegiatan'];
    $data['metode_pelaksanaan']     = $_POST['metode_pelaksanaan'];
    $data['sk_penugasan']           = $_POST['sk'];
    $data['tanggal_sk_penugasan']   = $_POST['tgl_sk'];
    
    //
    $doks['file']            = $_FILES['file'];
    $doks['id_sdm']          = $id_sdm;
    $doks['id_jenis_dokumen']= $_POST['jenis_dok'];
    $doks['nama']            = $_POST['nama_dok'];
    $doks['tautan']          = $_POST['tautan_dok'];
    $doks['keterangan']      = $_POST['keterangan_dok'];
    
    // 1. upload dokumen dulu utk dpt ID-nya
    echo "<pre>";
    $resdok = json_decode(wsPOST("dokumen", $doks), true);
    if ($resdok['kode']==200) {
        $data['dokumen'][] = $resdok['id'];
        echo "doks OK...<br>";
    } elseif ($resdok['kode']>202) {
        $data['dokumen'][] = "00000000-0000-0000-0000-000000000000";
        echo "doks GAGAL...<br>";
        print_r(json_encode($doks, JSON_PRETTY_PRINT));
        print_r(json_encode($resdok, JSON_PRETTY_PRINT ));
    }
    
    $result = json_decode(wsPOST($param, $data), true);
    if ($resdok['kode']==200) {
        echo "data disimpan :: ".$result['id']."<br>";
    } elseif ($resdok['kode']>202) {
        print_r(json_encode($data, JSON_PRETTY_PRINT));
        print_r(json_encode($result, JSON_PRETTY_PRINT ));
        echo "data GAGAL...<br>";
    }
    echo "</pre>";
}
if (isset($_POST['simput'])) {
    $data = $dokumen = $ajd = array();
    $data['id_sdm']                 = $id_sdm;
    $data['id_kategori_kegiatan']   = substr($_POST['id_kegiatan'], -6);
    $data['id_perguruan_tinggi']    = $id_pt;
    $data['tanggal_mulai']          = $_POST['tgl_mulai'];
    $data['tanggal_selesai']        = $_POST['tgl_selesai'];
    $data['bidang_tugas']           = $_POST['bidang_tugas'];
    $data['deskripsi_kegiatan']     = $_POST['deskripsi_kegiatan'];
    $data['metode_pelaksanaan']     = $_POST['metode_pelaksanaan'];
    $data['sk_penugasan']           = $_POST['sk'];
    $data['tanggal_sk_penugasan']   = $_POST['tgl_sk'];
    
    $doks['file']            = $_FILES['file'];
    $doks['id']              = $_POST['id_dok'];
    $doks['id_jenis_dokumen']= $_POST['jenis_dok'];
    $doks['nama']            = $_POST['nama_dok'];
    $doks['tautan']          = $_POST['tautan_dok'];
    $doks['keterangan']      = $_POST['keterangan_dok'];
    
    echo "<pre>";
    if ($_FILES['file']==null && $_POST['tautan']== "") {
        echo "tidak ada berkas yg akan dilampirkan ATAU link ke berkas, hanya upload data";
        $data['dokumen'] = "00000000-0000-0000-0000-000000000000";
        $result = json_decode(wsPUT("detasering", $id_sdm, $data), true); //"1.".do_put_detasering($id, $data);
    } else {
        if ($doks['id'] == "") {
            $doks['id_sdm']            = $id_sdm;
            $resdok = json_decode(wsPOST("dokumen", $doks), true);
        } else {
            $resdok = json_decode(wsPOST("dokumen/{".$doks['id']."}", $doks), true);
        } print_r($resdok);
        if (isset($resdok['id'])) {
            $data['dokumen'][] = $resdok['id'];
        } else {
            $data['dokumen'][] = "00000000-0000-0000-0000-000000000000";
        } $result = json_decode(wsPUT("detasering", $id_sdm, $data), true);
        print_r($result);
    } echo "</pre>";
}
if (isset($_GET['t'])) {
    echo "<a href='?p=$param'>batal</a><br>";
    include 'menu.php';
    $keg = menu_baru($param);
    $id_keg = substr($keg, -6);
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>SDM</th><td><?=$_SESSION['login_dos']?></td></tr>
    <tr><th>Kegiatan<br></th><td><?=$keg?></td></tr>
    <tr><th>Tgl.mulai</th><td><input type="date" name="tgl_mulai" required="required" value="<?=$_POST['tgl_mulai'] ?? date("Y-m-d")?>"></td></tr>
    <tr><th>Tgl.selesai</th><td><input type="date" name="tgl_selesai" value="<?=$_POST['tgl_selesai'] ?? date("Y-m-d")?>"></td></tr>
    <tr><th>Bidang tugas</th><td><input type="text" name="bidang_tugas" maxlength="100" value="<?=$_POST['bidang_tugas'] ?? '(test) Bidang tugas detasering'?>" style="width: 60ch"></td></tr>
    <tr><th>Deskripsi kegiatan</th><td><input type="text" name="deskripsi_kegiatan" maxlength="100" value="<?=$_POST['deskripsi_kegiatan'] ?? '(test) Deskripsi kegiatan detasering'?>" style="width: 60ch"></td></tr>
    <tr><th>Metode pelaksanaan</th><td><input type="text" name="metode_pelaksanaan" maxlength="30" value="<?=$_POST['metode_pelaksanaan'] ?? '(test) Metode pelaksanaan'?>"  style="width: 60ch"></td></tr>
    <tr><th>SK</th><td><input type="text" name="sk" maxlength="100" value="<?=$_POST['sk'] ?? 'SK-detaserng-test'?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Tgl.SK</th><td><input type="date" name="tgl_sk" value="<?=$_POST['tgl_sk'] ?? date("Y-m-d")?>" required="required"></td></tr>
    <tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $id_sdm);?></td></tr>
	<tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simpan" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table>
    <input type="hidden" name="id_kegiatan" value="<?=$id_keg?>"></form>
    <?php 
} elseif (isset($_GET['e'])) {
    echo "<a href='?p=$param'>batal</a><br>";
    $id = $_GET['e'];
    $result = json_decode(wsREF($param."/".$id), true);
    $id_kategori_kegiatan   = $result['id_kategori_kegiatan'];
    $bidang_tugas           = $result['bidang_tugas'];
    $tanggal_mulai          = $result['tanggal_mulai'];
    $tanggal_selesai        = $result['tanggal_selesai'];
    $deskripsi_kegiatan     = $result['deskripsi_kegiatan'];
    $metode_pelaksanaan     = $result['metode_pelaksanaan'];
    $sk                     = $result['sk_penugasan'];
    $tgl_sk                 = $result['tanggal_sk_penugasan'];
    
    $dokumen = array();
    $res_dokumen = $result['dokumen'];
    foreach ($res_dokumen as $kolom=>$isi) {
        $dokumen[$kolom] = $isi;
    }
    
    include 'menu.php';
    $keg = menu_edit($param,$id_kategori_kegiatan);
    $id_keg = substr($keg, strpos($keg, "id_kegiatan :: ")+15);
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>SDM</th><td><?=$_SESSION['login_dos']?></td></tr>
    <tr><th>Kegiatan<br><?=$id_kategori_kegiatan?></th><td><?=$keg?></td></tr>
    <tr><th>Tgl.mulai</th><td><input type="date" name="tgl_mulai" required="required" value="<?=$_POST['tgl_mulai'] ?? $tanggal_mulai?>"></td></tr>
    <tr><th>Tgl.selesai</th><td><input type="date" name="tgl_selesai" value="<?=$_POST['tgl_selesai'] ?? $tanggal_selesai?>"></td></tr>
    <tr><th>Bidang tugas</th><td><input type="text" name="bidang_tugas" maxlength="100" value="<?=$_POST['bidang_tugas'] ?? $bidang_tugas?>" style="width: 60ch"></td></tr>
    <tr><th>Deskripsi kegiatan</th><td><input type="text" name="deskripsi_kegiatan" maxlength="100" value="<?=$_POST['deskripsi_kegiatan'] ?? $deskripsi_kegiatan?>" style="width: 60ch"></td></tr>
    <tr><th>Metode pelaksanaan</th><td><input type="text" name="metode_pelaksanaan" maxlength="30" value="<?=$_POST['metode_pelaksanaan'] ?? $metode_pelaksanaan?>"  style="width: 60ch"></td></tr>
    <tr><th>SK</th><td><input type="text" name="sk" maxlength="100" value="<?=$_POST['sk'] ?? $sk?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Tgl.SK</th><td><input type="date" name="tgl_sk" value="<?=$_POST['tgl_sk'] ?? $tgl_sk?>"  style="width: 60ch"></td></tr>
    <tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $dokumen);?></td></tr>
	<tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simput" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table>
    <input type="hidden" name="id_kegiatan" value="<?=$id_keg?>"></form>
    <?php
} elseif (isset($_GET['d'])) {
    $id = $_GET['d'];
    $result_string = wsREF($param."/".$id);
    $detil = json_decode($result_string, true);
    echo "<a href='?p=$param'>kembali</a> | <a href='?p=$param&t'>+ data</a><br><br>Detil <b>$param</b><pre>";
    print_r(json_encode($detil, JSON_PRETTY_PRINT));
    echo "</pre>";
} elseif (isset($_GET['h'])) {
    $id = $_GET['h'];
    $result_string = wsDEL($param,$id);
    $detil = json_decode($result_string, true);
    echo "<a href='?p=$param'>kembali</a> | <a href='?p=$param&t'>+ data</a><br><br>Detil <b>$param</b><pre>";
    print_r(json_encode($detil, JSON_PRETTY_PRINT));
    echo "</pre>";
} elseif ($param!="") {
    ?><table><tr><td><?php
    if ($param!="") {
        $result = do_get_detasering($id_sdm);
        if ($result!=null) {
            if ($result['kode']==401) {
                echo "<br>Token expired"; exit();
            }
            echo "<table><tr><td><a href='?p=$param&t'>+ data</a><br>";
            echo "<br>Record <b>$param</b><br><br><table>";
            foreach ($result as $res) {
                if (is_array($res)) {
                    echo "<tr><td><table>";
                    foreach ($res as $kol => $isi) {
                        if ($kol=="id") {
                            $kol = "<a href='?p=$param&d=$isi' title='detil'>$kol</a>";         // link detil
                            $isi = "<a href='?p=$param&e=$isi' title='edit'>$isi</a></td><td>". // link edit
                                   "<a href='?p=$param&h=$isi' title='HAPUS!!!' onclick=\"return confirm('Yakin Hapus ?')\">del!</a>";      // link hapus
                        }
                        if (is_array($isi)) {
                            echo "<tr><th style='text-align: right;'>$kol</th><td><table>";
                            foreach ($isi as $klm => $ici) {
                                echo "<tr><th style='text-align: right;'>$klm</th><td>$ici</td></tr>\n";
                            } echo "</table><br><br></td></tr>\n";
                        } else {
                            echo "<tr><th style='text-align: right;'>$kol</th><td style=\"font-family: 'Arial narrow'\">$isi</td></tr>\n";
                        }
                    } echo "</table><br><br></td></tr>\n";
                }
            } echo "</table>";
        } else {
            echo "blom ada data <b>$param</b><br><br>";
        }
    }
}
?>
</td></tr></table>
