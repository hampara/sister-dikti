<!--
// =====================
// orasi_ilmiah
// =====================
-->
<?php 
include 'ws.php';
$param = isset($_GET['p']) ? $_GET['p'] : "";
function do_get_orasi_ilmiah($id_sdm) {
	$post = ["id_sdm" => $id_sdm ];
	$result_string = wsGET($param, $post);
	return json_decode($result_string, true);
}
$kategori_pembicara = array (
    1=>"Pembicara pada pertemuan ilmiah",
    2=>"Pembicara kunci",
    3=>"Pembicara/narasumber pada pelatihan/penyuluhan/ceramah"
);
$tingkat_pertemuan = array(
    "L" => "Lokal",
    "D" => "Daerah", 
    "N" => "Nasional", 
    "I" => "Internasional", 
    "X" => "Lain-lain"
);
// ==============================
// mulai dr sini
// ==============================
if (isset($_POST['simpan']) || isset($_POST['simput'])) {
    $data = $dokumen = $penulis = array();
    
    // data
    $data['id_sdm']                     = $id_sdm;
    $data['id_kategori_kegiatan']       = (int) $_POST['id_kegiatan'];
    $data['id_kategori_capaian_luaran'] = (int) $_POST['capaian'];
    $data['id_penelitian_pengabdian']   = $_POST['id_penelitian'];
    $data['id_kategori_pembicara']      = (int) $_POST['id_kategori_pembicara'];
    $data['judul_makalah']              = $_POST['judul_makalah'];
    $data['nama_pertemuan']             = $_POST['nama_pertemuan'];
    $data['id_tingkat_pertemuan']       = $_POST['tingkat_pertemuan'];
    $data['penyelenggara']              = $_POST['penyelenggara'];
    $data['tanggal_pelaksanaan']        = $_POST['tanggal_pelaksanaan'];
    $data['bahasa']                     = $_POST['bahasa'];
    $data['sk_penugasan']               = $_POST['sk_penugasan'];
    $data['tanggal_sk_penugasan']       = $_POST['tanggal_sk_penugasan'];
    
    // lampiran dokumen
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
    
    $resdok = json_decode(wsPOST("dokumen", $dokumen), true);
    //print_r($resdok);
    if ($resdok['kode']==200) {
        $data['dokumen'][] = $resdok['id'];
        echo "doks OK...<br>";
    } elseif ($resdok['kode']>202) {
        $data['dokumen'][] = "00000000-0000-0000-0000-000000000000";
        echo "doks GAGAL...<br>";
        print_r(json_encode($dokumen, JSON_PRETTY_PRINT));
        print_r(json_encode($resdok, JSON_PRETTY_PRINT ));
    }
    
    $result = json_decode(wsPOST($param, $data), true);
    if ($resdok['kode']==200) {
        echo "data disimpan :: ".$result['id']."<br>";
    } elseif ($resdok['kode']>202) {
        print_r(json_encode($data, JSON_PRETTY_PRINT));
        print_r(json_encode($result, JSON_PRETTY_PRINT ));
        echo "data GAGAL...<br>";
    } echo "</pre>";
}

