<!--
// =====================
// visiting_scientist
// =====================
-->
<?php 
include 'ws.php';
$param = isset($_GET['p']) ? $_GET['p'] : "";
function do_get_visiting_scientist($id_sdm) {
	$post = ["id_sdm" => $id_sdm ];
	$result_string = wsGET("visiting_scientist", $post);
	return json_decode($result_string, true);
}
// ==============================
// mulai dr sini
// ==============================
if (isset($_POST['simpan']) || isset($_POST['simput'])) {
    $data['id_sdm']                     = $id_sdm;
    $data['id_kategori_kegiatan']       = (int) $_POST['id_keg'] ?? 1;
    $data['id_kategori_capaian_luaran'] = $_POST['capaian'];
    $data['id_penelitian_pengabdian']   = $_POST['id_penelitian_pengabdian'];
    $data['id_perguruan_tinggi']        = $_POST['id_perguruan_tinggi'];
    $data['perguruan_tinggi']           = $_POST['perguruan_tinggi'];
    $data['lama_kegiatan']              = $_POST['lama_kegiatan'];
    $data['kegiatan_penting']           = $_POST['kegiatan_penting'];
    $data['tanggal']                    = $_POST['tanggal'];
    $data['sk_penugasan']               = $_POST['sk_penugasan'];
    $data['tanggal_sk_penugasan']       = $_POST['tanggal_sk_penugasan'];
    
    if (isset($_POST['simpan'])) {
        $result = json_decode(wsPOST($param, $data), true);
    } else {
        $result = json_decode(wsPUT($param, $id_sdm, $data), true);
    }
    if ($result['kode']==200) {
        echo "ID :: ".$result['id'];
    } else {
        echo "<pre>";
        print_r(json_encode($result, JSON_PRETTY_PRINT));
        echo "</pre>";
    }
}
if (isset($_GET['t'])) {
    include 'menu.php';
    $keg = menu_baru($param);
    $id_keg = substr($keg, -6);
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>SDM</th><td><?=$_SESSION['login_dos']?></td></tr>
    <tr><th>Kegiatan</th><td><?=$keg?></td><td></td></tr>
    
    <tr><th>Capaian</th><?php
        $qcap = mysqli_query($link, "select id,nama from sister where menu='kategori_capaian_luaran' order by id");
        if (mysqli_num_rows($qcap)>0) {
            ?><td><select name="capaian"><option value=""></option><?php
            while ($rcap=mysqli_fetch_assoc($qcap)) {
                $k = "";
                if (isset($_POST['capaian'])) {
                    if ($_POST['capaian'] == $rcap['id']) {
                        $k = "selected='selected'";
                    }
                } elseif ( $id_kategori_capaian_luaran==$rcap['id']) {
                    $k = "selected='selected'";
                } ?><option value="<?=$rcap['id']?>" <?=$k?>><?=$rcap['nama']?></option><?php
            } ?></select></td><?php 
        }?></tr>

    <tr><th>ID_penelitian_pengabdian</th><td><input type="text" name="id_penelitian_pengabdian" maxlength="50" value="<?=$_POST['id_penelitian_pengabdian'] ?? '00000000-0000-0000-0000-000000000000'?>" style="width: 40ch"></td></tr>
    <tr><th>ID PT</th><td><input type="text" name="id_perguruan_tinggi" maxlength="50" value="<?=$_POST['id_perguruan_tinggi'] ?? '828fb966-3733-430e-86ff-909b764e2523'?>" style="width: 40ch"></td></tr>
    <tr><th>PT</th><td><input type="text" name="perguruan_tinggi" maxlength="50" value="<?=$_POST['perguruan_tinggi'] ?? null?>" style="width: 40ch"></td></tr>
    
    <tr><th>Lama_kegiatan</th><td><input type="number" name="lama_kegiatan" value="<?=$_POST['lama_kegiatan'] ?? 14?>" required="required"> hari</td></tr>
    <tr><th>Kegiatan_penting</th><td><input type="text" name="kegiatan_penting" maxlength="50" value="<?=$_POST['kegiatan_penting'] ?? 'Kuliah Tamu dan Diskusi Kolaborasi Penelitian'?>" style="width: 60ch"></td></tr>
    <tr><th>Tanggal</th><td><input type="date" name="tanggal" value="<?=$_POST['tanggal'] ?? '2018-06-25'?>" required="required"></td></tr>
    <tr><th>SK</th><td><input type="text" name="sk_penugasan" value="<?=$_POST['sk_penugasan'] ?? '1234/SK/2018'?>"></td></tr>
    <tr><th>Tgl.SK</th><td><input type="date" name="tanggal_sk_penugasan" value="<?=$_POST['tanggal_sk_penugasan'] ?? '2018-01-01'?>" ></td></tr>
    <tr><td></td><td><hr></td></tr>
    
	<tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simpan" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table>
	<input type="hidden" name="id_kegiatan" value="<?=$id_keg?>"></form>
    <?php
} elseif (isset($_GET['e'])) {
    $id = $_GET['e'];
    $data[] = $id;
    $result_string = wsREF($param."/{".$id."}", $data);
    $result = json_decode($result_string, true);

    $id_kategori_kegiatan       = $result['id_kategori_kegiatan'];
    $id_kategori_capaian_luaran = $result['id_kategori_capaian_luaran'];
    $id_penelitian_pengabdian   = $result['id_penelitian_pengabdian'];
    
    $id_perguruan_tinggi    = $result['id_perguruan_tinggi'];
    $perguruan_tinggi       = $result['perguruan_tinggi'];
    $lama_kegiatan          = $result['lama_kegiatan'];
    $kegiatan_penting       = $result['kegiatan_penting'];
    $tanggal                = $result['tanggal'];
    $sk_penugasan           = $result['sk_penugasan'];
    $tanggal_sk_penugasan   = $result['tanggal_sk_penugasan'];
    
    include 'menu.php';
    $keg = menu_edit($param, $id_kategori_kegiatan);
    $id_keg = substr($keg, -6);
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>SDM</th><td><?=$_SESSION['login_dos']?></td></tr>
    <tr><th>Kegiatan</th><td><?=$keg?></td><td></td></tr>
    
    <tr><th>Capaian</th><?php
        $qcap = mysqli_query($link, "select id,nama from sister where menu='kategori_capaian_luaran' order by id");
        if (mysqli_num_rows($qcap)>0) {
            ?><td><select name="capaian"><option value=""></option><?php
            while ($rcap=mysqli_fetch_assoc($qcap)) {
                $k = "";
                if (isset($_POST['capaian'])) {
                    if ($_POST['capaian'] == $rcap['id']) {
                        $k = "selected='selected'";
                    }
                } elseif ( $id_kategori_capaian_luaran==$rcap['id']) {
                    $k = "selected='selected'";
                } ?><option value="<?=$rcap['id']?>" <?=$k?>><?=$rcap['nama']?></option><?php
            } ?></select></td><?php 
        }?></tr>

    <tr><th>ID_penelitian_pengabdian</th><td><input type="text" name="id_penelitian_pengabdian" maxlength="50" value="<?=$_POST['id_penelitian_pengabdian'] ?? $id_penelitian_pengabdian?>" style="width: 40ch"></td></tr>
    <tr><th>ID PT</th><td><input type="text" name="id_perguruan_tinggi" maxlength="50" value="<?=$_POST['id_perguruan_tinggi'] ?? $id_perguruan_tinggi?>" style="width: 40ch"></td></tr>
    <tr><th>PT</th><td><input type="text" name="perguruan_tinggi" maxlength="50" value="<?=$_POST['perguruan_tinggi'] ?? $perguruan_tinggi?>" style="width: 40ch"></td></tr>
    
    <tr><th>Lama_kegiatan</th><td><input type="number" name="lama_kegiatan" value="<?=$_POST['lama_kegiatan'] ?? $lama_kegiatan?>" required="required"> hari</td></tr>
    <tr><th>Kegiatan_penting</th><td><input type="text" name="kegiatan_penting" maxlength="50" value="<?=$_POST['kegiatan_penting'] ?? $kegiatan_penting?>" style="width: 60ch"></td></tr>
    <tr><th>Tanggal</th><td><input type="date" name="tanggal" value="<?=$_POST['tanggal'] ?? $tanggal?>" required="required"></td></tr>
    <tr><th>SK</th><td><input type="text" name="sk_penugasan" value="<?=$_POST['sk_penugasan'] ?? $sk_penugasan?>"></td></tr>
    <tr><th>Tgl.SK</th><td><input type="date" name="tanggal_sk_penugasan" value="<?=$_POST['tanggal_sk_penugasan'] ?? $tanggal_sk_penugasan?>" ></td></tr>
    <tr><td></td><td><hr></td></tr>
    
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
    
    $result = do_get_visiting_scientist($id_sdm);
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
