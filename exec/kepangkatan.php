<!--
// =====================
// kepangkatan
// =====================
-->
<?php 
include 'ws.php';
function do_kepangkatan($id_sdm) {
	$post = ["id_sdm" => $id_sdm ];
	$result_string = wsGET("kepangkatan", $post);
	return json_decode($result_string, true);
}
//echo "<h2 style='color:red'>Data TIDAK dapat diubah di SISTER</h2>";
//include 'koneksi.php';
//include 'hdr_id.php';
if (isset($_GET['p'])) {
    $param = $_GET['p'];
    
    $result = do_kepangkatan($id_sdm);
    if ($result!=null) {
        if ($result['kode']==401) {
            echo "<br>Token expired"; exit();
        }
        echo "<br>Record <b>$param</b><br><br><table>";
        foreach ($result as $res) {
            if (is_array($res)) {
                echo "<tr><td><table>";
                foreach ($res as $kol => $isi) {
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
