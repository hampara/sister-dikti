<!--
// =====================
// bahan_ajar
// =====================
-->
<?php 
include 'ws.php';
$param = isset($_GET['p']) ? $_GET['p'] : "";
function do_get_bahan_ajar($id_sdm) {
	$post = ["id_sdm" => $id_sdm ];
	$result_string = wsGET("bahan_ajar", $post);
	return json_decode($result_string, true);
}
// ==============================
// mulai dr sini
// ==============================
$a_peran = array('A' => 'Penulis', 'B' => 'Editor', 'C' => 'Penerjemah', 'D' => 'Penemu/Inventor');
$a_jns_pen = array("Dosen","Mahasiswa","Profesional/Mitra");

if (isset($_POST['simpan']) || isset($_POST['simput'])) {
    $data = $dokumen = $penulis = array();

    // data
    $data['id_kategori_kegiatan']       = $_POST['id_kegiatan'];
    $data['id_kategori_capaian_luaran'] = (int) $_POST['capaian'];
    $data['id_penelitian_pengabdian']   = $_POST['id_penelitian'];
    $data['id_jenis_bahan_ajar']        = (int) $_POST['jenis_bahan_ajar'];
    $data['judul']                      = $_POST['judul'];
    $data['isbn']                       = $_POST['isbn'];
    $data['nama_penerbit']              = $_POST['penerbit'];
    $data['tanggal_terbit']             = $_POST['tgl_terbit'];
    $data['sk_penugasan']               = $_POST['sk'];
    $data['tanggal_sk_penugasan']       = $_POST['tgl_sk'];

    // lampiran dokumen
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
    
    // penulis
    $penulis['id_sdm']  = $_POST['id_penulis'];
    $penulis['nama']    = $_POST['nama_penulis'];
    $penulis['jenis']   = $_POST['jenis_penulis'];
    $penulis['no_urut'] = $_POST['no_urut'];
    $penulis['afiliasi']= $_POST['afiliasi'];
    $penulis['peran']   = $_POST['peran'];

    // uraikan @ penulis
    $pen_dosen = $pen_mhs = $pen_lain = array();
    for ($i=0; $i<count($penulis['no_urut']); $i++ ) {
        $jenis_penulis = $penulis['jenis'][$i];
        if ($jenis_penulis=="Dosen") {
            $pen_dosen['id_sdm'][]    = $penulis['id_sdm'][$i];
            $pen_dosen['no_urut'][]   = $penulis['no_urut'][$i];
            $pen_dosen['afiliasi'][]  = $penulis['afiliasi'][$i];
            $pen_dosen['peran'][]     = $penulis['peran'][$i];
        } elseif ($jenis_penulis=="Mahasiswa") {
            $pen_mhs['id_peserta_didik'][]  = $penulis['id_sdm'][$i];
            $pen_mhs['nama'][]              = $penulis['nama'][$i];
            $pen_mhs['no_induk'][]          = $penulis['nim'][$i];
            $pen_mhs['no_urut'][]           = $penulis['no_urut'][$i];
            $pen_mhs['afiliasi'][]          = $penulis['afiliasi'][$i];
            $pen_mhs['peran'][]             = $penulis['peran'][$i];
        } elseif ($jenis_penulis=="Profesional/Mitra") {
            $pen_lain['id_orang'][]   = $penulis['id_sdm'][$i];
            $pen_lain['no_urut'][]    = $penulis['no_urut'][$i];
            $pen_lain['afiliasi'][]   = $penulis['afiliasi'][$i];
            $pen_lain['peran'][]      = $penulis['peran'][$i];
        }
    }
    
    // serialize penulis
    $penulis_dosen = $penulis_mahasiswa = $penulis_lain = array();
    foreach ($pen_dosen['no_urut'] as $index => $no_urut) {
        $penulis_dosen[] = [
            'id_sdm'    => $pen_dosen['id_sdm'][$index] ?? null,
            'no_urut'   => $no_urut,
            'afiliasi'  => $pen_dosen['afiliasi'][$index] ?? null,
            'peran'     => $pen_dosen['peran'][$index] ?? null,
        ];
    } 
    foreach ($pen_mhs['id_sdm'] as $index => $nim) {
        $penulis_mahasiswa[] = [
            'nama'             => $pen_mhs['nama'][$index] ?? null,
            'id_peserta_didik' => $nim, //mysqli_fetch_row(mysqli_query($link, "select id_mahasiswa from mastermhs where nim='$nim'"))[0],
            'no_urut'          => $pen_mhs['no_urut'][$index] ?? null,
            'afiliasi'         => $pen_mhs['afiliasi'][$index] ?? null,
            'peran'            => $pen_mhs['peran'][$index] ?? null,
            'no_induk'         => $$nim,
        ];
    }
    foreach ($pen_lain['no_urut'] as $index => $no_urut) {
        $penulis_lain[] = [
            'id_orang'  => $pen_lain['id_sdm'][$index] ?? null,
            'no_urut'   => $no_urut, 
            'afiliasi'  => $pen_lain['afiliasi'][$index] ?? null,
            'peran'     => $pen_lain['peran'][$index] ?? null,
        ];
    }
    
    // POST / PUT data
    echo "<pre>";
    $resdok = json_decode(wsPOST("dokumen", $dokumen), true);
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
    
    $result = json_decode(wsPOST($param, $penulis_dosen), true);
    if ($resdok['kode']==200) {
        echo "penulis DOSEN disimpan :: ".$result['id']."<br>";
    } elseif ($resdok['kode']>202) {
        print_r(json_encode($penulis_dosen, JSON_PRETTY_PRINT));
        print_r(json_encode($result, JSON_PRETTY_PRINT ));
        echo "penulis Dosen GAGAL...<br>";
    }
    if (count($penulis_mahasiswa)>0) {
        $result = json_decode(wsPOST($param, $penulis_mahasiswa), true);
        if ($resdok['kode']==200) {
            echo "penulis Mhs disimpan :: ".$result['id'];
        } elseif ($resdok['kode']>202) {
            print_r(json_encode($penulis_mahasiswa, JSON_PRETTY_PRINT));
            print_r(json_encode($result, JSON_PRETTY_PRINT ));
            echo "penulis Mhs GAGAL...".$resdok."<br>";
        }
    }
    if (count($penulis_lain)>0) {
        $result = json_decode(wsPOST($param, $penulis_lain), true);
        if ($resdok['kode']==200) {
            echo "penulis lain disimpan :: ".$result['id']."<br>";
        } elseif ($resdok['kode']>202) {
            print_r(json_encode($penulis_lain, JSON_PRETTY_PRINT));
            print_r(json_encode($result, JSON_PRETTY_PRINT ));
            echo "penulis lain GAGAL...<br>";
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
    
    <tr><th>Capaian</th><td><select name="capaian"><?php
        $qcap = mysqli_query($link, "select id,nama from sister where menu='kategori_capaian_luaran' order by id");
        if (mysqli_num_rows($qcap)>0) {
            while ($rcap=mysqli_fetch_assoc($qcap)) {
                $k = "";
                if (isset($_POST['capaian'])) {
                    if ($_POST['capaian'] == $rcap['id']) {
                        $k = "selected='selected'";
                    }
                } ?><option value="<?=$rcap['id']?>" <?=$k?>> <?=$rcap['nama']?></option>
                <?php
            } 
        }?></select></td></tr>
    <tr><th>ID penelitian</th><td><input type="text" name="id_penelitian" value="<?=$_POST['id_penelitian'] ?? null?>" style="width: 80ch"></td></tr>
    <tr><th>Jenis&nbsp;bahan&nbsp;ajar</th><td><select name="jenis_bahan_ajar"><?php 
    		$qjd = mysqli_query($link, "select id,nama from sister where menu='$param' and id>0 order by id");
    		if (mysqli_num_rows($qjd)>0) {
    		    while ($rjd = mysqli_fetch_assoc($qjd)) {
    		        $k = "";
    		        if (isset($_POST['jenis_bahan_ajar'])) {
    		            if ($_POST['jenis_bahan_ajar'] == $rjd['id']) {
    		                $k = "selected='selected'";
    		            }
    		        } echo "<option value='".$rjd['id']."' $k>".$rjd['nama']."</option>\n";
    		    }
    		} ?></select></td></tr>
    <tr><th>Judul</th><td><input type="text" name="judul" maxlength="500" value="<?=$_POST['judul'] ?? "(TEST) Judul bahan ajar STEI Yogyakarta"?>" required="required" placeholder="harus diisi" style="width: 80ch" ></td></tr>
    <tr><th>ISBN</th><td><input type="text" name="isbn" maxlength="20" value="<?=$_POST['isbn'] ?? "978-623-200-381-1"?>" style="width: 40ch" ></td></tr>
    <tr><th>Penerbit</th><td><input type="text" name="penerbit" maxlength="100" value="<?=$_POST['penerbit'] ?? "(TEST) Penerbit Yogya Press"?>" required="required" placeholder="harus diisi" style="width: 80ch" ></td></tr>
    <tr><th>Tgl.terbit</th><td><input type="date" name="tgl_terbit" value="<?=$_POST['tgl_terbit'] ?? ''?>"></td></tr>
    <tr><th>SK&nbsp;Penugasan</th><td><input type="text" name="sk" maxlength="80" value="<?=$_POST['sk'] ?? 'TEST-01/02.XYZ'?>" style="width: 40ch" ></td></tr>
    <tr><th>Tgl.SK</th><td><input type="date" name="tgl_sk" value="<?=$_POST['tgl_sk'] ?? ''?>"></td></tr>

    <tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $id_sdm);?></td></tr>
    
    <tr><th>Penulis</th><td><div id="wadah">
    	<?php
    	$jumpenulis = 1;
	    for ($i=0; $i<$jumpenulis; $i++) {
	        ?><table><tr><th>Jenis</th><td><select name="jenis_penulis[]"><?php 
        	foreach ($a_jns_pen as$ijp) {
        	   $k="";
        	    if (isset($_POST['jenis_penulis[]'])) {
        	        if ($_POST['jenis_penulis[]'] == $ijp) {
        	            $k="selected='selected'";
        	        }
        	    } elseif($penulis[$i]['jenis']==$ijp) {
        	        $k="selected='selected'";
        	    } echo "<option value='$ijp' $k>$ijp</option>";
        	} ?></select></td></tr>
        	<tr><th>ID penulis</th><td><input type="text" name="id_penulis[]" value="<?=$_POST['id_penulis[]'] ?? $id_sdm?>" readonly="readonly" style="width: 66ch"></td></tr>
        	<tr><th>Nama</th><td><input type="text" name="nama_penulis[]" value="<?=$_POST['nama_penulis[]'] ?? $nm_dos?>" style="width: 66ch"></td></tr>
        	<tr><th>Urutan</th><td><input type="number" name="no_urut[]" min="1" max="99" value="<?=$_POST['no_urut[]'] ?? '1'?>"></td></tr>
        	<tr><th>Afiliasi</th><td><input type="text" name="afiliasi[]" value="<?=$_POST['afiliasi[]'] ?? 'STEI Yogyakarta (TEST)'?>" style="width: 66ch"></td></tr>
        	<tr><th>Peran</th><td><select name="peran[]"><?php
                    foreach ($a_peran as $id => $nama) {
                        $sel = "";
                        if (isset($_POST['peran[]'])) {
                            if ($_POST['peran[]']==$id) {
                                $sel = "selected='selected'";
                            }
                        } echo "<option value='$id' $sel>$nama</option>\n";
                    } ?></select></td></tr></table><hr><?php
	    } ?></div></td></tr>

    <tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simpan" value="Simpan" formaction="?p=<?=$param?>">
    	&nbsp; || &nbsp; <button onclick="additem(); return false">+ penulis</button></td></tr></table>
    <input type="hidden" name="id_kegiatan" value="<?=$id_keg?>"></form>
    <?php 
} elseif (isset($_GET['e'])) {
    echo "<a href='?p=$param'>batal</a><br><br>";
    $id = $_GET['e'];
    $result = json_decode(wsREF($param."/".$id), true);
    
    $id_kategori_kegiatan       = $result['id_kategori_kegiatan'];
    $id_kategori_capaian_luaran = $result['id_kategori_capaian_luaran'];
    $id_penelitian_pengabdian   = $result['id_penelitian_pengabdian'];
    $id_jenis_bahan_ajar        = $result['id_jenis_bahan_ajar'];
    
    $judul                  = $result['judul'];
    $isbn                   = $result['isbn'];
    $nama_penerbit          = $result['nama_penerbit'];
    $tanggal_terbit         = $result['tanggal_terbit'];
    $sk_penugasan           = $result['sk_penugasan'];
    $tanggal_sk_penugasan   = $result['tanggal_sk_penugasan'];
    
    $penulis = $dokumen = array();
    $res_penulis = $result['anggota'];
    foreach ($res_penulis as $kolom=>$isi) {
        $penulis[$kolom] = $isi;
    }
    $res_dokumen    = $result['dokumen'];
    foreach ($res_dokumen as $kolom=>$isi) {
        $dokumen[$kolom] = $isi;
    }
    
    $penulis = $result['penulis'];
    array_multisort(array_column($penulis, "urutan"), SORT_ASC, $penulis);

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
        }?></select><?=$id_kategori_capaian_luaran?> </td></tr>
    <tr><th>ID penelitian</th><td><input type="text" name="id_penelitian" value="<?=$_POST['id_penelitian'] ?? $id_penelitian_pengabdian?>" style="width: 80ch"></td></tr>
    <tr><th>Jenis&nbsp;bahan&nbsp;ajar</th><td><select name="jenis_bahan_ajar"><?php 
    		$qjd = mysqli_query($link, "select id,nama from sister where menu='$param' and id>0 order by id");
    		if (mysqli_num_rows($qjd)>0) {
    		    while ($rjd = mysqli_fetch_assoc($qjd)) {
    		        $k = "";
    		        if (isset($_POST['jenis_bahan_ajar'])) {
    		            if ($_POST['jenis_bahan_ajar'] == $rjd['id']) {
    		                $k = "selected='selected'";
    		            }
    		        } elseif($id_jenis_bahan_ajar == $rjd['id']) {
    		            $k = "selected='selected'";
    		        } echo "<option value='".$rjd['id']."' $k>".$rjd['nama']."</option>\n";
    		    }
    		} ?></select></td></tr>
    <tr><th>Judul</th><td><input type="text" name="judul" maxlength="500" value="<?=$_POST['judul'] ?? $judul?>" required="required" placeholder="harus diisi" style="width: 80ch" ></td></tr>
    <tr><th>ISBN</th><td><input type="text" name="isbn" maxlength="20" value="<?=$_POST['isbn'] ?? $isbn?>" style="width: 40ch" ></td></tr>
    <tr><th>Penerbit</th><td><input type="text" name="penerbit" maxlength="100" value="<?=$_POST['penerbit'] ?? $nama_penerbit?>" required="required" placeholder="harus diisi" style="width: 80ch" ></td></tr>
    <tr><th>Tgl.terbit</th><td><input type="date" name="tgl_terbit" value="<?=$_POST['tgl_terbit'] ?? $tanggal_terbit?>"></td></tr>
    <tr><th>SK&nbsp;Penugasan</th><td><input type="text" name="sk" maxlength="80" value="<?=$_POST['sk'] ?? $sk_penugasan?>" style="width: 40ch" ></td></tr>
    <tr><th>Tgl.SK</th><td><input type="date" name="tgl_sk" value="<?=$_POST['tgl_sk'] ?? $tanggal_sk_penugasan?>"></td></tr>

    <tr><td></td><td><hr></td></tr>
    <tr><th>Dokumen</th><td><?=berkas_dok($link, $dokumen);?></td></tr>
    
    <tr><th>Penulis</th><td><div id="wadah">
    	<?php
    	$jumpenulis = count($penulis);
    	if ($jumpenulis>0) {
    	    for ($i=0; $i<count($penulis); $i++) {
    	        ?><table>
            	<tr><th>Jenis</th><td><select name="jenis_penulis[]"><?php 
            	foreach ($a_jns_pen as $ijp) {
            	   $k="";
            	    if (isset($_POST['jenis_penulis[]'])) {
            	        if ($_POST['jenis_penulis[]'] == $ijp) {
            	            $k="selected='selected'";
            	        }
            	    } elseif($penulis[$i]['jenis']==$ijp) {
            	        $k="selected='selected'";
            	    } echo "<option value='$ijp' $k>$ijp</option>";
            	} ?></select></td></tr>
            	<tr><th>ID penulis</th><td><input type="text" name="id_penulis[]" value="<?=$_POST['id_penulis[]'] ?? $penulis[$i]['id_sdm']?>" readonly="readonly" style="width: 66ch"></td></tr>
            	<tr><th>Nama</th><td><input type="text" name="nama_penulis[]" value="<?=$_POST['nama_penulis[]'] ?? $penulis[$i]['nama']?>" style="width: 66ch"></td></tr>
            	<tr><th>Urutan</th><td><input type="number" name="no_urut[]" min="1" max="99" value="<?=$_POST['no_urut[]'] ?? $penulis[$i]['urutan']?>"></td></tr>
            	<tr><th>Afiliasi</th><td><input type="text" name="afiliasi[]" value="<?=$_POST['afiliasi[]'] ?? $penulis[$i]['afiliasi']?>" style="width: 66ch"></td></tr>
	        	<tr><th>Peran</th><td><select name="peran[]"><?php
                    foreach ($a_peran as $id => $nama) {
                        $sel = "";
                        if (isset($_POST['peran[]'])) {
                            if ($_POST['peran[]']==$id) {
                                $sel = "selected='selected'";
                            }
                        } elseif ($penulis[$i]['peran']==$id)  {
                            $sel = "selected='selected'";
                        } echo "<option value='$id' $sel>$nama</option>\n";
                    } ?></select></td></tr></table><hr><?php
    	    }
    	} ?></div></td></tr>

    <tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simput" value="Simpan" formaction="?p=<?=$param?>">
    	&nbsp; || &nbsp; <button onclick="additem(); return false">+ penulis</button></td></tr></table>
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
    $result = do_get_bahan_ajar($id_sdm);
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
} ?><br>
<script>
    var i = <?=$jumpenulis?>;
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
        var jenis_penulis_select = document.createElement('select');
        jenis_penulis_select.setAttribute('name', 'jenis_penulis[' + i + ']');
        
        const jp = <?=json_encode($a_jns_pen);?>;
        jp.forEach((item) => {
        	const option = document.createElement("option");
        	option.value = item;
        	option.textContent = item;
        	jenis_penulis_select.appendChild(option);
		});
        
        kolom2.appendChild(jenis_penulis_select);
        baris1.appendChild(kolom2);
		tabel.appendChild(baris1);
		
        // baris 2
        var baris2 = document.createElement('tr');
        var kolom1 = document.createElement("th");
		kolom1.textContent = "Nama";
		baris2.appendChild(kolom1);
		
		var kolom2 = document.createElement("td");
        var nama_penulis_input = document.createElement('input');
        nama_penulis_input.setAttribute('name', 'nama_penulis[' + i + ']');
        nama_penulis_input.style.width = "66ch";
        nama_penulis_input.value = "";
        kolom2.appendChild(nama_penulis_input);
        baris2.appendChild(kolom2);
		tabel.appendChild(baris2);
		
        // baris 3
        var baris3 = document.createElement('tr');
        var kolom1 = document.createElement("th");
		kolom1.textContent = "ID penulis";
		baris3.appendChild(kolom1);
		
		var kolom2 = document.createElement("td");
        var id_penulis_input = document.createElement('input');
        id_penulis_input.setAttribute('name', 'id_sdm[' + i + ']');
        id_penulis_input.style.width = "66ch";
        kolom2.appendChild(id_penulis_input);
        baris3.appendChild(kolom2);
        tabel.appendChild(baris3);
		
        // baris 4
        var baris4 = document.createElement('tr');
        var kolom1 = document.createElement("th");
		kolom1.textContent = "Urutan";
		baris4.appendChild(kolom1);
		
		var kolom2 = document.createElement("td");
        var urutan_input = document.createElement('input');
        urutan_input.setAttribute('name', 'no_urut[' + i + ']');
        urutan_input.type = "number";
        urutan_input.min = "1";
        urutan_input.max = "99";
        urutan_input.value = i+1;
        kolom2.appendChild(urutan_input);
        baris4.appendChild(kolom2);
		tabel.appendChild(baris4);
		
        // baris 5
        var baris5 = document.createElement('tr');
        var kolom1 = document.createElement("th");
		kolom1.textContent = "Afiliasi";
		baris5.appendChild(kolom1);
		
		var kolom2 = document.createElement("td");
        var afiliasi_input = document.createElement('input');
        afiliasi_input.setAttribute('name', 'afiliasi[' + i + ']');
        afiliasi_input.style.width = "66ch";
        kolom2.appendChild(afiliasi_input);
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

		const phpData = <?=json_encode($a_peran);?>;
        
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
<?php
