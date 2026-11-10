<!--
// =====================
// inpassing
// =====================
-->
<?php 
include 'ws.php';
$param = isset($_GET['p']) ? $_GET['p'] : "";
function do_get_inpassing($id_sdm) {
    $post = ["id_sdm" => $id_sdm ];
    $result_string = wsGET("inpassing", $post);
    return json_decode($result_string, true);
}
// ==============================
// mulai dr sini
// ==============================
if (isset($_POST['simpan']) || isset($_POST['simput'])) {
    $data = $dokumen = array();
    $data['id_sdm']             = $id_sdm;
    $data['id_pangkat_golongan']= (int) $_POST['pangkat'];
    $data['sk']                 = $_POST['sk'];
    $data['tanggal_sk']         = $_POST['tgl_sk'];
    $data['tanggal_mulai']      = $_POST['tgl_mulai'];
    $data['angka_kredit']       = $_POST['kredit'];
    $data['masa_kerja_tahun']   = $_POST['masa_kerja_tahun'];
    $data['masa_kerja_bulan']   = $_POST['masa_kerja_bulan'];
    
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
        //$resdok = json_decode(wsPUT_DOK($dokumen['id'], $dokumen), true);
        $resdok = json_decode(wsPOST("dokumen/{".$dokumen['id']."}", $dokumen), true);
        if ($resdok['kode']==200) {
            $data['dokumen'][] = $resdok['id'];
        } elseif ($resdok['kode']>202) {
            echo "dokumen gagal<br>";
            print_r(json_encode($dokumen, JSON_PRETTY_PRINT));
            $data['dokumen'][] = null;
        }
        
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
    echo "<a href='?p=$param'>batal</a><br><br>";
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th style="text-align: right;">SDM</th><td><?=$_SESSION['login_dos']?></td></tr>
    <tr><th style="text-align: right;">Pangkat/gol</th><td><select name="pangkat">
    	<?php
    	$qpg = mysqli_query($link, "select id,isi,ket from pendukung where item='pangkat' order by ket");
    	if (mysqli_num_rows($qpg)>0) {
    	    while ($rpg = mysqli_fetch_assoc($qpg)) {
    	        $k = "";
    	        $pkt = $rpg['ket']." ".$rpg['isi'];
    	        if (isset($_POST['pangkat'])) {
    	            if ($_POST['pangkat']==$rpg['id']) {
    	                $k = "selected='selected'";
    	            }
    	        } echo "<option value='".$rpg['id']."' $k>".$pkt."</option>\n";
    	    }
    	} ?></select></td></tr>
    <tr><th style="text-align: right;">SK</th><td><input type="text" name="sk" maxlength="80" required="required" value="<?=$_POST['sk'] ?? 'B-382/Kop.Wil.IIl/Sekretaris/11/2022'?>" style="width: 60ch"></td></tr>
    <tr><th style="text-align: right;">Tgl.SK</th><td><input type="date" name="tgl_sk" value="<?=$_POST['tanggal_sk'] ?? date('Y-m-d')?>"></td></tr>
    <tr><th style="text-align: right;">Tgl.mulai</th><td><input type="date" name="tgl_mulai" required="required" value="<?=$_POST['tgl_mulai'] ?? date('Y-m-d')?>"></td></tr>
    <tr><th style="text-align: right;">Kredit</th><td><input type="text" name="kredit" id="kredit" maxlength="4" required="required" value="<?=$_POST['kredit'] ?? 234?>"></td></tr>
    <tr><th style="text-align: right;">Masa kerja (thn)</th><td><input type="text" name="masa_kerja_tahun" id="masa_kerja_tahun" maxlength="2" required="required" value="<?=$_POST['masa_kerja_tahun'] ?? 8?>"></td></tr>
    <tr><td style="text-align: right;">Masa kerja (bulan)</td><td><input type="text" name="masa_kerja_bulan" id="masa_kerja_bulan" maxlength="2" required="required" value="<?=$_POST['masa_kerja_bulan'] ?? 6?>"></td></tr>
	<tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $id_sdm);?></td></tr>
    <tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simpan" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table></form>
    <?php 
} elseif (isset($_GET['e'])) {
    $id = $_GET['e'];
    $result = json_decode(wsREF($param."/".$id), true);
    
    $id_sdm             = $result['id_sdm'];
    $id_pangkat_golongan= $result['id_pangkat_golongan'];
    $sk                 = $result['sk'];
    $tanggal_sk         = $result['tanggal_sk'];
    $tanggal_mulai      = $result['tanggal_mulai'];
    $angka_kredit       = $result['angka_kredit'];
    $masa_kerja_tahun   = $result['masa_kerja_tahun'];
    $masa_kerja_bulan   = $result['masa_kerja_bulan'];
    
    $dokumen = array();
    $res_dokumen    = $result['dokumen'];
    foreach ($res_dokumen as $kolom=>$isi) {
        $dokumen[$kolom] = $isi;
    }
    
    include 'menu.php';
    echo "<a href='?p=$param'>batal</a><br><br>";
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>SDM</th><td><?=$_SESSION['login_dos']?></td></tr>
    <tr><th>Pangkat/gol<br></th><td><select name="pangkat">
    	<?php
    	$qpg = mysqli_query($link, "select id,nama from sister where menu='golongan_pangkat' order by id");
    	if (mysqli_num_rows($qpg)>0) {
    	    while ($rpg = mysqli_fetch_assoc($qpg)) {
    	        $ks = "";
    	        if (isset($_POST['pangkat'])) {
    	            if ($_POST['pangkat']==$rpg['id']) {
    	                $ks = "selected='selected'";
    	            }
    	        } elseif ($id_pangkat_golongan==$rpg['id']) {
    	            $ks = "selected='selected'";
    	        } echo "<option value='".$rpg['id']."' $ks>".$rpg['ket']." ".$rpg['nama']."</option>";
    	    }
    	} ?></select></td></tr>
    <tr><th>SK</th><td><input type="text" name="sk" maxlength="80" value="<?=$_POST['sk'] ?? $sk?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Tgl.SK</th><td><input type="date" name="tgl_sk" value="<?=$_POST['tgl_sk'] ?? $tanggal_sk?>"></td></tr>
    <tr><th>Tgl.mulai</th><td><input type="date" name="tgl_mulai" required="required" value="<?=$_POST['tgl_mulai'] ?? $tanggal_mulai?>"></td></tr>
    <tr><th>Angka kredit</th><td><input type="number" name="kredit" min="0" value="<?=$_POST['angka_kredit'] ?? $angka_kredit?>" required="required"></td></tr>
    
    <tr><th>Masa kerja (thn)</th><td><input type="number" name="masa_kerja_tahun" min="0" value="<?=isset($_POST['masa_kerja_tahun']) ? $_POST['masa_kerja_tahun'] : $masa_kerja_tahun?>" required="required"></td></tr>
    <tr><th>Masa kerja (bln)</th><td><input type="number" name="masa_kerja_bulan" min="0" value="<?=isset($_POST['masa_kerja_bulan']) ? $_POST['masa_kerja_bulan'] : $masa_kerja_bulan?>" required="required"></td></tr>
	<tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $dokumen);?></td></tr>    
    <tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simput" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table>
    <input type="hidden" name="id_inpas" value="<?=$id?>"></form>
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
    $result = do_get_inpassing($id_sdm);
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
    ?><a href="?p=<?=$param?>&t">tambah data</a> <?=$btl?><?php 
 }
 ?>
<script>
	setPhoneInputOnly('kredit');
	setPhoneInputOnly('masa_kerja_thn');
	setPhoneInputOnly('masa_kerja_bul');
</script>
