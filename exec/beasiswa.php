<!--
// =====================
// beasiswa
// =====================
-->
<?php 
include 'ws.php';
function do_get_beasiswa($id_sdm) {
	$post = ["id_sdm" => $id_sdm ];
	$result_string = wsGET("beasiswa", $post);
	return json_decode($result_string, true);
}
$jenis_beasiswa = array("Prestasi","Kemiskinan","Pendidikan","Unggulan","Ikatan Dinas","Peningkatan Karir","Lainnya");

// ==============================
// mulai dr sini
// ==============================
//echo "<span style='padding: 4px 2px 12px 2px; color:green; font-size: x-large'>Data dapat diubah di SISTER</span><br><br>";
//include 'koneksi.php';
//include 'hdr_id.php';

if (isset($_GET['d'])) {
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
}
if (isset($_GET['p'])) {
    $param = $_GET['p'];
    
    $result = do_get_beasiswa($id_sdm);
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
