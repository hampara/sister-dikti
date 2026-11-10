<!--
// =====================
// diklat
// =====================
-->
<?php 
include 'ws.php';
$param = isset($_GET['p']) ? $_GET['p'] : "";
function do_get_diklat($id_sdm) {
	$post = ["id_sdm" => $id_sdm ];
	$result_string = wsGET('diklat', $post);
	return json_decode($result_string, true);
}
// ==============================
// mulai dr sini
// ==============================
if (isset($_POST['simpan']) || isset($_POST['simput'])) {
    $data = $dokumen = array();
    $data['id_sdm']                 = $id_sdm;
    $data['id_kategori_kegiatan']   = (int) $_POST['id_keg'];
    $data['id_jenis_diklat']        = (int) $_POST['id_jenis_diklat'];
    $data['nama']                   = $_POST['nama_diklat'];
    $data['penyelenggara']          = $_POST['penyelenggara'];
    $data['peran']                  = $_POST['peran'];
    $data['tingkat']                = $_POST['tingkat'];
    $data['jumlah_jam']             = $_POST['jumlah_jam'];
    $data['no_sertifikat']          = $_POST['no_sertifikat'];
    $data['tanggal_sertifikat']     = $_POST['tgl_sertifikat'];
    $data['tahun']                  = $_POST['tahun'];
    $data['lokasi']                 = $_POST['lokasi'];
    $data['tanggal_mulai']          = $_POST['tgl_mulai'];
    $data['tanggal_selesai']        = $_POST['tgl_selesai'];
    $data['sk_penugasan']           = $_POST['sk'];
    $data['tanggal_sk_penugasan']   = $_POST['tgl_sk'];
    
    // lampiran dokumen
    $doks['file']            = $_FILES['file'];
    $doks['id_dok']          = $_POST['id_dok'];
    $doks['id_jenis_dokumen']= $_POST['jenis_dok'];
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
    $menu_keg = menu_baru("diklat");
    $id_keg = substr($menu_keg, -6);
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>Kegiatan</th><td><?=$menu_keg?></td><td></td></tr>
    <tr><th>Jenis diklat</th><td><select name="jenis">
    	<?php 
    	$qdl = mysqli_query($link, "select id,nama from sister where menu='jenis_diklat' order by id");
    	if (mysqli_num_rows($qdl)>0) {
    	    while ($rdl = mysqli_fetch_assoc($qdl)) {
    	        $k="";
    	        if (isset($_POST['jenis'])) {
    	            if ($_POST['jenis'] == $rdl['id']) {
    	                $k="selected='selected'";
    	            }
    	        } echo "<option value='".$rdl['id']."' $k>".$rdl['nama']."</option>";
    	    }
    	} mysqli_free_result($qdl);
    	?></select></td></tr>
    <tr><th>Nama diklat</th><td><input type="text" name="nama_diklat" value="<?=$_POST['nama'] ?? '(test) Diklatsar'?>" maxlength="160" required="required" placeholder="harus diisi" style="width: 80ch"></td></tr>
    <tr><th>Penyelenggara</th><td><input type="text" name="penyelenggara" value="<?=$_POST['penyelenggara'] ?? '(test) Basarnas'?>" maxlength="100" required="required" placeholder="harus diisi" style="width: 80ch"></td></tr>
    <tr><th>Peran</th><td><input type="text" name="peran" value="<?=$_POST['peran'] ?? '(test) Team Pendukung'?>" maxlength="30" style="width: 60ch"></td></tr>
    <tr><th>Tingkat</th><td><select name="tingkat">
    	<?php 
    	$a_tingkat = array(1=>"Lokal", 2=>"Regional", 3=>"Nasional", 4=>"Internasional");
    	foreach ($a_tingkat as $id=>$isi) {
	        $k="";
	        if (isset($_POST['tingkat'])) {
	            if ($_POST['tingkat'] == $id) {
	                $k="selected='selected'";
	            }
	        } echo "<option value='$id' $k>$isi</option>";
    	} ?></select></td></tr>
    <tr><th>Jumlah jam</th><td><input type="number" name="jumlah_jam" min="1" max="1000" value="<?=$_POST['jumlah_jam'] ?? 6?>"></td></tr>
    <tr><th>No sertifikat</th><td><input type="text" name="no_sertifikat" value="<?=$_POST['no_sertifikat'] ?? '(test) BS-DLS/xxix/09.90'?>" maxlength="30" style="width: 60ch"></td></tr>
    <tr><th>Tgl.sertifikat</th><td><input type="date" name="tgl_sertifikat" value="<?=$_POST['tgl_sertifikat'] ?? date("Y-m-d")?>" maxlength="30" style="width: 60ch"></td></tr>
    <tr><th>Tahun</th><td><input type="number" name="tahun" value="<?=isset($_POST['tahun']) ? $_POST['tahun'] : date('Y')?>" min="<?=(date("Y")-5)?>" max="<?=date("Y")?>" required="required" placeholder="harus diisi"></td></tr>
    <tr><th>Lokasi</th><td><input type="text" name="lokasi" value="<?=$_POST['lokasi'] ?? '(test) Yogyakarta'?>" maxlength="30" style="width: 60ch"></td></tr>
    <tr><th>Tgl.mulai</th><td><input type="date" name="tgl_mulai" required="required" value="<?=$_POST['tgl_mulai'] ?? date('Y-m-d')?>"></td></tr>
    <tr><th>Tgl.selesai</th><td><input type="date" name="tgl_selesai" value="<?=$_POST['tgl_selesai'] ?? ''?>"></td></tr>
    
    <tr><th>SK</th><td><input type="text" name="sk" value="<?=$_POST['sk'] ?? '(test) surat tugas diklatsar'?>" maxlength="80" style="width: 60ch"></td></tr>
    <tr><th>Tgl.SK</th><td><input type="date" name="tgl_sk" value="<?=$_POST['tgl_sk'] ?? ""?>"></td></tr>
    <tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $id_sdm);?></td></tr>
    <tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simpan" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table>
    <input type="hidden" name="id_keg" value="<?=$id_keg?>"></form><?php
} elseif (isset($_GET['e'])) {
    echo "<a href='?p=$param'>batal</a><br>";
    $id = $_GET['e'];
    $result = json_decode(wsREF($param."/".$id), true);
    
    //$jenis_diklat   = $result['jenis_diklat'];
    $id_sdm             = $result['id_sdm'];
    $id_kategori_kegiatan=$result['id_kategori_kegiatan'];
    $id_jenis_diklat    = $result['id_jenis_diklat'];
    $nama               = $result['nama_diklat'];
    $penyelenggara      = $result['penyelenggara'];
    $peran              = $result['peran'];
    $tingkat            = $result['tingkat'];
    $jumlah_jam         = $result['jumlah_jam'];
    $no_sertifikat      = $result['no_sertifikat'];
    $tanggal_sertifikat = $result['tanggal_sertifikat'];
    $tahun              = $result['tahun'];
    $lokasi             = $result['lokasi'];
    $tanggal_mulai      = $result['tanggal_mulai'];
    $tanggal_selesai    = $result['tanggal_selesai'];
    $sk_penugasan       = $result['sk_penugasan'];
    $tanggal_sk_penugasan=$result['tanggal_sk_penugasan'];
 
    $dokumen = array();
    $res_dokumen    = $result['dokumen'];
    foreach ($res_dokumen as $kolom=>$isi) {
        $dokumen[$kolom] = $isi;
    }
    
    include 'menu.php';
    $menu_keg = menu_edit("diklat", $id_kategori_kegiatan);
    $id_keg = substr($menu_keg, -6);
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>Kegiatan</th><td><?=$menu_keg?></td><td></td></tr>
    <tr><th>Jenis diklat</th><td><select name="jenis">
    	<?php 
    	$qdl = mysqli_query($link, "select id,nama from sister where menu='jenis_diklat' order by id");
    	if (mysqli_num_rows($qdl)>0) {
    	    while ($rdl = mysqli_fetch_assoc($qdl)) {
    	        $k="";
    	        if (isset($_POST['jenis'])) {
    	            if ($_POST['jenis'] == $rdl['id']) {
    	                $k="selected='selected'";
    	            }
    	        } elseif ($id_jenis_diklat == $rdl['id']) {
    	            $k="selected='selected'";
    	        } echo "<option value='".$rdl['id']."' $k>".$rdl['nama']."</option>";
    	    }
    	} mysqli_free_result($qdl);
    	?></select></td></tr>
    <tr><th>Nama diklat</th><td><input type="text" name="nama_diklat" value="<?=$_POST['nama_diklat'] ?? $$nama?>" maxlength="160" required="required" placeholder="harus diisi" style="width: 80ch"></td></tr>
    <tr><th>Penyelenggara</th><td><input type="text" name="penyelenggara" value="<?=$_POST['penyelenggara'] ?? $penyelenggara?>" maxlength="100" required="required" placeholder="harus diisi" style="width: 80ch"></td></tr>
    <tr><th>Peran</th><td><input type="text" name="peran" value="<?=$_POST['peran'] ?? $peran?>" maxlength="30" style="width: 60ch"></td></tr>
    <tr><th>Tingkat</th><td><select name="tingkat">
    	<?php 
    	$a_tingkat = array(1=>"Lokal", 2=>"Regional", 3=>"Nasional", 4=>"Internasional");
    	foreach ($a_tingkat as $id=>$isi) {
	        $k="";
	        if (isset($_POST['tingkat'])) {
	            if ($_POST['tingkat'] == $id) {
	                $k="selected='selected'";
	            }
	        } elseif ($tingkat == $id) {
	            $k="selected='selected'";
	        } echo "<option value='$id' $k>$isi</option>";
    	} ?></select></td></tr>
    <tr><th>Jumlah jam</th><td><input type="number" name="jumlah_jam" value="<?=$_POST['jumlah_jam'] ?? $jumlah_jam?>"></td></tr>
    <tr><th>No sertifikat</th><td><input type="text" name="no_sertifikat" value="<?=$_POST['no_sertifikat'] ?? $no_sertifikat?>" maxlength="30" style="width: 60ch"></td></tr>
    <tr><th>Tgl.sertifikat</th><td><input type="date" name=tgl_sertifikat" value="<?=$_POST['tgl_sertifikat'] ?? $tanggal_sertifikat?>" maxlength="30" style="width: 60ch"></td></tr>
    <tr><th>Tahun</th><td><input type="number" name="tahun" value="<?=$_POST['tahun'] ?? $tahun?>" min="<?=(date("Y")-5)?>" max="<?=date("Y")?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Lokasi</th><td><input type="text" name="no_sertifikat" value="<?=$_POST['lokasi'] ?? $lokasi?>" maxlength="30" style="width: 60ch"></td></tr>
    <tr><th>Tgl.mulai</th><td><input type="date" name="tgl_mulai" required="required" value="<?=$_POST['tgl_mulai'] ?? $tanggal_mulai?>"></td></tr>
    <tr><th>Tgl.selesai</th><td><input type="date" name="tgl_selesai" value="<?=$_POST['tgl_selesai'] ?? $tanggal_selesai?>"></td></tr>
    
    <tr><th>SK</th><td><input type="text" name="sk" value="<?=$_POST['sk'] ?? $sk_penugasan?>" maxlength="80" style="width: 60ch"></td></tr>
    <tr><th>Tgl.SK</th><td><input type="date" name="tgl_sk" value="<?=$_POST['tgl_sk'] ?? $tanggal_sk_penugasan?>"></td></tr>
    <tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $dokumen);?></td></tr>
    <tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simput" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table>
    <input type="hidden" name="id_keg" value="<?=$id_keg?>">
    <input type="hidden" name="id_diklat" value="<?=$id_diklat?>"></form>
    <a href="?p=<?=$param?>">batal</a><?php
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
    $result = do_get_diklat($id_sdm);
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
    } ?></td></tr></table><?php
    ?><a href="?p=<?=$param?>&t">tambah data</a><?php
}
