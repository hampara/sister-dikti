<!--
// =====================
// kesejahteraan
// =====================
-->
<?php 
include 'ws.php';
$param = isset($_GET['p']) ? $_GET['p'] : "";
function do_kesejahteraan($id_sdm) {
	$post = ["id_sdm" => $id_sdm ];
	$result_string = wsGET("kesejahteraan", $post);
	return json_decode($result_string, true);
}

if (isset($_POST['simpan']) || isset($_POST['simput'])) {
    $data['id_sdm']                 = $id_sdm;
    $data['id_jenis_kesejahteraan'] = (int) $_POST['id_jenis_kesejahteraan'] ?? 1;
    $data['nama']                   = $_POST['nama'];
    $data['penyelenggara']          = $_POST['penyelenggara'];
    $data['tahun_mulai']            = $_POST['tahun_mulai'];
    $data['tahun_selesai']          = $_POST['tahun_selesai'];
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
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>SDM</th><td><?=$_SESSION['login_dos']?></td></tr>
    <tr><th>Jenis kesejahteraan</th><td><select name="id_jenis_kesejahteraan">
    	<?php
    	$qpg = mysqli_query($link, "select id,isi,ket from pendukung where item='jenis_kesejahteraan' order by ket");
    	if (mysqli_num_rows($qpg)>0) {
    	    while ($rpg = mysqli_fetch_assoc($qpg)) {
    	        $k = "";
    	        $pkt = $rpg['ket']." ".$rpg['isi'];
    	        if (isset($_POST['pangkat'])) {
    	            if ($_POST['pangkat']==$rpg['id']) {
    	                $k = "selected='selected'";
    	            }
    	        } elseif($id_jenis_kesejahteraan == $rpg['id']) {
    	            $k = "selected='selected'";
    	        } echo "<option value='".$rpg['id']."' $k>".$pkt."</option>\n";
    	    }
    	} ?></select></td></tr>
    
    <tr><th>Nama</th><td><input type="text" name="nama" " maxlength="50" value="<?=$_POST['nama'] ?? 'Takaful Fulmedicare Gold'?>" required="required" placeholder="harus diisi" style="width: 50ch"></td></tr>
    <tr><th>Penyelenggara</th><td><input type="text" name="penyelenggara" maxlength="100" value="<?=$_POST['penyelenggara'] ?? 'IPB'?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Thn.mulai</th><td><input type="number" name="tahun_mulai" value="<?=$_POST['tahun_mulai'] ?? 2019?>" required="required"></td></tr>
    <tr><th>Thn.selesai</th><td><input type="number" name="tahun_selesai" value="<?=$_POST['tahun_selesai'] ?? null?>" ></td></tr>
    
	<tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simpan" value="Simpan" formaction="?p=<?=$param?>"></td></tr></table></form>
    <?php
} elseif (isset($_GET['e'])) {
    $id = $_GET['e'];
    $data[] = $id;
    $result_string = wsREF($param."/{".$id."}", $data);
    $result = json_decode($result_string, true);

    $id_jenis_kesejahteraan = $result['id_jenis_kesejahteraan'];
    $nama                   = $result['nama'];
    $penyelenggara          = $result['penyelenggara'];
    $tahun_mulai            = $result['tahun_mulai'];
    $tahun_selesai          = $result['tahun_selesai'];
    ?>
    <form action="" method="post" enctype="multipart/form-data"><table>
    <tr><th>SDM</th><td><?=$_SESSION['login_dos']?></td></tr>
    <tr><th>Jenis kesejahteraan</th><td><select name="id_jenis_kesejahteraan">
    	<?php
    	$qpg = mysqli_query($link, "select id,isi,ket from pendukung where item='jenis_kesejahteraan' order by ket");
    	if (mysqli_num_rows($qpg)>0) {
    	    while ($rpg = mysqli_fetch_assoc($qpg)) {
    	        $k = "";
    	        $pkt = $rpg['ket']." ".$rpg['isi'];
    	        if (isset($_POST['pangkat'])) {
    	            if ($_POST['pangkat']==$rpg['id']) {
    	                $k = "selected='selected'";
    	            }
    	        } elseif($id_jenis_kesejahteraan == $rpg['id']) {
    	            $k = "selected='selected'";
    	        } echo "<option value='".$rpg['id']."' $k>".$pkt."</option>\n";
    	    }
    	} ?></select></td></tr>
    
    <tr><th>Nama</th><td><input type="text" name="nama" maxlength="50" value="<?=$_POST['nama'] ?? $nama?>" required="required" placeholder="harus diisi" style="width: 40ch"></td></tr>
    <tr><th>Penyelenggara</th><td><input type="text" name="penyelenggara" maxlength="100" value="<?=$_POST['penyelenggara'] ?? $penyelenggara?>" required="required" placeholder="harus diisi" style="width: 60ch"></td></tr>
    <tr><th>Thn.mulai</th><td><input type="number" name="tahun_mulai" value="<?=$_POST['tahun_mulai'] ?? $tahun_mulai?>" required="required"></td></tr>
    <tr><th>Thn.selesai</th><td><input type="number" name="tahun_selesai" value="<?=$_POST['tahun_selesai'] ?? $tahun_selesai?>" ></td></tr>
    
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
    
    $result = do_kesejahteraan($id_sdm);
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
?><a href="?p=<?=$param?>&t">tambah data</a>
