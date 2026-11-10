<!--
// =====================
// nilai_tes
// =====================
-->
<?php 
include 'ws.php';
$param = isset($_GET['p']) ? $_GET['p'] : "";
function do_nilai_tes($id_sdm) {
    $post = ["id_sdm" => $id_sdm ];
    $result_string = wsGET($param, $post);
	return json_decode($result_string, true);
}
if (isset($_POST['simpan']) || isset($_POST['simput'])) {
    $data = $dokumen = array();
    $data['id_sdm']         = $id_sdm;
    $data['id_jenis_tes']   = (int) $_POST['jenis_tes'];
    $data['nama']           = $_POST['nama_tes'];
    $data['penyelenggara']  = $_POST['penyelenggara'];
    $data['tanggal']        = $_POST['tanggal'];
    $data['tahun']          = $_POST['tahun'];
    $data['skor']           = $_POST['skor'];
    
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
        $resdok = json_decode(wsPOST("dokumen/{".$dokumen['id']."}", $dokumen), true);
        
        $data['dokumen'] = $dokumen;
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
    ?><form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>SDM</th><td><?=$_SESSION['login_dos']?></td></tr>
    <tr><th>Jenis tes</th><td><select name="jenis_tes">
    	<?php 
    	$qdl = mysqli_query($link, "select id,nama from sister where menu='jenis_tes' order by id");
    	if (mysqli_num_rows($qdl)>0) {
    	    while ($rdl = mysqli_fetch_assoc($qdl)) {
    	        $k="";
    	        if (isset($_POST['jenis_tes'])) {
    	            if ($_POST['jenis_tes'] == $rdl['id']) {
    	                $k="selected='selected'";
    	            }
    	        } echo "<option value='".$rdl['id']."' $k>".$rdl['nama']."</option>";
    	    }
    	} mysqli_free_result($qdl);
    	?></select></td></tr>
    <tr><th>Nama</th><td><input type="text" name="nama_tes" value="<?=$_POST['nama_tes'] ?? "IELTS Academic"?>" maxlength="50" required="required" placeholder="harus diisi" style="width: 40ch"></td></tr>
    <tr><th>Penyelenggara</th><td><input type="text" name="penyelenggara" value="<?=$_POST['penyelenggara'] ?? "British Council"?>" maxlength="100" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Tanggal</th><td><input type="date" name="tanggal" value="<?=$_POST['tanggal'] ?? "2019-09-14"?>" required="required"></td></tr>
    <tr><th>Tahun</th><td><input type="number" name="tahun" value="<?=$_POST['tahun'] ?? 2019?>" required="required"></td></tr>
    <tr><th>Skor</th><td><input type="number" name="skor" value="<?=$_POST['skor'] ?? 6.5?>" required="required"></td></tr>
    
    <tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $id_sdm);?></td></tr>

	<tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simput" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table></form>
    <?php
} elseif (isset($_GET['e'])) {
    echo "<a href='?p=$param'>batal</a><br>";
    $id = $_GET['e'];
    $data[] = $id;
    $result_string = wsREF($param."/{".$id."}", $data);
    $result = json_decode($result_string, true);

    $id_jenis_tes       = $result['id_jenis_tes'];
    $nama               = $result['nama'];
    $penyelenggara      = $result['penyelenggara'];
    $tanggal            = $result['tanggal'];
    $tahun              = $result['tahun'];
    $skor               = $result['skor'];
    
    $dokumen = array();
    $res_dokumen    = $result['dokumen'];
    foreach ($res_dokumen as $kolom=>$isi) {
        $dokumen[$kolom] = $isi;
    }
    ?><form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>SDM</th><td><?=$_SESSION['login_dos']?></td></tr>
    <tr><th>Jenis tes</th><td><select name="jenis_tes">
    	<?php 
    	$qdl = mysqli_query($link, "select id,nama from sister where menu='jenis_tes' order by id");
    	if (mysqli_num_rows($qdl)>0) {
    	    while ($rdl = mysqli_fetch_assoc($qdl)) {
    	        $k="";
    	        if (isset($_POST['jenis_tes'])) {
    	            if ($_POST['jenis_tes'] == $rdl['id']) {
    	                $k="selected='selected'";
    	            }
    	        } elseif ($id_jenis_tes == $rdl['id']) {
    	            $k="selected='selected'";
    	        } echo "<option value='".$rdl['id']."' $k>".$rdl['nama']."</option>";
    	    }
    	} mysqli_free_result($qdl);
    	?></select></td></tr>
    <tr><th>Nama</th><td><input type="text" name="nama_tes" value="<?=$_POST['nama_tes'] ?? $nama?>" maxlength="50" required="required" placeholder="harus diisi" style="width: 40ch"></td></tr>
    <tr><th>Penyelenggara</th><td><input type="text" name="penyelenggara" value="<?=$_POST['penyelenggara'] ?? $penyelenggara?>" maxlength="100" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Tanggal</th><td><input type="date" name="tanggal" value="<?=$_POST['tanggal'] ?? $tanggal?>" required="required"></td></tr>
    <tr><th>Tahun</th><td><input type="number" name="tahun" value="<?=$_POST['tahun'] ?? $tahun?>" required="required"></td></tr>
    <tr><th>Skor</th><td><input type="number" name="skor" value="<?=$_POST['skor'] ?? $skor?>" required="required"></td></tr>
    
    <tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $dokumen);?></td></tr>

	<tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simput" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table></form>
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
    
    $result = do_nilai_tes($id_sdm);
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
