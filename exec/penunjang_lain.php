<!--
// =====================
// penunjang_lain
// =====================
-->
<?php 
include 'ws.php';
$param = isset($_GET['p']) ? $_GET['p'] : "";
function do_get_penunjang_lain($id_sdm) {
	$post = ["id_sdm" => $id_sdm ];
	$result_string = wsGET("penunjang_lain", $post);
	return json_decode($result_string, true);
}
// ==============================
// mulai dr sini
// ==============================
$a_tingkat = array(
    'L' => 'Lokal', 'D' => 'Daerah', 'N' => 'Nasional', 'I' => 'Internasional'
);
if (isset($_POST['simpan']) || isset($_POST['simput'])) {
    $data = $anggota = $dokumen = array();
    $data['id_sdm']                 = $id_sdm;
    $data['id_kategori_kegiatan']   = (int) $_POST['id_kegiatan'];
    $data['nama']                   = $_POST['nama'];
    $data['id_jenis_kepanitiaan']   = $_POST['id_jenis_kepanitiaan'];
    $data['instansi']               = $_POST['instansi'];
    $data['tingkat']                = $_POST['tingkat'];
    $data['sk_penugasan']           = $_POST['sk_penugasan'];
    $data['tanggal_mulai']          = $_POST['tanggal_mulai'];
    $data['tanggal_selesai']        = $_POST['tanggal_selesai'];
    
    $anggota['id_sdm']  = $id_sdm;
    $anggota['peran']   = $_POST['peran'];
    
    $doks['file']            = $_FILES['file'];
    $doks['id_jenis_dokumen']= (int) $_POST['jenis_dok'];
    $doks['nama']            = $_POST['nama_dok'];
    $doks['tautan']          = $_POST['tautan_dok'];
    $doks['keterangan']      = $_POST['keterangan_dok'];
    $doks['id_dok']          = $_POST['id_dok'];     // post
    
    if (is_array($doks['id_jenis_dokumen'])) {
        // serialize dokumen
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
    } else {
        $dokumen['file']            = $doks['file'];
        $dokumen['id_sdm']          = $doks['id_dok'];
        $dokumen['id_jenis_dokumen']= $doks['id_jenis_dokumen'];
        $dokumen['nama']            = $doks['nama'];
        $dokumen['tautan']          = $doks['tautan'];
        $dokumen['keterangan']      = $doks['keterangan'];
    }
    
    echo "<pre>";
    if (isset($_POST['simpan'])) {
        $dokumen['id_sdm']  = $_POST['id_dok'];
        $resdok = json_decode(wsPOST("dokumen", $dokumen), true);
        if ($resdok['kode']==200) {
            $data['dokumen'][] = $resdok['id'];
        } elseif ($resdok['kode']>202) {
            echo "dokumen gagal<br>";
            print_r(json_encode($dokumen, JSON_PRETTY_PRINT));
            $data['dokumen'][] = null;
        }
        $result = json_decode(wsPOST($param, $data), true);
        print_r($result);
        if ($resdok['kode']==200) {
            echo "data disimpan :: ".$result['id'];
        } elseif ($resdok['kode']>202) {
            print_r(json_encode($data, JSON_PRETTY_PRINT));
        }
        $result = json_decode(wsPOST($param, $anggota), true);
        print_r($result);
        if ($resdok['kode']==200) {
            echo "anggota disimpan :: ".$result['id'];
        } elseif ($resdok['kode']>202) {
            print_r(json_encode($anggota, JSON_PRETTY_PRINT));
        }
    } else {
        $data['id']         = $_POST['id_inpas'];
        $dokumen['id']      = $_POST['id_dok'];     // put
        //$resdok = json_decode(wsPOST_DOK($dokumen['id'], $dokumen), true);
        $resdok = json_decode(wsPOST("dokumen/{".$dokumen['id']."}", $dokumen), true);
        
        $result = json_decode(wsPUT($param, $data['id'], $data), true);
        print_r($result);
        if ($resdok['kode']==200) {
            echo "data disimpan :: ".$result['id'];
        } elseif ($resdok['kode']>202) {
            print_r(json_encode($data, JSON_PRETTY_PRINT));
        }
        $result = json_decode(wsPUT($param, $anggota['id_sdm'], $anggota), true);
        print_r($result);
        if ($resdok['kode']==200) {
            echo "anggota disimpan :: ".$result['id'];
        } elseif ($resdok['kode']>202) {
            print_r(json_encode($anggota, JSON_PRETTY_PRINT));
        }
    } echo "</pre>";
}
if (isset($_GET['t'])) {
    include 'menu.php';
    $keg = menu_baru($param);
    $id_keg = substr($keg, -6);
    echo "<a href='?p=$param'>batal</a><br><br>";
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>SDM</th><td><?=$_SESSION['login_dos']?></td></tr>
    <tr><th>Kegiatan</th><td><?=$keg?></td><td></td></tr>
    <tr><th>Jenis kepanitiaan<br></th><td><select name="id_jenis_kepanitiaan">
    	<?php
    	$qpg = mysqli_query($link, "select id,nama from sister where menu='jenis_kepanitiaan' and id>0 order by id");
    	if (mysqli_num_rows($qpg)>0) {
    	    while ($rpg = mysqli_fetch_assoc($qpg)) {
    	        $ks = "";
    	        if (isset($_POST['id_jenis_kepanitiaan'])) {
    	            if ($_POST['id_jenis_kepanitiaan']==$rpg['id']) {
    	                $ks = "selected='selected'";
    	            }
    	        } elseif ($id_jenis_kepanitiaan==$rpg['id']) {
    	            $ks = "selected='selected'";
    	        } echo "<option value='".$rpg['id']."' $ks>".$rpg['nama']."</option>";
    	    }
    	} ?></select></td></tr>
    <tr><th>Nama kegiatan</th><td><input type="text" name="nama" maxlength="80" value="<?=$_POST['nama'] ?? 'International Symposium in Veterinary Sciences (Indonesia and Japan Vet Schools)'?>" required="required" placeholder="harus diisi" style="width: 80ch"></td></tr>
    <tr><th>Instansi</th><td><input type="text" name="instansi" maxlength="100" value="<?=$_POST['instansi'] ?? 'Institut Pertanian Bogor'?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Tingkat<br></th><td><select name="tingkat">
    	<?php
    	foreach ($a_tingkat as $id => $nama) {
    	   $ks = "";
    	   if (isset($_POST['tingkat'])) {
                if ($_POST['tingkat']==$id) {
    	           $ks = "selected='selected'";
                }
            } elseif ($tingkat==$id) {
                $ks = "selected='selected'";
            } echo "<option value='$id' $ks>$nama</option>";
    	} ?></select></td></tr>
    
    <tr><th>SK</th><td><input type="text" name="sk_penugasan" maxlength="80" value="<?=$_POST['sk_penugasan'] ?? '1234/SK/2019'?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Tgl.mulai</th><td><input type="text" name="tanggal_mulai" value="<?=$_POST['tanggal_mulai'] ?? '2019-09-21'?>" required="required"></td></tr>
    <tr><th>Tgl.selesai</th><td><input type="text" name="tanggal_selesai" value="<?=$_POST['tanggal_selesai'] ?? '2022-09-21'?>"></td></tr>
    
    <tr><th>Anggota dosen</th><td><table>
    	<tr><th>SDM</th><td><input type="text" name="id_sdm" value="<?=$id_sdm?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    	<tr><th>Peran</th><td><input type="text" name="peran" value="<?=$_POST['peran'] ?? $peran?>" style="width: 60ch"></td></tr></table>
    
	<tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $id_sdm);?></td></tr>
    <tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simpan" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table>
    <input type="hidden" name="id_kegiatan" value="<?=$id_keg?>"></form>
    <?php 
} elseif (isset($_GET['e'])) {
    $id = $_GET['e'];
    $result = json_decode(wsREF($param."/".$id), true);
    
    $id_kategori_kegiatan   = $result['id_kategori_kegiatan'] ?? null;
    $nama                   = $result['nama'] ?? "International Symposium in Veterinary Sciences (Indonesia and Japan Vet Schools)";
    $id_jenis_kepanitiaan   = $result['id_jenis_kepanitiaan'] ?? 4;
    $instansi               = $result['instansi'] ?? "Institut Pertanian Bogor";
    $tingkat                = $result['tingkat'] ?? "L";
    
    $sk_penugasan           = $result['sk_penugasan'] ?? "1234/SK/2019";
    $tanggal_mulai          = $result['tanggal_mulai'] ?? "2019-09-21";
    $tanggal_selesai        = $result['tanggal_selesai'] ?? "2020-09-21";
    
    $peran                  = $result['peran'] ?? "Anggota Komite";
    
    $dokumen = array();
    $res_dokumen    = $result['dokumen'];
    foreach ($res_dokumen as $kolom=>$isi) {
        $dokumen[$kolom] = $isi;
    }
    
    include 'menu.php';
    $keg = menu_edit($param, $id_kategori_kegiatan);
    $id_keg = substr($keg, -6);
    echo "<a href='?p=$param'>batal</a><br><br>";
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>SDM</th><td><?=$_SESSION['login_dos']?></td></tr>
    <tr><th>Kegiatan</th><td><?=$keg?></td><td></td></tr>
    <tr><th>Jenis kepanitiaan<br></th><td><select name="id_jenis_kepanitiaan">
    	<?php
    	$qpg = mysqli_query($link, "select id,nama from sister where menu='jenis_kepanitiaan' and id>0 order by id");
    	if (mysqli_num_rows($qpg)>0) {
    	    while ($rpg = mysqli_fetch_assoc($qpg)) {
    	        $ks = "";
    	        if (isset($_POST['id_jenis_kepanitiaan'])) {
    	            if ($_POST['id_jenis_kepanitiaan']==$rpg['id']) {
    	                $ks = "selected='selected'";
    	            }
    	        } elseif ($id_jenis_kepanitiaan==$rpg['id']) {
    	            $ks = "selected='selected'";
    	        } echo "<option value='".$rpg['id']."' $ks>".$rpg['nama']."</option>";
    	    }
    	} ?></select></td></tr>
    <tr><th>Nama kegiatan</th><td><input type="text" name="nama" maxlength="80" value="<?=$_POST['nama'] ?? $nama?>" required="required" placeholder="harus diisi" style="width: 80ch"></td></tr>
    <tr><th>Instansi</th><td><input type="text" name="instansi" maxlength="100" value="<?=$_POST['instansi'] ?? $instansi?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Tingkat<br></th><td><select name="tingkat">
    	<?php
    	foreach ($a_tingkat as $id => $nama) {
    	   $ks = "";
    	   if (isset($_POST['tingkat'])) {
                if ($_POST['tingkat']==$id) {
    	           $ks = "selected='selected'";
                }
            } elseif ($tingkat==$id) {
                $ks = "selected='selected'";
            } echo "<option value='$id' $ks>$nama</option>";
    	} ?></select></td></tr>
    
    <tr><th>SK</th><td><input type="text" name="sk_penugasan" maxlength="80" value="<?=$_POST['sk_penugasan'] ?? $sk_penugasan?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Tgl.mulai</th><td><input type="text" name="tanggal_mulai" value="<?=$_POST['tanggal_mulai'] ?? $tanggal_mulai?>" required="required"></td></tr>
    <tr><th>Tgl.selesai</th><td><input type="text" name="tanggal_selesai" value="<?=$_POST['tanggal_selesai'] ?? $tanggal_selesai?>"></td></tr>
    
    <tr><th>anggota_dosen</th><td><table>
    	<tr><th>SDM</th><td><input type="text" name="id_sdm" value="<?=$id_sdm?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    	<tr><th>Peran</th><td><input type="text" name="peran" value="<?=$_POST['peran'] ?? $peran?>" style="width: 60ch"></td></tr></table>
    
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
    
    $result = do_get_penunjang_lain($id_sdm);
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
