<!--
// =====================
// jabatan_fungsional
// =====================
-->
<?php 
include 'ws.php';
function do_jabatan_fungsional($id_sdm) {
	$post = ["id_sdm" => $id_sdm ];
	$result_string = wsGET("jabatan_fungsional", $post);
	return json_decode($result_string, true);
}
$param = isset($_GET['p']) ? $_GET['p'] : "";
// ==============================
// mulai dr sini
// ==============================
if (isset($_POST['simpan']) || isset($_POST['simput'])) {
    $data = $dokumen = array();
    $data['id_sdm']              = $id_sdm;
    $data['id_jabatan_fungsional']=(int) $_POST['id_jabatan_fungsional'];
    $data['sk']                  = $_POST['sk'];
    $data['tanggal_mulai']       = $_POST['tanggal_mulai'];
    $data['angka_kredit']        = $_POST['angka_kredit'];
    $data['kelebihan_pengajaran']= $_POST['kelebihan_pengajaran'];
    $data['kelebihan_penelitian']= $_POST['kelebihan_penelitian'];
    $data['kelebihan_pengabdian']= $_POST['kelebihan_pengabdian'];
    $data['kelebihan_penunjang'] = $_POST['kelebihan_penunjang'];
    
    $doks['file']            = $_FILES['file'];
    $doks['id_jenis_dokumen']= $_POST['jenis_dok'];
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
                'id_jenis_dokumen'  => (int) $doks['id_jenis_dokumen'][$index] ?? null,
                'nama'              => $doks['nama'][$index] ?? null,
                'tautan'            => $doks['tautan'][$index] ?? null,
                'keterangan'        => $doks['keterangan'][$index] ?? null,
            ];
        }
    } else {
        $dokumen['file']            = $doks['file'];
        $dokumen['id_sdm']          = $doks['id_dok'];
        $dokumen['id_jenis_dokumen']= (int) $doks['id_jenis_dokumen'];
        $dokumen['nama']            = $doks['nama'];
        $dokumen['tautan']          = $doks['tautan'];
        $dokumen['keterangan']      = $doks['keterangan'];
    }
    
    echo "<pre>";
    if (isset($_POST['simpan'])) {
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
        $data['id']         = $_POST['id_jabatan_fungsional'];
        //$resdok = json_decode(wsPOST_DOK($dokumen['id'], $dokumen), true);
        $resdok = json_decode(wsPOST("dokumen/{".$dokumen['id']."}", $dokumen), true);
        if ($resdok['kode']==200) {
            $data['dokumen'][] = $resdok['id'];
        } elseif ($resdok['kode']>202) {
            print_r(json_encode($dokumen, JSON_PRETTY_PRINT));
            $data['dokumen'][] = null;
        }
        
        $result = json_decode(wsPUT($param, $data['id'], $data), true);
        print_r($result);
        if ($result['kode']==200) {
            echo "disimpan :: ".$result['id'];
        } elseif ($result['kode']>202) {
            print_r(json_encode($data, JSON_PRETTY_PRINT));
        }
    } echo "</pre>";
}

