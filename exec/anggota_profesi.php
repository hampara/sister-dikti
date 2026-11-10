<!--
// ================
// anggota_profesi
// ================
-->
<?php 
include 'ws.php';
$param = isset($_GET['p']) ? $_GET['p'] : "";
function do_get_anggota_profesi($id_sdm) {
	$post = ["id_sdm" => $id_sdm ];
	$result_string = wsGET("anggota_profesi", $post);
	return json_decode($result_string, true);
}
// ================
// mulai dr sini
// ================
//echo "<span style='padding: 4px 2px 12px 2px; color:green; font-size: x-large'>Data dapat diubah di SISTER</span><br><br>";
if (isset($_POST['simpan'])) {
    $data = $dokumen = $doks = array();
    
    $data['id_sdm']                     = $id_sdm;
    $data['id_kategori_kegiatan']       = (int) $_POST['id_kegiatan'];
    $data['nama_organisasi']            = $_POST['organisasi'];
    $data['peran']                      = $_POST['peran'];
    $data['tanggal_mulai_keanggotaan']  = $_POST['tgl_mulai'];
    $data['tanggal_selesai_keanggotaan']= $_POST['tgl_selesai'];
    $data['instansi_profesi']           = $_POST['instansi'];
    
    $dokumen['file']            = $_FILES['file'];
    //$dokumen['id']            = $_POST['id_dok'];     // put
    $dokumen['id_sdm']          = $_POST['id_dok'];     // post
    $dokumen['id_jenis_dokumen']= (int) $_POST['jenis_dok'];
    $dokumen['nama']            = $_POST['nama_dok'];
    $dokumen['tautan']          = $_POST['tautan_dok'];
    $dokumen['keterangan']      = $_POST['keterangan_dok'];
    /*
    for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
        $berkas[] = [
            'name'      => $_FILES['file']['name'][$i],
            'type'      => $_FILES['file']['type'][$i],
            'tmp_name'  => $_FILES['file']['tmp_name'][$i],
            'size'      => $_FILES['file']['size'][$i],
            'error'     => $_FILES['file']['error'][$i],
        ];
    }
    foreach ($dokumen['id_sdm'] as $index => $id_sdm) {
        $doks[] = [
            'id_sdm'            => $id_sdm,
            'file'              => $berkas[$index] ?? null,
            'id_jenis_dokumen'  => $dokumen['id_jenis_dokumen'][$index] ?? null,
            'nama'              => $dokumen['nama'][$index] ?? null,
            'tautan'            => $dokumen['tautan'][$index] ?? null,
            'keterangan'        => $dokumen['keterangan'][$index] ?? null,
        ];
    }
    */
    echo "<pre>";
    $resdok = json_decode(wsPOST("dokumen", $dokumen), true);
    print_r($resdok);
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
    } echo "</pre>";
}
if (isset($_POST['simput'])) {
    $data = $dokumen = array();
    $data['id']                         = $_GET['e'];
    $data['id_sdm']                     = $id_sdm;
    $data['id_kategori_kegiatan']       = $_POST['id_kegiatan'];
    $data['nama_organisasi']            = $_POST['organisasi'];
    $data['peran']                      = $_POST['peran'];
    $data['tanggal_mulai_keanggotaan']  = $_POST['tgl_mulai'];
    $data['tanggal_selesai_keanggotaan']= $_POST['tgl_selesai'];
    $data['instansi_profesi']           = $_POST['instansi'];
    
    $dokumen['file']            = $_FILES['file'];
    $dokumen['id']              = $_POST['id_dok'];     // put
    //$dokumen['id_sdm']        = $_POST['nama_dok'];   // post
    $dokumen['id_jenis_dokumen']= $_POST['jenis_dokumen'];
    $dokumen['nama']            = $_POST['nama_dok'];
    $dokumen['tautan']          = $_POST['tautan'];
    $dokumen['keterangan']      = $_POST['keterangan'];
    
    for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
        $berkas[] = [
            'name'      => $_FILES['file']['name'][$i],
            'type'      => $_FILES['file']['type'][$i],
            'tmp_name'  => $_FILES['file']['tmp_name'][$i],
            'size'      => $_FILES['file']['size'][$i],
            'error'     => $_FILES['file']['error'][$i],
        ];
    }
    foreach ($dokumen['id_jenis_dokumen'] as $index => $id_jenis_dokumen) {
        $doks[] = [
            'id'                => $dokumen['id'][$index] ?? null,
            'file'              => $berkas[$index] ?? null,
            'id_jenis_dokumen'  => $id_jenis_dokumen,
            'nama'              => $dokumen['nama'][$index] ?? null,
            'tautan'            => $dokumen['tautan'][$index] ?? null,
            'keterangan'        => $dokumen['keterangan'][$index] ?? null,
        ];
    }

    echo "<pre>";
    $resdok = json_decode(wsPOST("dokumen/{".$doks['id']."}", $doks), true);
    print_r($resdok);
    if ($resdok['kode']==200) {
        $data['dokumen'][] = $resdok['id'];
    } elseif ($resdok['kode']>202) {
        print_r(json_encode($doks, JSON_PRETTY_PRINT));
        $data['dokumen'][] = null;
    }
    
    $result = json_decode(wsPUT($param, $_GET['e'], $data), true);
    print_r($result);
    if ($resdok['kode']>202) {
        print_r(json_encode($data, JSON_PRETTY_PRINT));
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
    <tr><th>Kegiatan</th><td><?=$keg?></td><td></td></tr>
    <tr><th>Organisasi</th><td><input type="text" name="organisasi" maxlength="100" required="required" value="<?=$_POST['organisasi'] ?? "STEI Yogyakarta (test)"?>" style="width: 60ch" placeholder="harus diisi"></td></tr>
    <tr><th>Peran</th><td><input type="text" name="peran" id="peran" maxlength="30" required="required" value="<?=$_POST['peran'] ?? "Ketua Peran (test)"?>" style="width: 60ch" placeholder="harus diisi"></td></tr>
    <tr><th>Tgl.mulai</th><td><input type="date" name="tgl_mulai" required="required" value="<?=$_POST['tgl_mulai'] ?? date("Y-m-d")?>"></td></tr>
    <tr><th>Tgl.selesai</th><td><input type="date" name="tgl_selesai" value=""></td></tr>
    <tr><th>Instansi</th><td><input type="text" name="instansi" maxlength="100" value="<?=$_POST['instansi'] ?? "STEI Yogyakarta (test organisasi)"?>" style="width: 60ch"></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $id_sdm);?></td></tr>
    <!-- 
    <tr><th>Dokumen</th><td><table>
    	<tr><th>Berkas</th><td><input type="file" name="file"></td></tr>
    	<tr><th>ID SDM</th><td><input type="text" name="id_sdm" value="<?=$id_sdm?>" readonly="readonly" style="width: 45ch"></td></tr>
    	<tr><th>Jenis dok</th><td><select name="jenis_dokumen"><?php 
    		$qjd = mysqli_query($link, "select id,nama from sister where menu='jenis_dokumen' order by id");
    		if (mysqli_num_rows($qjd)>0) {
    		    while ($rjd = mysqli_fetch_assoc($qjd)) {
    		        $sel = ""; 
    		        if (isset($_POST['jenis_dokumen'])) {
    		            if ($_POST['jenis_dokumen']==$rjd['id']) {
    		                $sel = "selected='selected'";
    		            }
    		        } echo "<option value='".$rjd['id']."' $sel>".$rjd['nama']."</option>\n";
    		    }
    		} ?></select></td></tr>
    	<tr><th>Nama dok</th><td><input type="text" name="nama" value="<?=$_POST['nama_file'] ?? ""?>" style="width: 45ch"></td></tr>
    	<tr><th>Link</th><td><input type="text" name="tautan" placeholder="link ke file dokumen" value="<?=$_POST['tautan'] ?? ""?>" style="width: 45ch"></td></tr>
    	<tr><th>Keterangan</th><td><input type="text" name="keterangan" value="<?=$_POST['keterangan'] ?? ""?>" style="width: 45ch"></td></tr>
    	</table></td></tr>
    	-->
    <tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simpan" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table>
    <input type="hidden" name="id_kegiatan" value="<?=$id_keg?>"></form>
    <?php 
} elseif (isset($_GET['e'])) {
    echo "<a href='?p=$param'>batal</a><br>";
    $id = $_GET['e'];
    $data[] = $id;
    
    $result = json_decode(wsREF($param."/".$id), true);
    $id_kategori_kegiatan       = $result['id_kategori_kegiatan'];
    $nama_organisasi            = $result['nama_organisasi'];
    $peran                      = $result['peran'];
    $tanggal_mulai_keanggotaan  = $result['tanggal_mulai_keanggotaan'];
    $tanggal_selesai_keanggotaan= $result['tanggal_selesai_keanggotaan'];
    $instansi_profesi           = $result['instansi_profesi'];
    
    $dokumen = array();
    $res_dokumen    = $result['dokumen'];
    foreach ($res_dokumen as $kolom=>$isi) {
        $dokumen[$kolom] = $isi;
    }
    
    include 'menu.php';
    $keg = menu_edit($param,$id_kategori_kegiatan);
    $id_keg = substr($keg, -6);
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>SDM</th><td><?=$_SESSION['login_dos']?></td></tr>
    <tr><th>Kegiatan<br><?=$id_kategori_kegiatan?></th><td><?=$keg?></td></tr>
    <tr><th>Organisasi</th><td><input type="text" name="organisasi" maxlength="100" required="required" value="<?=$_POST['organisasi'] ?? $nama_organisasi?>" style="width: 60ch" placeholder="harus diisi"></td></tr>
    <tr><th>Peran</th><td><input type="text" name="peran" id="peran" maxlength="30" required="required" value="<?=$_POST['peran'] ?? $peran?>" style="width: 60ch" placeholder="harus diisi"></td></tr>
    <tr><th>Tgl.mulai</th><td><input type="date" name="tgl_mulai" required="required" value="<?=$_POST['tgl_mulai'] ?? $tanggal_mulai_keanggotaan?>"></td></tr>
    <tr><th>Tgl.selesai</th><td><input type="date" name="tgl_selesai" value="<?=$tanggal_selesai_keanggotaan?>"></td></tr>
    <tr><th>Instansi</th><td><input type="text" name="instansi" maxlength="100" value="<?=$instansi_profesi?>" style="width: 60ch"></td></tr>
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
    $result = do_get_anggota_profesi($id_sdm);
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
        echo "blom ada data <b>$param</b><br>";
    } 
}
?>