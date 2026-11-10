<!--
// =====================
// pengabdian
// input mahasiswa masih gagal
// =====================
-->
<?php 
include 'ws.php';
$param = isset($_GET['p']) ? $_GET['p'] : "";
function do_get_pengabdian($id_sdm) {
	$post = ["id_sdm" => $id_sdm ];
	$result_string = wsGET($param, $post);
	return json_decode($result_string, true);
}
// ==============================
// mulai dr sini
// ==============================
$a_peran = array('K' => 'Ketua', 'A' => 'Anggota');
$a_aktif = array(true => 'Aktif', false => 'Tidak aktif');
$jns_agt = array("Dosen","Mahasiswa","Profesional/Mitra");

if (isset($_POST['simpan']) || isset($_POST['simput'])) {
    $data = $dokumen = $anggota = array();
    
    // data
    $data['id_kategori_kegiatan']   = (int) $_POST['id_kegiatan'];
    $data['judul']                  = $_POST['judul'];
    $data['id_afiliasi']            = $_POST['id_afiliasi'];
    $data['id_kelompok_bidang']     = (int) $_POST['id_kelompok_bidang'];
    $data['id_litabmas_sebelumnya'] = $_POST['id_penelitian'];
    
    $data['id_jenis_skim']          = $_POST['skema'];
    $data['lokasi']                 = $_POST['lokasi'];
    
    $data['tahun_usulan']           = (int) $_POST['tahun_usulan'];
    $data['tahun_kegiatan']         = (int) $_POST['tahun_kegiatan'];
    $data['tahun_pelaksanaan']      = (int) $_POST['tahun_pelaksanaan'];
    
    $data['lama_kegiatan']          = (int) $_POST['lama_kegiatan'];
    $data['tahun_pelaksanaan_ke']   = (int) $_POST['tahun_pelaksanaan_ke'];
    $data['dana_dikti']             = (int) $_POST['dana_dikti'];
    $data['dana_perguruan_tinggi']  = (int) $_POST['dana_perguruan_tinggi'];
    $data['dana_institusi_lain']    = (int) $_POST['dana_institusi_lain'];
    
    $data['in_kind']                = $_POST['in_kind'];
    $data['sk_penugasan']           = $_POST['sk_penugasan'];
    $data['tanggal_sk_penugasan']   = $_POST['tanggal_sk_penugasan'];
    $data['mitra_litabmas']         = $_POST['mitra_litabmas'];
    
    // lampiran dokumen
    $doks['file']            = $_FILES['file'];
    $doks['id_dok']          = $_POST['id_dok'];     // post
    $doks['id_jenis_dokumen']= (int) $_POST['jenis_dok'];
    $doks['nama']            = $_POST['nama_dok'];
    $doks['tautan']          = $_POST['tautan_dok'];
    $doks['keterangan']      = $_POST['keterangan_dok'];
    
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
    
    // anggota
    $anggota['id_sdm']  = $_POST['id_anggota'];
    $anggota['nama']    = $_POST['nama_anggota'];
    $anggota['jenis']   = $_POST['jenis_anggota'];
    $anggota['aktif']   = $_POST['aktif'];
    $anggota['peran']   = $_POST['peran'];
    
    // uraikan @ anggota
    $pen_dosen = $pen_mhs = $pen_lain = array();
    for ($i=0; $i<count($anggota); $i++ ) {
        $jenis_anggota = $anggota['jenis'][$i];
        if ($jenis_anggota=="Dosen") {
            $pen_dosen['id_sdm'][] = $anggota['id_sdm'][$i];
            $pen_dosen['aktif'][]  = $anggota['aktif'][$i];
            $pen_dosen['peran'][]  = $anggota['peran'][$i];
        } elseif ($jenis_anggota=="Mahasiswa") {
            $pen_mhs['id_peserta_didik'][]  = $anggota['id_sdm'][$i];
            $pen_mhs['nama'][]              = $anggota['nama'][$i];
            $pen_mhs['no_induk'][]          = $anggota['nim'][$i];
            $pen_mhs['aktif'][]             = $anggota['aktif'][$i];
            $pen_mhs['peran'][]             = $anggota['peran'][$i];
        } elseif ($jenis_anggota=="Profesional/Mitra") {
            $pen_lain['id_orang'][] = $anggota['id_sdm'][$i];
            $pen_lain['aktif'][]    = $anggota['aktif'][$i];
            $pen_lain['peran'][]    = $anggota['peran'][$i];
        }
    }
    
    // serialize anggota
    $anggota_dosen = $anggota_mahasiswa = $anggota_lain = array();
    foreach ($pen_dosen['id_sdm'] as $index => $id_sdm) {
        $anggota_dosen[] = [
            'id_sdm'    => $id_sdm,
            'aktif'     => $pen_dosen['aktif'][$index] ?? null,
            'peran'     => $pen_dosen['peran'][$index] ?? null,
        ];
    }
    foreach ($pen_mhs['id_sdm'] as $index => $id_sdm) {
        $nim = $pen_mhs['id_sdm'][$index];
        $anggota_mahasiswa[] = [
            'nama'             => $pen_mhs['nama'][$index] ?? null,
            'id_peserta_didik' => $nim, // mysqli_fetch_row(mysqli_query($link, "select id_mahasiswa from mastermhs where nim='$nim'"))[0],
            'aktif'            => $pen_mhs['aktif'][$index] ?? null,
            'peran'            => $pen_mhs['peran'][$index] ?? null,
            'no_induk'         => $nim,
        ];
    }
    foreach ($pen_lain['id_orang'] as $index => $id_orang) {
        $anggota_lain[] = [
            'id_orang'  => $id_orang,
            'aktif'     => $pen_lain['aktif'][$index] ?? null,
            'peran'     => $pen_lain['peran'][$index] ?? null,
        ];
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
    }
    
    $result = json_decode(wsPOST($param, $anggota_dosen), true);
    if ($resdok['kode']==200) {
        echo "anggota DOSEN disimpan :: ".$result['id']."<br>";
    } elseif ($resdok['kode']>202) {
        print_r(json_encode($anggota_dosen, JSON_PRETTY_PRINT));
        print_r(json_encode($result, JSON_PRETTY_PRINT ));
        echo "anggota Dosen GAGAL...<br>";
    }
    if (count($anggota_mahasiswa)>0) {
        echo "1<br>";
        $result = json_decode(wsPOST($param, $anggota_mahasiswa), true);
        if ($resdok['kode']==200) {
            echo "anggota Mhs disimpan :: ".$result['id'];
        } elseif ($resdok['kode']>202) {
            print_r(json_encode($anggota_mahasiswa, JSON_PRETTY_PRINT));
            print_r(json_encode($result, JSON_PRETTY_PRINT ));
            echo "anggota Mhs GAGAL...".$resdok."<br>";
        }
    }
    if (count($anggota_lain)>0) {
        $result = json_decode(wsPOST($param, $anggota_lain), true);
        if ($resdok['kode']==200) {
            echo "anggota lain disimpan :: ".$result['id']."<br>";
        } elseif ($resdok['kode']>202) {
            print_r(json_encode($anggota_lain, JSON_PRETTY_PRINT));
            print_r(json_encode($result, JSON_PRETTY_PRINT ));
            echo "anggota lain GAGAL...<br>";
        }
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
    <tr><th>Judul</th><td><input type="text" name="judul" maxlength="500" value="<?=$_POST['judul'] ?? '(test) Enhancing the Paddy Farmer Income Sustainability with Crop Insurance Policy'?>" required="required" placeholder="harus diisi" style="width: 80ch" ></td></tr>
    <tr><th>Afiliasi</th><td><input type="text" name="afiliasi" maxlength="100" value="<?=$_POST['afiliasi'] ?? $id_pt?>" required="required" placeholder="harus diisi" style="width: 60ch" ></td></tr>

    <tr><th>Kelompok bidang</th><td><select name="id_kelompok_bidang"><?php 
    		$qjd = mysqli_query($link, "select id,nama from sister where menu='id_kelompok_bidang' order by id");
    		if (mysqli_num_rows($qjd)>0) {
    		    while ($rjd = mysqli_fetch_assoc($qjd)) {
    		        $k = "";
    		        if (isset($_POST['id_kelompok_bidang'])) {
    		            if ($_POST['id_kelompok_bidang'] == $rjd['id']) {
    		                $k = "selected='selected'";
    		            }
    		        } echo "<option value='".$rjd['id']."' $k>".$rjd['nama']."</option>\n";
    		    }
    		} ?></select></td></tr>
    <tr><td>ID&nbsp;litabmas&nbsp;sebelumnya</td><td><input type="text" name="id_penelitian" value="<?=$_POST['id_penelitian'] ?? 'id_litabmas_sebelumnya'?>" style="width: 80ch"></td></tr>
    <tr><th>Skema</th><td><input type="text" name="skema" maxlength="20" value="<?=$_POST['skema'] ?? ''?>" style="width: 40ch" ></td></tr>
    <tr><th>Lokasi</th><td><input type="text" name="lokasi" maxlength="80" value="<?=$_POST['lokasi'] ?? 'Sumatera Utara, Pulau Jawa, dan Nusa Tenggara Barat'?>" style="width: 60ch" ></td></tr>
    
    <tr><th>Thn.usulan</th><td><input type="number" name="tahun_usulan" value="<?=$_POST['tahun_usulan'] ?? 2018?>" required="required"></td></tr>
    <tr><th>Thn.kegiatan</th><td><input type="number" name="tahun_kegiatan" value="<?=$_POST['tahun_kegiatan'] ?? 2019?>" required="required"></td></tr>
    <tr><th>Thn.pelaksanaan</th><td><input type="number" name="tahun_pelaksanaan" value="<?=$_POST['tahun_pelaksanaan'] ?? 2019?>" required="required"></td></tr>
    <tr><th>Lama kegiatan</th><td><input type="number" name="lama_kegiatan" value="<?=$_POST['lama_kegiatan'] ?? 3?>" required="required"> tahun</td></tr>
    <tr><th>Thn.pelaksanaan&nbsp;ke</th><td><input type="number" name="tahun_pelaksanaan_ke" value="<?=$_POST['tahun_pelaksanaan_ke'] ?? 2?>" required="required"></td></tr>
    
    <tr><th>Dana dikti</th><td><input type="number" min='0' name="dana_dikti" value="<?=$_POST['dana_dikti'] ?? 0?>" required="required"></td></tr>
    <tr><th>Dana PT</th><td><input type="number" min='0' name="dana_perguruan_tinggi" value="<?=$_POST['dana_perguruan_tinggi'] ?? 0?>" required="required"></td></tr>
    <tr><th>Dana lain</th><td><input type="number" min='0' name="dana_institusi_lain" value="<?=$_POST['dana_institusi_lain'] ?? 123456?>" required="required"></td></tr>
    
    <tr><th>in kind</th><td><input type="text" name="in_kind" maxlength="80" value="<?=$_POST['in_kind'] ?? $in_kind?>" style="width: 40ch" ></td></tr>
    <tr><th>SK&nbsp;Penugasan</th><td><input type="text" name="sk_penugasan" maxlength="80" value="<?=$_POST['sk_penugasan'] ?? 'sk-1234/SK/2019'?>" style="width: 40ch" ></td></tr>
    <tr><th>Tgl.SK</th><td><input type="date" name="tanggal_sk_penugasan" value="<?=$_POST['tanggal_sk_penugasan'] ?? '2019-01-01'?>"></td></tr>
    <tr><th>Mitra litabmas</th><td><input type="text" name="mitra_litabmas" maxlength="80" value="<?=$_POST['mitra_litabmas'] ?? []?>" style="width: 40ch" ></td></tr>

    <tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $id_sdm);?></td></tr>
    
    <tr><th>Anggota</th><td><div id="wadah">
    	<?php
    	$jumanggota = 1;
    	for ($i=0; $i<$jumanggota; $i++) {
	        ?><table>
        	<tr><th>Jenis</th><td><select name="jenis_anggota[]"><?php 
        	foreach ($jns_agt as$ijp) {
        	   $k="";
        	    if (isset($_POST['jenis_anggota[]'])) {
        	        if ($_POST['jenis_anggota[]'] == $ijp) {
        	            $k="selected='selected'";
        	        }
        	    } echo "<option value='$ijp' $k>$ijp</option>";
        	} ?></select></td></tr>
        	<tr><th>Nama</th><td><input type="text" name="nama_anggota[]" value="<?=$_POST['nama_anggota[]'] ?? $nm_sdm?>" style="width: 66ch"></td></tr>
        	<tr><th>ID anggota</th><td><input type="text" name="id_anggota[]" value="<?=$_POST['id_anggota[]'] ?? $id_sdm?>" readonly="readonly" style="width: 66ch"></td></tr>
        	<tr><th>Aktif</th><td><select name="aktif[]"><?php
        			foreach ($a_aktif as $id => $isi) {
                        $sel = "";
                        if (isset($_POST['aktif[]'])) {
                            if ($_POST['aktif[]']==$id) {
                                $sel = "selected='selected'";
                            }
                        } echo "<option value='$id' $sel>$isi</option>\n";
        			} ?></select></td></tr>
        	<tr><th>Peran</th><td><select name="peran[]"><?php
                    foreach ($a_peran as $id => $isi) {
                        $sel = "";
                        if (isset($_POST['peran[]'])) {
                            if ($_POST['peran[]']==$id) {
                                $sel = "selected='selected'";
                            }
                        } echo "<option value='$id' $sel>$isi</option>\n";
                    }?></select></td></tr></table><hr><?php
	    } ?></div></td></tr>

    <tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simpan" value="Simpan" formaction="?p=<?=$param?>">
    	&nbsp; || &nbsp; <button onclick="additem(); return false">+ anggota</button></td></tr></table>
    <input type="hidden" name="id_kegiatan" value="<?=$id_keg?>"></form>
    <?php 
} elseif (isset($_GET['e'])) {
    echo "<a href='?p=$param'>batal</a><br><br>";
    $id = $_GET['e'];
    $result_string = wsREF($param."/$id");
    $result = json_decode($result_string, true);

    $id_kategori_kegiatan   = $result['id_kategori_kegiatan'];
    $judul                  = $result['judul'];
    $id_afiliasi            = $result['id_afiliasi'];
    $id_kelompok_bidang     = $result['id_kelompok_bidang'];
    $id_litabmas_sebelumnya = $result['id_litabmas_sebelumnya'];
    $id_jenis_skim          = $result['id_jenis_skim'];
    
    $lokasi                 = $result['lokasi'];
    $tahun_usulan           = $result['tahun_usulan'];
    $tahun_kegiatan         = $result['tahun_kegiatan'];
    $tahun_pelaksanaan      = $result['tahun_pelaksanaan'];
    
    $lama_kegiatan          = $result['lama_kegiatan'];
    $tahun_pelaksanaan_ke   = $result['tahun_pelaksanaan_ke'];
    $dana_dikti             = $result['dana_dikti'];
    $dana_perguruan_tinggi  = $result['dana_perguruan_tinggi'];
    $dana_institusi_lain    = $result['dana_institusi_lain'];
    $in_kind                = $result['in_kind'];
    $sk_penugasan           = $result['sk_penugasan'];
    $tanggal_sk_penugasan   = $result['tanggal_sk_penugasan'];
    $mitra_litabmas         = $result['mitra_litabmas'];
    
    $anggota = $dokumen = array();
    $res_anggota = $result['anggota'];
    foreach ($res_anggota as $kolom=>$isi) {
        $anggota[$kolom] = $isi;
    }
    $res_dokumen    = $result['dokumen'];
    foreach ($res_dokumen as $kolom=>$isi) {
        $dokumen[$kolom] = $isi;
    }
    
    $anggota = $result['anggota'];
    
    include 'menu.php';
    $keg = menu_edit($param, $id_kategori_kegiatan);
    $id_keg = substr($keg, -6);
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>Kegiatan</th><td><?=$keg?></td><td></td></tr>
    <tr><th>Judul</th><td><input type="text" name="judul" maxlength="500" value="<?=$_POST['judul'] ?? $judul?>" required="required" placeholder="harus diisi" style="width: 80ch" ></td></tr>
    <tr><th>Afiliasi</th><td><input type="text" name="id_afiliasi" maxlength="100" value="<?=$_POST['afiliasi'] ?? $id_afiliasi?>" required="required" placeholder="harus diisi" style="width: 60ch" ></td></tr>

    <tr><th>Kelompok bidang</th><td><select name="id_kelompok_bidang"><?php 
    		$qjd = mysqli_query($link, "select id,nama from sister where menu='kelompok_bidang' order by id");
    		if (mysqli_num_rows($qjd)>0) {
    		    while ($rjd = mysqli_fetch_assoc($qjd)) {
    		        $k = "";
    		        if (isset($_POST['id_kelompok_bidang'])) {
    		            if ($_POST['id_kelompok_bidang'] == $rjd['id']) {
    		                $k = "selected='selected'";
    		            }
    		        } elseif($id_kelompok_bidang == $rjd['id']) {
    		            $k = "selected='selected'";
    		        } echo "<option value='".$rjd['id']."' $k>".$rjd['nama']."</option>";
    		    }
    		} ?></select></td></tr>
    <tr><td>ID&nbsp;litabmas&nbsp;sebelumnya</td><td><input type="text" name="id_penelitian" value="<?=$_POST['id_penelitian'] ?? $id_litabmas_sebelumnya?>" style="width: 80ch"></td></tr>
    <tr><th>Skema</th><td><input type="text" name="skema" maxlength="20" value="<?=$_POST['skema'] ?? $skema?>" style="width: 40ch" ></td></tr>
    <tr><th>Lokasi</th><td><input type="text" name="lokasi" maxlength="80" value="<?=$_POST['lokasi'] ?? $lokasi?>" style="width: 60ch" ></td></tr>
    
    <tr><th>Thn.usulan</th><td><input type="number" name="tahun_usulan" value="<?=$_POST['tahun_usulan'] ?? $tahun_usulan?>" required="required"></td></tr>
    <tr><th>Thn.kegiatan</th><td><input type="number" name="tahun_kegiatan" value="<?=$_POST['tahun_kegiatan'] ?? $tahun_kegiatan?>" required="required"></td></tr>
    <tr><th>Thn.pelaksanaan</th><td><input type="number" name="tahun_pelaksanaan" value="<?=$_POST['tahun_pelaksanaan'] ?? $tahun_pelaksanaan?>" required="required"></td></tr>
    <tr><th>Lama kegiatan</th><td><input type="number" name="lama_kegiatan" value="<?=$_POST['lama_kegiatan'] ?? $lama_kegiatan?>" required="required"> tahun</td></tr>
    <tr><th>Thn.pelaksanaan&nbsp;ke</th><td><input type="number" name="tahun_pelaksanaan_ke" value="<?=$_POST['tahun_pelaksanaan_ke'] ?? $tahun_pelaksanaan_ke?>" required="required"></td></tr>
    
    <tr><th>Dana dikti</th><td><input type="number" min='0' name="dana_dikti" value="<?=$_POST['dana_dikti'] ?? $dana_dikti?>" required="required"></td></tr>
    <tr><th>Dana PT</th><td><input type="number" min='0' name="dana_perguruan_tinggi" value="<?=$_POST['dana_perguruan_tinggi'] ?? $dana_perguruan_tinggi?>" required="required"></td></tr>
    <tr><th>Dana lain</th><td><input type="number" min='0' name="dana_institusi_lain" value="<?=$_POST['dana_institusi_lain'] ?? $dana_institusi_lain?>" required="required"></td></tr>
    
    <tr><th>in kind</th><td><input type="text" name="in_kind" maxlength="80" value="<?=$_POST['in_kind'] ?? $in_kind?>" style="width: 40ch" ></td></tr>
    <tr><th>SK&nbsp;Penugasan</th><td><input type="text" name="sk_penugasan" maxlength="80" value="<?=$_POST['sk_penugasan'] ?? $sk_penugasan?>" style="width: 40ch" ></td></tr>
    <tr><th>Tgl.SK</th><td><input type="date" name="tanggal_sk_penugasan" value="<?=$_POST['tanggal_sk_penugasan'] ?? $tanggal_sk_penugasan?>"></td></tr>
    <tr><th>Mitra litabmas</th><td><input type="text" name="mitra_litabmas" maxlength="80" value="<?=$_POST['mitra_litabmas'] ?? $mitra_litabmas?>" style="width: 40ch" ></td></tr>

    <tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $dokumen);?></td></tr>
    
    <tr><th>Anggota</th><td><div id="wadah"><?php
    	$jumanggota = count($anggota);
    	if ($jumanggota>0) {
    	    for ($i=0; $i<count($anggota); $i++) {
    	        ?><table>
            	<tr><th>Jenis</th><td><select name="jenis_anggota[]"><?php 
            	foreach ($jns_agt as$ijp) {
            	   $k="";
            	    if (isset($_POST['jenis_anggota[]'])) {
            	        if ($_POST['jenis_anggota[]'] == $ijp) {
            	            $k="selected='selected'";
            	        }
            	    } elseif($anggota[$i]['jenis']==$ijp) {
            	        $k="selected='selected'";
            	    } echo "<option value='$ijp' $k>$ijp</option>";
            	} ?></select></td></tr>
            	<tr><th>ID anggota</th><td><input type="text" name="id_anggota[]" value="<?=$_POST['id_anggota[]'] ?? $anggota[$i]['id_sdm']?>" readonly="readonly" style="width: 66ch"></td></tr>
            	<tr><th>Nama</th><td><input type="text" name="nama_anggota[]" value="<?=$_POST['nama_anggota[]'] ?? $anggota[$i]['nama']?>" style="width: 66ch"></td></tr>
            	<tr><th>Aktif</th><td><select name="aktif[]"><?php
            			foreach ($a_aktif as $id => $isi) {
                            $sel = "";
                            if (isset($_POST['aktif[]'])) {
                                if ($_POST['aktif[]']==$id) {
                                    $sel = "selected='selected'";
                                }
                            } elseif ($anggota[$i]['aktif'] == $id) {
                                $sel = "selected='selected'";
                            } echo "<option value='$id' $sel>$isi</option>\n";
            			} ?></select></td></tr>
            	<tr><th>Peran</th><td><select name="peran[]"><?php
                        foreach ($a_peran as $id => $isi) {
                            $sel = "";
                            if (isset($_POST['peran[]'])) {
                                if ($_POST['peran[]']==$id) {
                                    $sel = "selected='selected'";
                                }
                            } elseif ($anggota[$i]['peran'] == $id) {
                                $sel = "selected='selected'";
                            } echo "<option value='$id' $sel>$isi</option>\n";
                        } ?></select></td></tr></table><hr><?php
    	    }
    	} ?></div></td></tr>

    <tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simput" value="Simpan" formaction="?p=<?=$param?>">
    	&nbsp; || &nbsp; <button onclick="additem(); return false">+ anggota</button></td></tr></table>
    <input type="hidden" name="id_kegiatan" value="<?=$id_keg?>"></form>
    <?php 
} elseif (isset($_GET['l'])) {
    $id = $_GET['l'];
    $result_string = wsREF($param."/".$id);
    $detil = json_decode($result_string, true);
    echo "<a href='?p=$param'>kembali</a> | <a href='?p=$param&t'>+ data</a><br><br>Detil <b>$param</b><pre>";
    print_r(json_encode($detil, JSON_PRETTY_PRINT));
    echo "</pre>";
} elseif ($param!="") {
    $result = do_get_pengabdian($id_sdm);
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
                        $kol = "<a href='?p=$param&l=$isi'>$kol</a>";   // link detil
                        $isi = "<a href='?p=$param&e=$isi'>$isi</a>";   // link edit
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
        echo "blom ada data <b>$param</b><br><br>atau <b style='color: red'>cek TOKEN</b>";
    }
}
?>
<br>
<script>
    var i = <?=$jumanggota?>;
    function additem() {
        var itemlist = document.getElementById('wadah');
        
		// membuat element
        var tabel = document.createElement('table');
        
        // baris 1
        var baris1 = document.createElement('tr');
        var kolom1 = document.createElement("th");
		kolom1.textContent = "Jenis";
		baris1.appendChild(kolom1);
		
		var kolom2 = document.createElement("td");
        var jenis_anggota_select = document.createElement('select');
        jenis_anggota_select.setAttribute('name', 'jenis_anggota[' + i + ']');
        
        const jp = <?=json_encode($jns_agt);?>;
        jp.forEach((item) => {
        	const option = document.createElement("option");
        	option.value = item;
        	option.textContent = item;
        	jenis_anggota_select.appendChild(option);
		});
        
        kolom2.appendChild(jenis_anggota_select);
        baris1.appendChild(kolom2);
		tabel.appendChild(baris1);
		
        // baris 2
        var baris2 = document.createElement('tr');
        var kolom1 = document.createElement("th");
		kolom1.textContent = "Nama";
		baris2.appendChild(kolom1);
		
		var kolom2 = document.createElement("td");
        var nama_anggota_input = document.createElement('input');
        nama_anggota_input.setAttribute('name', 'nama_anggota[' + i + ']');
        nama_anggota_input.style.width = "66ch";
        nama_anggota_input.value = "";
        kolom2.appendChild(nama_anggota_input);
        baris2.appendChild(kolom2);
		tabel.appendChild(baris2);
		
        // baris 3
        var baris3 = document.createElement('tr');
        var kolom1 = document.createElement("th");
		kolom1.textContent = "ID anggota";
		baris3.appendChild(kolom1);
		
		var kolom2 = document.createElement("td");
        var id_anggota_input = document.createElement('input');
        id_anggota_input.setAttribute('name', 'id_anggota[' + i + ']');
        id_anggota_input.style.width = "66ch";
        kolom2.appendChild(id_anggota_input);
        baris3.appendChild(kolom2);
        tabel.appendChild(baris3);
		
        // baris 4
        // no_urut
        
        // baris 5
        var baris5 = document.createElement('tr');
        var kolom1 = document.createElement("th");
		kolom1.textContent = "Aktif";
		baris5.appendChild(kolom1);
		
		var kolom2 = document.createElement("td");
        var aktif_input = document.createElement('select');
        aktif_input.setAttribute('name', 'aktif[' + i + ']');
        
        const phpAktif = <?php echo json_encode($a_aktif); ?>;
        for (const [key, value] of Object.entries(phpAktif)) {
            const option = document.option || document.createElement("option");
            const opt = document.createElement("option");
            opt.value = key;
            opt.textContent = value;
            aktif_input.appendChild(opt);
        }
        
        kolom2.appendChild(aktif_input);
        baris5.appendChild(kolom2);
		tabel.appendChild(baris5);
		
        // baris 6
        var baris6 = document.createElement('tr');
        var kolom1 = document.createElement("th");
		kolom1.textContent = "Peran";
		baris6.appendChild(kolom1);
		
		var kolom2 = document.createElement("td");
        var peran_input = document.createElement('select');
        peran_input.setAttribute('name', 'peran[' + i + ']');
        
        const phpData = <?=json_encode($a_peran); ?>;
        
        for (const [key, value] of Object.entries(phpData)) {
            const option = document.option || document.createElement("option");
            const opt = document.createElement("option");
            opt.value = key;
            opt.textContent = value;
            peran_input.appendChild(opt);
        }
        kolom2.appendChild(peran_input);
        baris6.appendChild(kolom2);
		
		var aksi = document.createElement('td');
        baris6.appendChild(aksi);
		tabel.appendChild(baris6);
		
        // baris spasi
        var baris0 = document.createElement('tr');
        var kolom0 = document.createElement("td");
		kolom0.setAttribute('colspan', '3');
		kolom0.innerHTML = "<hr>";
		baris0.appendChild(kolom0);
		tabel.appendChild(baris0);

		itemlist.appendChild(tabel);

        var hapus = document.createElement('span');
		aksi.appendChild(hapus);
		
        hapus.innerHTML = '<button><b>-</b></button>';
		hapus.onclick = function () {
            tabel.parentNode.removeChild(tabel);
        };

        i++;
    }
    </script>
