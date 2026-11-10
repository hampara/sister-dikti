<!--
// =====================
// jabatan_struktural
// =====================
-->
<?php 
include 'ws.php';
function do_jabatan_struktural($id_sdm) {
	$post = ["id_sdm" => $id_sdm ];
	$result_string = wsGET("jabatan_struktural", $post);
	return json_decode($result_string, true);
}
$param = isset($_GET['p']) ? $_GET['p'] : "";
// ==============================
// mulai dr sini
// ==============================
if (isset($_POST['simpan']) || isset($_POST['simput'])) {
    $data = $dokumen = array();
    $data['id_sdm']                 = $id_sdm;
    $data['id_kategori_kegiatan']   = (int) $_POST['id_keg'];
    $data['id_jabatan_negara']      = (int) $_POST['jabatan_negara'];
    $data['sk_jabatan']             = $_POST['sk'];
    $data['tanggal_mulai_jabatan']  = $_POST['tgl_mulai'];
    $data['tanggal_selesai_jabatan']= $_POST['tgl_mulai'];
    $data['lokasi']                 = $_POST['lokasi'];
    
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
    echo "<a href='?p=$param'>batal</a><br>";
    include 'menu.php';
    $keg = menu_baru($param);
    $id_keg = substr($keg, -6);
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>SDM</th><td><?=$_SESSION['login_dos']?></td></tr>
    <tr><th>Kegiatan</th><td><?=$keg?></td></tr>
    
    <tr><th>Jabatan</th><?php
        $qcap = mysqli_query($link, "select id,nama from sister where menu='jabatan_negara' and id<>0 order by id");
        if (mysqli_num_rows($qcap)>0) {
            ?><td><select name="jabatan_negara"><?php
            while ($rcap=mysqli_fetch_assoc($qcap)) {
                $k = "";
                if (isset($_POST['jabatan_negara'])) {
                    if ($_POST['jabatan_negara'] == $rcap['id']) {
                        $k = "selected='selected'";
                    }
                } ?><option value="<?=$rcap['id']?>" $k><?=$rcap['nama']?></option><?php
            } ?></select></td><?php 
        }?></tr>
    
    <tr><th>SK</th><td><input type="text" name="sk" maxlength="100" value="<?=$_POST['sk'] ?? '1234/SK/2019'?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Tgl.mulai</th><td><input type="date" name="tgl_mulai" required="required" value="<?=$_POST['tgl_mulai'] ?? '2019-09-21'?>"></td></tr>
    <tr><th>Tgl.selesai</th><td><input type="date" name="tgl_selesai" value="<?=$_POST['tgl_selesai'] ?? null?>"></td></tr>
    <tr><th>Lokasi</th><td><input type="text" name="lokasi" maxlength="80" value="<?=$_POST['lokasi'] ?? 'Indonesia'?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $id_sdm);?></td></tr>
    <tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simpan" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table>
    <input type="hidden" name="id_keg" value="<?=$id_keg?>"></form>
    <?php
} elseif (isset($_GET['e'])) {
    echo "<a href='?p=$param'>batal</a><br>";
    $id = $_GET['e'];
    $data[] = $id;
    $result_string = wsREF($param."/".$id, $data);
    $result = json_decode($result_string, true);
    $id_kategori_kegiatan   = $result['id_kategori_kegiatan'];
    $id_jabatan_negara      = $result['id_jabatan_negara'];
    $sk_jabatan             = $result['sk_jabatan'];
    
    $tanggal_mulai_jabatan  = $result['tanggal_mulai_jabatan'];
    $tanggal_selesai_jabatan= $result['tanggal_selesai_jabatan'];
    $lokasi                 = $result['lokasi'];
    
    $dokumen = array();
    $res_dokumen    = $result['dokumen'];
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
    
    <tr><th>Jabatan</th><?php
        $qcap = mysqli_query($link, "select id,nama from sister where menu='jabatan_negara' and id<>0 order by id");
        if (mysqli_num_rows($qcap)>0) {
            ?><td><select name="jabatan_negara"><?php
            while ($rcap=mysqli_fetch_assoc($qcap)) {
                $k = "";
                if (isset($_POST['jabatan_negara'])) {
                    if ($_POST['jabatan_negara'] == $rcap['id']) {
                        $k = "selected='selected'";
                    }
                } elseif ( $id_jabatan_negara==$rcap['id']) {
                    $k = "selected='selected'";
                } ?><option value="<?=$rcap['id']?>" $k><?=$rcap['nama']?></option><?php
            } ?></select></td><?php 
        }?></tr>
    
    <tr><th>SK</th><td><input type="text" name="sk" maxlength="100" value="<?=$_POST['sk'] ?? $sk_jabatan?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Tgl.mulai</th><td><input type="date" name="tgl_mulai" required="required" value="<?=$_POST['tgl_mulai'] ?? $tanggal_mulai_jabatan?>"></td></tr>
    <tr><th>Tgl.selesai</th><td><input type="date" name="tgl_selesai" value="<?=$_POST['tgl_selesai'] ?? $tanggal_selesai_jabatan?>"></td></tr>
    <tr><th>Lokasi</th><td><input type="text" name="lokasi" maxlength="80" value="<?=$_POST['lokasi'] ?? $lokasi?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
	<tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $dokumen);?></td></tr>
    <tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simput" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table>
    <input type="hidden" name="id_keg" value="<?=$id_keg?>"></form>
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
    $result = do_jabatan_struktural($id_sdm);
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
