<!--
// =====================
// pengujian_mahasiswa
// =====================
-->
<?php
include 'ws.php';
function do_get_pengujian_mahasiswa($id_sdm) {
    $post = ["id_sdm" => $id_sdm, 
        "id_semester" => 20232
    ];
	$result_string = wsGET("pengujian_mahasiswa", $post);
	return json_decode($result_string, true);
}
$param = isset($_GET['p']) ? $_GET['p'] : "";
// ==============================
// mulai dr sini
// ==============================
$param = isset($_GET['p']) ? $_GET['p'] : "";
if (isset($_GET['d'])) {
    $id = $_GET['d'];
    $result_string = wsREF($param."/".$id);
    $detil = json_decode($result_string, true);
    echo "<a href='?p=$param'>kembali</a> | <a href='?p=$param&t'>+ data</a><br><br>Detil <b>$param</b><pre>";
    print_r(json_encode($detil, JSON_PRETTY_PRINT));
    echo "</pre>";
} elseif ($param!="") {
    
    $result = do_get_pengujian_mahasiswa($id_sdm);
    if ($result!=null) {
        if ($result['kode']==401) {
            echo "<br>Token expired"; exit();
        }
        echo "<br>Record <b>$param</b><br><br><table>";
        foreach ($result as $res) {
            if (is_array($res)) {
                echo "<tr><td><table>";
                foreach ($res as $kol => $isi) {
                    if ($kol=="id") {
                        $kol = "<a href='?p=$param&d=$isi' title='detil'>$kol</a>"; // link detil
                        $isi = "<a href='?p=$param&e=$isi' title='edit'>$isi</a>";  // link edit
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
