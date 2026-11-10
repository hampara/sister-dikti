<!--
// =====================
// tugas_tambahan
// =====================
-->
<?php 
include 'ws.php';
$param = isset($_GET['p']) ? $_GET['p'] : "";
function do_tugas_tambahan($id_sdm) {
    $post = ["id_sdm" => $id_sdm ];
	$result_string = wsGET("tugas_tambahan", $post);
	return json_decode($result_string, true);
}
// ==============================
// mulai dr sini
// ==============================
if (isset($_POST['simpan']) || isset($_POST['simput'])) {
    $data = $dokumen = array();
    $data['id_sdm']                 = $id_sdm;
    $data['id_kategori_kegiatan']   = (int) $_POST['id_kegiatan'];
    $data['id_jenis_tugas']         = (int) $_POST['id_jenis_tugas'];
    $data['id_perguruan_tinggi']    = $_POST['id_perguruan_tinggi'];
    $data['id_unit_kerja']          = $_POST['id_unit_kerja'];
    $data['jumlah_jam']             = $_POST['jumlah_jam'];
    $data['sk_penugasan']           = $_POST['sk_penugasan'];
    $data['tanggal_mulai_tugas']    = $_POST['tanggal_mulai_tugas'];
    $data['tanggal_selesai_tugas']  = $_POST['tanggal_selesai_tugas'];
    
    $dokumen['file']            = $_FILES['file'];
    $dokumen['id_jenis_dokumen']= (int) $_POST['jenis_dok'];
    //$dokumen['id']              = $_POST['id_dok'];     // put
    //$dokumen['id_sdm']          = $_POST['id_dok'];     // post
    $dokumen['nama']            = $_POST['nama_dok'];
    $dokumen['tautan']          = $_POST['tautan_dok'];
    $dokumen['keterangan']      = $_POST['keterangan_dok'];
    /*
     $ajd = $_POST['jenis_dokumen'];
     foreach ($ajd as $isi) {
     $dokumen['id_jenis_dokumen'][]= mysqli_fetch_row(mysqli_query($link, "select id from sister where menu='jenis_dokumen' and nama ='$isi'"))[0];
     }
     */
    
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
            echo "disimpan :: ".$result['id'];
        } elseif ($resdok['kode']>202) {
            print_r(json_encode($data, JSON_PRETTY_PRINT));
        }
    } else {
        $data['id']         = $_POST['id_inpas'];
        $dokumen['id']      = $_POST['id_dok'];     // put
        //$resdok = json_decode(wsPOST_DOK($dokumen['id'], $dokumen), true);
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
    include 'menu.php';
    $keg = menu_baru($param);
    $id_keg = substr($keg, -6);
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>SDM</th><td><?=$_SESSION['login_dos']?></td></tr>
    <tr><th>Kegiatan</th><td><?=$keg?></td><td></td></tr>
    <tr><th>Jenis tugas</th><td><select name="id_jenis_tugas">
    	<?php
    	$qpg = mysqli_query($link, "select id,nama from sister where menu='id_jenis_tugas' order by cast(id as UNSIGNED)");
    	if (mysqli_num_rows($qpg)>0) {
    	    while ($rpg = mysqli_fetch_assoc($qpg)) {
    	        $ks = "";
    	        if (isset($_POST['id_jenis_tugas'])) {
    	            if ($_POST['id_jenis_tugas']==$rpg['id']) {
    	                $ks = "selected='selected'";
    	            }
    	        } echo "<option value='".$rpg['id']."' $ks>".$rpg['nama']."</option>";
    	    }
    	} ?></select></td></tr>
    <tr><th>Perguruan tinggi</th><td><input type="text" name="id_perguruan_tinggi" maxlength="80" value="<?=$_POST['id_perguruan_tinggi'] ?? '828fb966-3733-430e-86ff-909b764e2523'?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Prodi</th><td><input type="text" name="id_unit_kerja" value="<?=$_POST['id_unit_kerja'] ?? '08418295-f193-4534-a548-58bd91f64fb0'?>"></td></tr>
    <tr><th>Jumlah jam</th><td><input type="number" name="jumlah_jam" required="required" value="<?=$_POST['jumlah_jam'] ?? 40?>"></td></tr>
    <tr><th>SK</th><td><input type="text" name="sk_penugasan" value="<?=$_POST['sk_penugasan'] ?? "1234/SK/2019"?>" required="required"></td></tr>
    
    <tr><th>Tgl.mulai</th><td><input type="date" name="tanggal_mulai_tugas" value="<?=$_POST['tanggal_mulai_tugas'] ?? "2018-02-15"?>" required="required"></td></tr>
    <tr><th>Tgl.selesai</th><td><input type="date" name="tanggal_selesai_tugas" value="<?=$_POST['tanggal_selesai_tugas'] ?? "2020-02-15"?>"></td></tr>
	<tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $id_sdm);?></td></tr>
    <tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simpan" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table>
    <input type="hidden" name="id_kegiatan" value="<?=$id_keg?>"></form>
    <?php 
} elseif (isset($_GET['e'])) {
    $id = $_GET['e'];
    $result = json_decode(wsREF($param."/".$id), true);
    
    $id_sdm               = $result['id_sdm'];
    $id_kategori_kegiatan = $result['id_kategori_kegiatan'];
    $id_jenis_tugas       = $result['id_jenis_tugas'];
    $id_perguruan_tinggi  = $result['id_perguruan_tinggi'];
    $id_unit_kerja        = $result['id_unit_kerja'];
    $jumlah_jam           = $result['jumlah_jam'];
    $sk_penugasan         = $result['sk_penugasan'];
    $tanggal_mulai_tugas  = $result['tanggal_mulai_tugas'];
    $tanggal_selesai_tugas= $result['tanggal_selesai_tugas'];
    
    $dokumen = array();
    $res_dokumen    = $result['dokumen'];
    foreach ($res_dokumen as $kolom=>$isi) {
        $dokumen[$kolom] = $isi;
    }
    
    include 'menu.php';
    $keg = menu_edit($param, $id_kategori_kegiatan);
    $id_keg = substr($keg, -6);
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>SDM</th><td><?=$_SESSION['login_dos']?></td></tr>
    <tr><th>Kegiatan</th><td><?=$keg?></td><td></td></tr>
    <tr><th>id_jenis_tugas</th><td><select name="id_jenis_tugas">
    	<?php
    	$qpg = mysqli_query($link, "select id,nama from sister where menu='id_jenis_tugas' order by cast(id as UNSIGNED)");
    	if (mysqli_num_rows($qpg)>0) {
    	    while ($rpg = mysqli_fetch_assoc($qpg)) {
    	        $ks = "";
    	        if (isset($_POST['id_jenis_tugas'])) {
    	            if ($_POST['id_jenis_tugas']==$rpg['id']) {
    	                $ks = "selected='selected'";
    	            }
    	        } elseif ($id_pangkat_golongan==$rpg['id']) {
    	            $ks = "selected='selected'";
    	        } echo "<option value='".$rpg['id']."' $ks>".$rpg['nama']."</option>";
    	    }
    	} ?></select></td></tr>
    <tr><th>Perguruan tinggi</th><td><input type="text" name="id_perguruan_tinggi" maxlength="80" value="<?=$_POST['id_perguruan_tinggi'] ?? $id_perguruan_tinggi?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Prodi</th><td><input type="text" name="id_unit_kerja" value="<?=$_POST['id_unit_kerja'] ?? $id_unit_kerja?>"></td></tr>
    <tr><th>Jumlah jam</th><td><input type="number" name="jumlah_jam" required="required" value="<?=$_POST['jumlah_jam'] ?? $jumlah_jam?>"></td></tr>
    <tr><th>SK</th><td><input type="text" name="sk_penugasan" value="<?=$_POST['sk_penugasan'] ?? $sk_penugasan?>" required="required"></td></tr>
    
    <tr><th>Tgl.mulai</th><td><input type="date" name="tanggal_mulai_tugas" value="<?=$_POST['tanggal_mulai_tugas'] ?? $tanggal_mulai_tugas?>" required="required"></td></tr>
    <tr><th>Tgl.selesai</th><td><input type="date" name="tanggal_selesai_tugas" value="<?=$_POST['tanggal_selesai_tugas'] ?? $tanggal_selesai_tugas?>"></td></tr>
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
    
    $result = do_tugas_tambahan($id_sdm);
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
