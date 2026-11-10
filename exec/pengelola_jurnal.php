<!--
// =====================
// pengelola_jurnal
// =====================
-->
<?php 
include 'ws.php';
$param = isset($_GET['p']) ? $_GET['p'] : "";
function do_get_pengelola_jurnal($id_sdm) {
	$post = ["id_sdm" => $id_sdm ];
	$result_string = wsGET($param, $post);
	return json_decode($result_string, true);
}
// ==============================
// mulai dr sini
// ==============================
$a_aktif = array(
    1 => 'Aktif', 0 => 'Tidak aktif'
);
if (isset($_POST['simpan']) || isset($_POST['simput'])) {
    $data = $dokumen = array();
    $data['id_sdm']                 = $id_sdm;
    $data['id_kategori_kegiatan']   = (int) $_POST['id_keg'];
    $data['id_media_publikasi']     = $_POST['id_media_publikasi'];
    
    $data['peran']                  = $_POST['peran'];
    $data['sk_penugasan']           = $_POST['sk_penugasan'];
    //$data['tanggal_sk_penugasan']   = $_POST['tgl_sk'];
    $data['tanggal_mulai']          = $_POST['tgl_mulai'];
    $data['tanggal_selesai']        = $_POST['tgl_selesai'];
    $data['aktif']                  = $_POST['aktif'];
    
    // lampiran dokumen
    $doks['file']            = $_FILES['file'];
    $doks['id_dok']          = $_POST['id_dok'];
    $doks['id_jenis_dokumen']= (int) $_POST['jenis_dok'];
    $doks['nama']            = $_POST['nama_dok'];
    $doks['tautan']          = $_POST['tautan_dok'];
    $doks['keterangan']      = $_POST['keterangan_dok'];
    
    echo "<pre>";
    if (isset($_POST['simpan'])) {
        $dokumen['file']            = $doks['file'];
        $dokumen['id_sdm']          = $doks['id_dok'];       // post
        $dokumen['id_jenis_dokumen']= $doks['id_jenis_dokumen'];
        $dokumen['nama']            = $doks['nama'];
        $dokumen['tautan']          = $doks['tautan'];
        $dokumen['keterangan']      = $doks['keterangan'];
        // simpan
        $resdok = json_decode(wsPOST("dokumen", $dokumen), true);
        if ($resdok['kode']==200) {
            $data['dokumen'][] = $resdok['id'];
        } elseif ($resdok['kode']>202) {
            print_r(json_encode($dokumen, JSON_PRETTY_PRINT));
            $data['dokumen'][] = null;
        }
        $result = json_decode(wsPOST($param, $data), true);
        print_r($result);
        if ($resdok['kode']==200) {
            echo "disimpan :: ".$result['id'];
        } elseif ($resdok['kode']>202) {
            print_r(json_encode($data, JSON_PRETTY_PRINT));
        }
    } else {
        foreach ($doks['id_dok'] as $index => $id) {
            $dokumen[] = [
                'file'              => $doks['file'][$index] ?? null,
                'id'                => $id,
                'id_jenis_dokumen'  => $doks['id_jenis_dokumen'][$index] ?? null,
                'nama'              => $doks['nama'][$index] ?? null,
                'tautan'            => $doks['tautan'][$index] ?? null,
                'keterangan'        => $doks['keterangan'][$index] ?? null,
            ];
        }
        $data['id']         = $_POST['id_diklat'];
        $resdok = json_decode(wsPOST("dokumen/{".$dokumen['id']."}", $dokumen), true);
        
        $result = json_decode(wsPUT($param, $data['id'], $data), true);
        print_r($result);
        if ($resdok['kode']==200) {
            echo "disimpan :: ".$result['id'];
        } elseif ($resdok['kode']>202) {
            print_r(json_encode($data, JSON_PRETTY_PRINT));
        }
    } echo "</pre>";
}
if (isset($_GET['t'])) {
    echo "<a href='?p=$param'>batal</a><br>";
    include 'menu.php';
    $menu_keg = menu_baru($param);
    $id_keg = substr($menu_keg, -6);
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>Kegiatan</th><td><?=$menu_keg?></td><td></td></tr>
    <tr><th>Media publikasi</th><td><input type="text" name="id_media_publikasi" value="<?=$_POST['id_media_publikasi'] ?? $id_media_publikasi?>" maxlength="60" required="required" placeholder="harus diisi" style="width: 80ch"></td></tr>
    <tr><th>Peran</th><td><input type="text" name="peran" value="<?=$_POST['peran'] ?? 'Editor Pelaksana'?>" maxlength="30" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>

    <tr><th>SK</th><td><input type="text" name="sk_penugasan" value="<?=$_POST['sk_penugasan'] ?? '1234/SK/2019'?>" maxlength="80" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Tgl.mulai</th><td><input type="date" name="tgl_mulai" required="required" value="<?=$_POST['tgl_mulai'] ?? '2019-09-21'?>"></td></tr>
    <tr><th>Tgl.selesai</th><td><input type="date" name="tgl_selesai" value="<?=$_POST['tgl_selesai'] ?? '2021-09-21'?>"></td></tr>
    <tr><th>Aktif</th><td><select name="aktif"><?php
			foreach ($a_aktif as $id => $isi) {
                $sel = "";
                if (isset($_POST['aktif'])) {
                    if ($_POST['aktif']==$id) {
                        $sel = "selected='selected'";
                    }
                } echo "<option value='$id' $sel>$isi</option>\n";
			} ?></select></td></tr>
            	
    <tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $id_sdm);?></td></tr>

	<tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simpan" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table>
    <input type="hidden" name="id_keg" value="<?=$id_keg?>"></form><?php
} elseif (isset($_GET['e'])) {
    echo "<a href='?p=$param'>batal</a><br>";
    $id_pj = $_GET['e'];
    $result_string = wsREF("$param/$id_pj");
    $result = json_decode($result_string, true);
    
    //$jenis_diklat   = $result['jenis_diklat'];
    $id_sdm             = $result['id_sdm'];
    $id_kategori_kegiatan=$result['id_kategori_kegiatan'];
    $id_media_publikasi = $result['id_media_publikasi'];
    $peran              = $result['peran'];
    $sk_penugasan       = $result['sk_penugasan'];
    $tanggal_mulai      = $result['tanggal_mulai'];
    $tanggal_selesai    = $result['tanggal_selesai'];
    $aktif              = $result['aktif'];
 
    $dokumen = array();
    $res_dokumen    = $result['dokumen'];
    foreach ($res_dokumen as $kolom=>$isi) {
        $dokumen[$kolom] = $isi;
    }
    
    include 'menu.php';
    $menu_keg = menu_edit($param, $id_kategori_kegiatan);
    $id_keg = substr($menu_keg, -6);
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>Kegiatan</th><td><?=$menu_keg?></td><td></td></tr>
    <tr><th>Media publikasi</th><td><input type="text" name="id_media_publikasi" value="<?=$_POST['id_media_publikasi'] ?? $id_media_publikasi?>" maxlength="60" required="required" placeholder="harus diisi" style="width: 80ch"></td></tr>
    <tr><th>Peran</th><td><input type="text" name="peran" value="<?=$_POST['peran'] ?? $peran?>" maxlength="30" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>

    <tr><th>SK</th><td><input type="text" name="sk_penugasan" value="<?=$_POST['sk_penugasan'] ?? $sk_penugasan?>" maxlength="80" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Tgl.mulai</th><td><input type="date" name="tgl_mulai" required="required" value="<?=$_POST['tgl_mulai'] ?? $tanggal_mulai?>"></td></tr>
    <tr><th>Tgl.selesai</th><td><input type="date" name="tgl_selesai" value="<?=$_POST['tgl_selesai'] ?? $tanggal_selesai?>"></td></tr>
    <tr><th>Aktif</th><td><select name="aktif"><?php
			foreach ($a_aktif as $id => $isi) {
                $sel = "";
                if (isset($_POST['aktif'])) {
                    if ($_POST['aktif']==$id) {
                        $sel = "selected='selected'";
                    }
                } elseif ($aktif == $id) {
                    $sel = "selected='selected'";
                } echo "<option value='$id' $sel>$isi</option>\n";
			} ?></select></td></tr>
    <tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $dokumen);?></td></tr>

    <tr><td></td><td><hr></td></tr>
    <tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simput" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table>
    <input type="hidden" name="id_keg" value="<?=$id_keg?>">
    <input type="hidden" name="id_diklat" value="<?=$id_diklat?>"></form>
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
    
    $result = do_get_pengelola_jurnal($id_sdm);
    if ($result!=null) {
        if ($result['kode']==401) {
            echo "<br>Token expired"; exit();
        }
        echo "<a href='?p=$param&t'>+ data</a><br>";
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