if (isset($_GET['t'])) {
    echo "<a href='?p=$param'>batal</a><br><br>";
    include 'menu.php';
    $keg = menu_baru($param);
    $id_keg = substr($keg, -6);
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>Kegiatan</th><td><?=$keg?></td><td></td></tr>
    
    <tr><th>Capaian</th><?php
        $qcap = mysqli_query($link, "select id,nama from sister where menu='kategori_capaian_luaran' order by id");
        if (mysqli_num_rows($qcap)>0) {
            ?><td><select name="capaian"><?php
            while ($rcap=mysqli_fetch_assoc($qcap)) {
                $k = "";
                if (isset($_POST['capaian'])) {
                    if ($_POST['capaian'] == $rcap['id']) {
                        $k = "selected='selected'";
                    }
                } ?><option value="<?=$rcap['id']?>" <?=$k?>><?=$rcap['nama']?></option><?php
            } ?></select></td><?php 
        }?></tr>
    <tr><th>ID penelitian</th><td><input type="text" name="id_penelitian" value="<?=$_POST['id_penelitian'] ?? ""?>" style="width: 80ch"></td></tr>
	<tr><th>Kategori pembicara</th><td><select name="id_kategori_pembicara"><?php 
            foreach ($kategori_pembicara as $id=>$isi) {
    	       if (isset($_POST['id_kategori_pembicara'])) {
    		      if ($_POST['id_kategori_pembicara'] == $id) {
    		          $k = "selected='selected'";
                  }
    		   } echo "<option value='$id' $k>$isi</option>";
            } ?></select></td></tr>
    <tr><th>Judul</th><td><input type="text" name="judul_makalah" maxlength="500" value="<?=$_POST['judul_makalah'] ?? '(test) judul_makalah'?>" required="required" placeholder="harus diisi" style="width: 80ch"></td></tr>
    <tr><th>Nama pertemuan</th><td><input type="text" name="nama_pertemuan" maxlength="160" value="<?=$_POST['nama_pertemuan'] ?? '(test) nama_pertemuan'?>" style="width: 60ch" ></td></tr>
    <tr><th>Tingkat pertemuan</th><td><select name="tingkat_pertemuan"><?php
            foreach ($tingkat_pertemuan as $id=>$isi) {
    	       if (isset($_POST['tingkat_pertemuan'])) {
    		      if ($_POST['tingkat_pertemuan'] == $id) {
    		          $k = "selected='selected'";
                  }
    	       } echo "<option value='$id' $k>$isi</option>";
            } ?></select></td></tr>
    <tr><th>Penyelenggara</th><td><input type="text" maxlength="100" name="penyelenggara" value="<?=$_POST['penyelenggara'] ?? '(test) penyelenggara'?>" required="required" placeholder="harus diisi" style="width: 80ch"></td></tr>
    <tr><th>Tgl.pelaksanaan</th><td><input type="date" name="tanggal_pelaksanaan" value="<?=$_POST['tanggal_pelaksanaan'] ?? date("Y-m-d")?>" required="required"></td></tr>
    <tr><th>Bahasa</th><td><input type="text" maxlength="20" name="bahasa" value="<?=$_POST['bahasa'] ?? 'Indonesia'?>"></td></tr>
    <tr><th>SK</th><td><input type="text" maxlength="20" name="sk_penugasan" value="<?=$_POST['sk_penugasan'] ?? 'SK-1234/O/6.2022'?>"></td></tr>
	<tr><th>Tgl.SK</th><td><input type="date" name="tanggal_sk_penugasan" value="<?=$_POST['tanggal_sk_penugasan'] ?? null?>"></td></tr>
    
    <tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $id_sdm);?></td></tr>
    
    <tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simpan" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table>
    <input type="hidden" name="id_kegiatan" value="<?=$id_keg?>"></form>
    <?php 
} elseif (isset($_GET['e'])) {
    echo "<a href='?p=$param'>batal</a><br><br>";
    $id = $_GET['e'];
    $result_string = wsREF($param."/$id");
    $result = json_decode($result_string, true);

    $id_kategori_kegiatan       = $result['id_kategori_kegiatan'];
    $id_kategori_capaian_luaran = $result['id_kategori_capaian_luaran'];
    $id_penelitian_pengabdian   = $result['id_penelitian_pengabdian'];
    $id_kategori_pembicara      = $result['id_kategori_pembicara'];
    
    $judul_makalah          = $result['judul_makalah'];
    $nama_pertemuan         = $result['nama_pertemuan'];
    $id_tingkat_pertemuan   = $result['id_tingkat_pertemuan'];
    $penyelenggara          = $result['penyelenggara'];
    $tanggal_pelaksanaan    = $result['tanggal_pelaksanaan'];
    $bahasa                 = $result['bahasa'];
    
    $sk_penugasan           = $result['sk_penugasan'];
    $tanggal_sk_penugasan   = $result['tanggal_sk_penugasan'];

    $res_dokumen    = $result['dokumen'];
    foreach ($res_dokumen as $kolom=>$isi) {
        $dokumen[$kolom] = $isi;
    }
    
    include 'menu.php';
    $keg = menu_edit($param, $id_kategori_kegiatan);
    $id_keg = substr($keg, -6);
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>Kegiatan</th><td><?=$keg?></td><td></td></tr>
    
    <tr><th>Capaian</th><td><select name="capaian"><?php
        $qcap = mysqli_query($link, "select id,nama from sister where menu='kategori_capaian_luaran' order by id");
        if (mysqli_num_rows($qcap)>0) {
            while ($rcap=mysqli_fetch_assoc($qcap)) {
                $k = "";
                if (isset($_POST['capaian'])) {
                    if ($_POST['capaian'] == $rcap['id']) {
                        $k = "selected='selected'";
                    }
                } elseif ( $id_kategori_capaian_luaran==$rcap['id']) {
                    $k = "selected='selected'";
                } ?><option value="<?=$rcap['id']?>" <?=$k?>><?=$rcap['nama']?></option>
                <?php
            }  
        }?></select></td></tr>
    <tr><th>ID penelitian</th><td><input type="text" name="id_penelitian" value="<?=$_POST['id_penelitian'] ?? $id_penelitian_pengabdian?>" style="width: 80ch"></td></tr>
	<tr><th>Kategori pembicara</th><td><select name="id_kategori_pembicara"><?php 
            foreach ($kategori_pembicara as $id->$isi) {
    	       if (isset($_POST['id_kategori_pembicara'])) {
    		      if ($_POST['id_kategori_pembicara'] == $id) {
    		          $k = "selected='selected'";
                  }
    	       } elseif($id_kategori_pembicara == $id) {
    		      $k = "selected='selected'";
    		   } echo "<option value=$id $k>".$isi."</option>";
            } ?></select></td></tr>
    <tr><th>Judul</th><td><input type="text" name="judul_makalah" maxlength="500" value="<?=$_POST['judul_makalah'] ?? $judul_makalah?>" required="required" placeholder="harus diisi" style="width: 80ch"></td></tr>
    <tr><th>Nama pertemuan</th><td><input type="text" name="nama_pertemuan" maxlength="160" value="<?=$_POST['nama_pertemuan'] ?? $nama_pertemuan?>" style="width: 60ch" ></td></tr>
    <tr><th>Tingkat pertemuan</th><td><select name="tingkat_pertemuan"><?php
            foreach ($tingkat_pertemuan as $id->$isi) {
    	       if (isset($_POST['tingkat_pertemuan'])) {
    		      if ($_POST['tingkat_pertemuan'] == $id) {
    		          $k = "selected='selected'";
                  }
    	       } elseif($id_tingkat_pertemuan == $id) {
    		      $k = "selected='selected'";
    		   } echo "<option value=$id $k>".$isi."</option>";
            } ?></select></td></tr>
    <tr><th>Penyelenggara</th><td><input type="text" maxlength="100" name="penyelenggara" value="<?=$_POST['penyelenggara'] ?? $penyelenggara?>" required="required" placeholder="harus diisi" style="width: 80ch"></td></tr>
    <tr><th>Tgl.pelaksanaan</th><td><input type="date" name="tanggal_pelaksanaan" value="<?=$_POST['tanggal_pelaksanaan'] ?? $tanggal_pelaksanaan?>" required="required"></td></tr>
    <tr><th>Bahasa</th><td><input type="text" maxlength="20" name="bahasa" value="<?=$_POST['bahasa'] ?? $bahasa?>"></td></tr>
    <tr><th>SK</th><td><input type="text" maxlength="20" name="sk_penugasan" value="<?=$_POST['sk_penugasan'] ?? $sk_penugasan?>"></td></tr>
	<tr><th>Tgl.SK</th><td><input type="date" name="tanggal_sk_penugasan" value="<?=$_POST['tanggal_sk_penugasan'] ?? $tanggal_sk_penugasan?>" required="required"></td></tr>
    
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
    
    $result = do_get_orasi_ilmiah($id_sdm);
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
