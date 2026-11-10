<!--
// =====================
// penghargaan
// =====================
-->
<?php 
include 'ws.php';
$param = isset($_GET['p']) ? $_GET['p'] : "";
function do_get_penghargaan($id_sdm) {
	$post = ["id_sdm" => $id_sdm ];
	$result_string = wsGET("penghargaan", $post);
	return json_decode($result_string, true);
}
// ==============================
// mulai dr sini
// ==============================
if (isset($_POST['simpan']) || isset($_POST['simput'])) {
    $data = $dokumen = array();
    $data['id_sdm']                 = $id_sdm;
    $data['id_kategori_kegiatan']   = (int) $_POST['id_keg'];
    $data['id_tingkat_penghargaan'] = (int) $_POST['id_tingkat_penghargaan'];
    $data['id_jenis_penghargaan']   = (int) $_POST['id_jenis_penghargaan'];
    
    $data['nama']                   = $_POST['nama'];
    $data['tahun']                  = $_POST['tahun'];
    $data['instansi_pemberi']       = $_POST['instansi_pemberi'];
    
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
    <tr><th>Tingkat&nbsp;penghargaan<br></th><td><select name="id_tingkat_penghargaan">
    	<?php
    	$qpg = mysqli_query($link, "select id,nama from sister where menu='tingkat_penghargaan' and id>0 order by id");
    	if (mysqli_num_rows($qpg)>0) {
    	    while ($rpg = mysqli_fetch_assoc($qpg)) {
    	        $ks = "";
    	        if (isset($_POST['id_tingkat_penghargaan'])) {
    	            if ($_POST['id_tingkat_penghargaan']==$rpg['id']) {
    	                $ks = "selected='selected'";
    	            }
    	        } echo "<option value='".$rpg['id']."' $ks>".$rpg['nama']."</option>";
    	    }
    	} ?></select></td></tr>
    <tr><th>Jenis&nbsp;penghargaan<br></th><td><select name="id_jenis_penghargaan">
    	<?php
    	$qpg = mysqli_query($link, "select id,nama from sister where menu='jenis_penghargaan' order by id");
    	if (mysqli_num_rows($qpg)>0) {
    	    while ($rpg = mysqli_fetch_assoc($qpg)) {
    	        $ks = "";
    	        if (isset($_POST['id_jenis_penghargaan'])) {
    	            if ($_POST['id_jenis_penghargaan']==$rpg['id']) {
    	                $ks = "selected='selected'";
    	            }
    	        } echo "<option value='".$rpg['id']."' $ks>".$rpg['nama']."</option>";
    	    }
    	} ?></select></td></tr>
    
    <tr><th>Nama</th><td><input type="text" name="nama" value="<?=$_POST['nama'] ?? 'Satyalancana Karya Satya 10 tahun'?>" maxlength="60" required="required" placeholder="harus diisi" style="width: 80ch"></td></tr>
    <tr><th>Tahun</th><td><input type="number" name="tahun" value="<?=$_POST['tahun'] ?? 2020?>" required="required"></td></tr>
	<tr><th>Instansi&nbsp;pemberi</th><td><input type="text" name="instansi_pemberi" value="<?=$_POST['instansi_pemberi'] ?? 'Presiden RI'?>" maxlength="100" required="required" placeholder="harus diisi" style="width: 80ch"></td></tr>
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
    $id_sdm                 = $result['id_sdm'];
    $id_kategori_kegiatan   = $result['id_kategori_kegiatan'];
    $id_tingkat_penghargaan = $result['id_tingkat_penghargaan'];
    $id_jenis_penghargaan   = $result['id_jenis_penghargaan'];
    
    $nama               = $result['nama'];
    $tahun              = $result['tahun'];
    $instansi_pemberi   = $result['instansi_pemberi'];
 
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
    <tr><th>Tingkat&nbsp;penghargaan<br></th><td><select name="id_tingkat_penghargaan">
    	<?php
    	$qpg = mysqli_query($link, "select id,nama from sister where menu='tingkat_penghargaan' and id>0 order by id");
    	if (mysqli_num_rows($qpg)>0) {
    	    while ($rpg = mysqli_fetch_assoc($qpg)) {
    	        $ks = "";
    	        if (isset($_POST['id_tingkat_penghargaan'])) {
    	            if ($_POST['id_tingkat_penghargaan']==$rpg['id']) {
    	                $ks = "selected='selected'";
    	            }
    	        } elseif ($id_tingkat_penghargaan==$rpg['id']) {
    	            $ks = "selected='selected'";
    	        } echo "<option value='".$rpg['id']."' $ks>".$rpg['nama']."</option>";
    	    }
    	} ?></select></td></tr>
    <tr><th>Jenis&nbsp;penghargaan<br></th><td><select name="id_jenis_penghargaan">
    	<?php
    	$qpg = mysqli_query($link, "select id,nama from sister where menu='jenis_penghargaan' order by id");
    	if (mysqli_num_rows($qpg)>0) {
    	    while ($rpg = mysqli_fetch_assoc($qpg)) {
    	        $ks = "";
    	        if (isset($_POST['id_jenis_penghargaan'])) {
    	            if ($_POST['id_jenis_penghargaan']==$rpg['id']) {
    	                $ks = "selected='selected'";
    	            }
    	        } elseif ($id_jenis_penghargaan==$rpg['id']) {
    	            $ks = "selected='selected'";
    	        } echo "<option value='".$rpg['id']."' $ks>".$rpg['nama']."</option>";
    	    }
    	} ?></select></td></tr>
    
    <tr><th>Nama</th><td><input type="text" name="nama" value="<?=$_POST['nama'] ?? $nama?>" maxlength="60" required="required" placeholder="harus diisi" style="width: 80ch"></td></tr>
    <tr><th>Tahun</th><td><input type="number" name="tahun" value="<?=$_POST['tahun'] ?? $tahun?>" required="required"></td></tr>
	<tr><th>Instansi&nbsp;pemberi</th><td><input type="text" name="instansi_pemberi" value="<?=$_POST['instansi_pemberi'] ?? $instansi_pemberi?>" maxlength="100" required="required" placeholder="harus diisi" style="width: 80ch"></td></tr>
    
    <tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $dokumen);?></td></tr>

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
    
    $result = do_get_penghargaan($id_sdm);
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