if (isset($_GET['t'])) {
    echo "<a href='?p=$param'>batal</a><br><br>";
    include 'menu.php';
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>SDM</th><td><?=$_SESSION['login_dos']?></td></tr>
    <tr><th>Jabatan fungsional<br></th><td><select name="id_jabatan_fungsional">
    	<?php 
    	$qjf = mysqli_query($link, "select id,nama from sister where menu='jabatan_fungsional' and id<>0 order by id");
    	if (mysqli_num_rows($qjf)>0) {
    	    while ($rjf = mysqli_fetch_assoc($qjf)) {
    	        $s = "";
    	        if (isset($_POST['id_jabatan_fungsional'])) {
    	            if ($_POST['id_jabatan_fungsional'] == $rjf['id']) {
    	                $s = "selected = 'selected'";
    	            }
    	        } echo "<option value='".$rjf['id']."' $s>".$rjf['nama']."</option>\n";
    	    }
    	} mysqli_free_result($qjf);
    	?></select></td></tr>
    <tr><th>SK</th><td><input name="sk" type="text" value="<?=$_POST['sk'] ?? '47/IT3/KP.01.00/2020' ?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Tgl. mulai</th><td><input name="tanggal_mulai" type="date" value="<?=$_POST['tanggal_mulai'] ?? '2020-03-01' ?>" required="required"></td></tr>
    <tr><th>Angka kredit</th><td><input name="angka_kredit" type="number" value="<?=$_POST['angka_kredit'] ?? 234?>"></td></tr>
    <tr><th>Kelebihan pengajaran</th><td><input name="kelebihan_pengajaran" type="number" value="<?=$_POST['kelebihan_pengajaran'] ?? 6?>"></td></tr>
    <tr><th>Kelebihan penelitian</th><td><input name="kelebihan_penelitian" type="number" value="<?=$_POST['kelebihan_penelitian'] ?? null?>"></td></tr>
    <tr><th>Kelebihan pengabdian</th><td><input name="kelebihan_pengabdian" type="number" value="<?=$_POST['kelebihan_pengabdian'] ?? null?>"></td></tr>
    <tr><th>Kelebihan penunjang</th><td><input name="kelebihan_penunjang" type="number" value="<?=$_POST['kelebihan_penunjang'] ?? null?>"></td></tr>
    <tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $id_sdm);?></td></tr>
    <tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simpan" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table></form>
    <?php 
} elseif (isset($_GET['e'])) {
    $id_jabatan_fungsional  = $_GET['e'];
    $result_string = wsREF($param."/".$id_jabatan_fungsional);
    $result = json_decode($result_string, true);
    
    $id_jabatan_fungsional  = $result['id_jabatan_fungsional'];
    $sk                     = $result['sk'];
    $tanggal_mulai          = $result['tanggal_mulai'];
    $angka_kredit           = $result['angka_kredit'];
    $kelebihan_pengajaran   = $result['kelebihan_pengajaran'];
    $kelebihan_penelitian   = $result['kelebihan_penelitian'];
    $kelebihan_pengabdian   = $result['kelebihan_pengabdian'];
    $kelebihan_penunjang    = $result['kelebihan_penunjang'];
    
    $dokumen = array();
    $res_dokumen    = $result['dokumen'];
    foreach ($res_dokumen as $kolom=>$isi) {
        $dokumen[$kolom] = $isi;
    }
    
    echo "<a href='?p=$param'>batal</a><br><br>";
    include 'menu.php';
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>SDM</th><td><?=$_SESSION['login_dos']?></td></tr>
    <tr><th>Jabatan fungsional<br></th><td><select name="id_jabatan_fungsional">
    	<?php 
    	$qjf = mysqli_query($link, "select id,nama from sister where menu='jabatan_fungsional' and id<>0 order by id");
    	if (mysqli_num_rows($qjf)>0) {
    	    while ($rjf = mysqli_fetch_assoc($qjf)) {
    	        $s = "";
    	        if (isset($_POST['id_jabatan_fungsional'])) {
    	            if ($_POST['id_jabatan_fungsional'] == $rjf['id']) {
    	                $s = "selected = 'selected'";
    	            }
    	        } elseif ($rjf['id'] == $id_jabatan_fungsional) {
    	            $s = "selected = 'selected'";
    	        } echo "<option value='".$rjf['id']."' $s>".$rjf['nama']."</option>\n";
    	    }
    	} mysqli_free_result($qjf);
    	?></select></td></tr>
    <tr><th>SK</th><td><input name="sk" type="text" value="<?=$_POST['sk'] ?? $sk ?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Tgl. mulai</th><td><input name="tanggal_mulai" type="date" value="<?=$_POST['tanggal_mulai'] ?? $tanggal_mulai ?>" required="required"></td></tr>
    <tr><th>Angka kredit</th><td><input name="angka_kredit" type="number" value="<?=$_POST['angka_kredit'] ?? $angka_kredit?>"></td></tr>
    <tr><th>Kelebihan pengajaran</th><td><input name="kelebihan_pengajaran" type="number" value="<?=$_POST['kelebihan_pengajaran'] ?? $kelebihan_pengajaran?>"></td></tr>
    <tr><th>Kelebihan penelitian</th><td><input name="kelebihan_penelitian" type="number" value="<?=$_POST['kelebihan_penelitian'] ?? $kelebihan_penelitian?>"></td></tr>
    <tr><th>Kelebihan pengabdian</th><td><input name="kelebihan_pengabdian" type="number" value="<?=$_POST['kelebihan_pengabdian'] ?? $kelebihan_pengabdian?>"></td></tr>
    <tr><th>Kelebihan penunjang</th><td><input name="kelebihan_penunjang" type="number" value="<?=$_POST['kelebihan_penunjang'] ?? $kelebihan_penunjang?>"></td></tr>
    <tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $dokumen);?></td></tr>
    <tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simput" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table>
    <input type="hidden" name="id_jab_fung" value="<?=$id_jabatan_fungsional?>"></form>
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
    $result = $result_string = do_jabatan_fungsional($id_sdm);
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
?>