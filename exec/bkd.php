<!--
// ===============================================================
// bkd
// ===============================================================
-->
<?php 
include 'ws.php';
function do_get_bkd($act, $id_sdm, $id_smt) {
	$post = ["id_sdm" => $id_sdm, "id_smt" => $id_smt ];
	$result_string = wsGET($act, $post);
	return json_decode($result_string, true);
}
// ==============================
// mulai dr sini
// ==============================
//include 'koneksi.php';
//echo "<h2 style='color:red'>Data bersumber dari PDDIKTI dan tidak dapat diubah di SISTER</h2>";

$result = do_get_bkd('bkd/laporan_akhir_bkd', $id_sdm, $thnlapor.$semlapor);
if ($result!=null) {
    if ($result['kode']==401) {
        echo "<br>Token expired"; exit();
    }
    echo "<br>Record <b>bkd/laporan_akhir_bkd</b><br><br><table>";
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
    echo "blom ada data <b>bkd/laporan_akhir_bkd</b><br><br>";
}

$result = do_get_bkd('bkd/pendidikan', $id_sdm, $thnlapor.$semlapor);
if ($result!=null) {
    echo "<br>Record <b>bkd/pendidikan</b><br><br><table>";
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
    echo "blom ada data <b>bkd/pendidikan</b><br><br>";
}

$result = do_get_bkd('bkd/ajar', $id_sdm, $thnlapor.$semlapor);
if ($result!=null) {
    echo "<br>Record <b>bkd/ajar</b><br><br><table>";
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
    echo "blom ada data <b>bkd/ajar</b><br><br>";
}

$result = do_get_bkd('bkd/tunjang', $id_sdm, $thnlapor.$semlapor);
if ($result!=null) {
    echo "<br>Record <b>bkd/tunjang</b><br><br><table>";
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
    echo "blom ada data <b>bkd/tunjang</b><br><br>";
}

$result = do_get_bkd('bkd/pengmas', $id_sdm, $thnlapor.$semlapor);
if ($result!=null) {
    echo "<br>Record <b>bkd/pengmas</b><br><br><table>";
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
    echo "blom ada data <b>bkd/pengmas</b><br><br>";
}

$result = do_get_bkd('bkd/penelitian', $id_sdm, $thnlapor.$semlapor);
if ($result!=null) {
    echo "<br>Record <b>bkd/penelitian</b><br><br><table>";
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
    echo "blom ada data <b>bkd/penelitian</b><br><br>";
}
?>